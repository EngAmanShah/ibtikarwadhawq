<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/** معالج التثبيت من المتصفح — بدون أي أوامر */
class InstallController extends Controller
{
    public function welcome()
    {
        return view('install.welcome', ['step' => 1]);
    }

    public function requirements()
    {
        $checks = [
            ['إصدار PHP 8.2 أو أحدث', version_compare(PHP_VERSION, '8.2.0', '>='), PHP_VERSION],
        ];
        foreach (['pdo_mysql' => 'PDO MySQL', 'mbstring' => 'Mbstring', 'openssl' => 'OpenSSL', 'tokenizer' => 'Tokenizer', 'xml' => 'XML', 'ctype' => 'Ctype', 'json' => 'JSON', 'fileinfo' => 'Fileinfo', 'bcmath' => 'BCMath'] as $ext => $label) {
            $checks[] = ["إضافة $label", extension_loaded($ext), ''];
        }
        $checks[] = ['إضافة GD (لضغط الصور — اختياري)', extension_loaded('gd'), 'اختياري'];
        foreach (['storage', 'bootstrap/cache', 'public/uploads', '.env'] as $p) {
            $checks[] = ["صلاحية الكتابة: $p", is_writable(base_path($p)), ''];
        }
        $ok = collect($checks)->reject(fn ($c) => str_contains($c[0], 'اختياري'))->every(fn ($c) => $c[1]);
        return view('install.requirements', ['step' => 2, 'checks' => $checks, 'ok' => $ok]);
    }

    public function database()
    {
        return view('install.database', ['step' => 3]);
    }

    protected function dbRules(): array
    {
        return ['host' => 'required|string', 'port' => 'required|numeric', 'database' => 'required|string', 'username' => 'required|string', 'password' => 'nullable|string'];
    }

    protected function connect(array $d): ?string
    {
        try {
            new \PDO("mysql:host={$d['host']};port={$d['port']};dbname={$d['database']};charset=utf8mb4", $d['username'], $d['password'] ?? '', [\PDO::ATTR_TIMEOUT => 6, \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]);
            return null;
        } catch (\Throwable $e) {
            $m = $e->getMessage();
            if (str_contains($m, 'Access denied')) return 'اسم المستخدم أو كلمة المرور غير صحيحة.';
            if (str_contains($m, 'Unknown database')) return 'قاعدة البيانات غير موجودة — أنشئها أولًا من لوحة الاستضافة.';
            if (str_contains($m, 'getaddrinfo') || str_contains($m, 'refused') || str_contains($m, 'timed out')) return 'تعذر الوصول للخادم — تأكد من عنوان الخادم (غالبًا localhost).';
            return 'تعذر الاتصال: ' . $m;
        }
    }

    public function testDatabase(Request $request)
    {
        $d = $request->validate($this->dbRules());
        $err = $this->connect($d);
        return response()->json(['ok' => ! $err, 'message' => $err ?: 'تم الاتصال بقاعدة البيانات بنجاح']);
    }

    public function saveDatabase(Request $request)
    {
        $d = $request->validate($this->dbRules(), [], ['host' => 'عنوان الخادم', 'port' => 'المنفذ', 'database' => 'اسم قاعدة البيانات', 'username' => 'اسم المستخدم']);
        if ($err = $this->connect($d)) return back()->withInput()->withErrors(['database' => $err]);

        $this->writeEnv([
            'DB_CONNECTION' => 'mysql', 'DB_HOST' => $d['host'], 'DB_PORT' => $d['port'], 'DB_DATABASE' => $d['database'],
            'DB_USERNAME' => $d['username'], 'DB_PASSWORD' => $d['password'] ?? '', 'APP_URL' => $request->getSchemeAndHttpHost(),
        ]);
        return redirect()->route('install.account');
    }

    public function account()
    {
        return view('install.account', ['step' => 4]);
    }

    public function finish(Request $request)
    {
        $data = $request->validate([
            'site_name' => 'required|string|max:120',
            'name' => 'required|string|max:100',
            'email' => 'required|email|max:191',
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [], ['site_name' => 'اسم الموقع', 'name' => 'الاسم', 'email' => 'البريد الإلكتروني', 'password' => 'كلمة المرور']);

        @set_time_limit(300);
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
            Artisan::call('db:seed', ['--force' => true]);
            Setting::put('site_name', $data['site_name']);
            Setting::put('site_url', $request->getSchemeAndHttpHost());
            Setting::flush();
            User::create(['name' => $data['name'], 'email' => $data['email'], 'password' => Hash::make($data['password'])]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['install' => 'حدث خطأ أثناء التثبيت: ' . $e->getMessage()]);
        }

        file_put_contents(storage_path('app/installed.lock'), now()->toDateTimeString());
        try { Artisan::call('optimize:clear'); } catch (\Throwable $e) {}

        return redirect()->route('install.done');
    }

    public function done()
    {
        return view('install.done', ['step' => 5]);
    }

    protected function writeEnv(array $values): void
    {
        $path = base_path('.env');
        $env = is_file($path) ? file_get_contents($path) : file_get_contents(base_path('.env.example'));
        foreach ($values as $k => $v) {
            $v = (string) $v;
            $q = preg_match('/[\s#"\'$\\\\=]/', $v) || $v === '' ? '"' . str_replace(['\\', '"', '$'], ['\\\\', '\"', '\$'], $v) . '"' : $v;
            $line = $k . '=' . $q;
            $env = preg_match("/^{$k}=.*$/m", $env) ? preg_replace("/^{$k}=.*$/m", str_replace(['\\', '$'], ['\\\\', '\$'], $line), $env) : $env . "\n" . $line;
        }
        file_put_contents($path, $env);
    }
}
