<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('settings', function (Blueprint $t) {
            $t->id();
            $t->string('key', 120)->unique();
            $t->longText('value')->nullable();
            $t->timestamps();
        });

        Schema::create('services', function (Blueprint $t) {
            $t->id();
            $t->string('slug', 120)->unique();
            $t->string('title');
            $t->string('subtitle')->nullable();
            $t->string('icon', 80)->default('fa-star');
            $t->text('excerpt')->nullable();
            $t->string('card_image')->nullable();
            $t->string('hero_image')->nullable();
            $t->string('hero_title')->nullable();
            $t->text('hero_text')->nullable();
            $t->text('cta_text')->nullable();
            $t->string('meta_title')->nullable();
            $t->text('meta_description')->nullable();
            $t->boolean('in_menu')->default(true);
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('service_items', function (Blueprint $t) {
            $t->id();
            $t->foreignId('service_id')->constrained()->cascadeOnDelete();
            $t->string('title');
            $t->string('icon', 80)->default('fa-star');
            $t->string('image')->nullable();
            $t->json('points')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('slides', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('image')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('clients', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('logo')->nullable();
            $t->string('url')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('documents', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('preview_image')->nullable();
            $t->string('file')->nullable();
            $t->date('expires_at')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('branches', function (Blueprint $t) {
            $t->id();
            $t->string('title');
            $t->string('icon', 80)->default('fa-building');
            $t->string('phone', 40)->nullable();
            $t->string('whatsapp', 40)->nullable();
            $t->string('address')->nullable();
            $t->text('map_embed')->nullable();
            $t->string('map_title')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('blocks', function (Blueprint $t) {
            $t->id();
            $t->string('group', 60)->index();
            $t->string('icon', 80)->nullable();
            $t->string('label')->nullable();
            $t->string('title')->nullable();
            $t->text('text')->nullable();
            $t->boolean('is_active')->default(true);
            $t->unsignedInteger('sort')->default(0);
            $t->timestamps();
        });

        Schema::create('leads', function (Blueprint $t) {
            $t->id();
            $t->string('name');
            $t->string('phone', 40);
            $t->string('service')->nullable();
            $t->string('space')->nullable();
            $t->text('message')->nullable();
            $t->string('status', 20)->default('new')->index();
            $t->text('notes')->nullable();
            $t->string('ip', 45)->nullable();
            $t->timestamps();
        });

        Schema::create('visits', function (Blueprint $t) {
            $t->id();
            $t->date('day');
            $t->string('path', 191);
            $t->unsignedInteger('hits')->default(0);
            $t->unique(['day', 'path']);
        });

        Schema::create('activities', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $t->string('action', 40);
            $t->string('subject');
            $t->string('icon', 60)->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        foreach (['activities', 'visits', 'leads', 'blocks', 'branches', 'documents', 'clients', 'slides', 'service_items', 'services', 'settings'] as $table) {
            Schema::dropIfExists($table);
        }
    }
};
