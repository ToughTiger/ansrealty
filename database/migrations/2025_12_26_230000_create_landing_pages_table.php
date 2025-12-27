<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('landing_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('subtitle')->nullable();
            
            // SEO Meta
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            
            // Hero Section
            $table->string('hero_heading')->nullable();
            $table->text('hero_subheading')->nullable();
            
            // CTA
            $table->text('cta_text')->nullable();
            $table->string('cta_button_text')->default('Book Now');
            
            // Content
            $table->json('features')->nullable();
            $table->json('amenities')->nullable();
            $table->json('location_benefits')->nullable();
            $table->text('special_offer_text')->nullable();
            
            // Form Section
            $table->string('form_heading')->default('Get Free Consultation');
            $table->text('form_subheading')->nullable();
            
            // Status & Analytics
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('views_count')->default(0);
            $table->unsignedInteger('leads_count')->default(0);
            
            // Campaign Tracking
            $table->string('campaign_source')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            $table->index(['slug', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('landing_pages');
    }
};
