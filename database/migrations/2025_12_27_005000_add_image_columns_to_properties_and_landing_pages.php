<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->json('images')->nullable()->after('description');
            $table->json('floor_plans')->nullable()->after('images');
            $table->json('documents')->nullable()->after('floor_plans');
        });
        
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->string('hero_image')->nullable()->after('hero_subheading');
            $table->json('gallery_images')->nullable()->after('form_subheading');
            $table->string('featured_image')->nullable()->after('gallery_images');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn(['images', 'floor_plans', 'documents']);
        });
        
        Schema::table('landing_pages', function (Blueprint $table) {
            $table->dropColumn(['hero_image', 'gallery_images', 'featured_image']);
        });
    }
};
