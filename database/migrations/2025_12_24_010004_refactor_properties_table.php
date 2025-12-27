<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['address', 'price', 'status', 'type', 'features']);
            
            // Add new columns
            $table->string('name')->after('id');
            $table->foreignId('builder_id')->nullable()->after('name')->constrained()->nullOnDelete();
            $table->string('project_name')->nullable()->after('builder_id');
            $table->string('location')->after('project_name');
            $table->string('city')->after('location');
            $table->string('state')->default('Maharashtra')->after('city');
            $table->string('pincode')->nullable()->after('state');
            $table->string('rera_number')->nullable()->after('pincode');
            
            $table->enum('property_type', ['Flat', 'Villa', 'Plot', 'Commercial', 'Penthouse', 'Studio'])->after('rera_number');
            $table->enum('listing_type', ['Sale', 'Rent', 'Lease'])->default('Sale')->after('property_type');
            
            $table->decimal('carpet_area', 10, 2)->nullable()->after('listing_type');
            $table->decimal('built_up_area', 10, 2)->nullable()->after('carpet_area');
            $table->string('area_unit')->default('sq.ft')->after('built_up_area');
            
            $table->integer('bedrooms')->nullable()->after('area_unit');
            $table->integer('bathrooms')->nullable()->after('bedrooms');
            $table->integer('balconies')->nullable()->after('bathrooms');
            $table->integer('parking')->nullable()->after('balconies');
            $table->integer('floor_number')->nullable()->after('parking');
            $table->integer('total_floors')->nullable()->after('floor_number');
            
            $table->decimal('price_min', 15, 2)->nullable()->after('total_floors');
            $table->decimal('price_max', 15, 2)->nullable()->after('price_min');
            $table->string('price_unit')->default('INR')->after('price_max');
            
            $table->json('amenities')->nullable()->after('price_unit');
            $table->date('possession_date')->nullable()->after('amenities');
            $table->enum('possession_status', ['Ready to Move', 'Under Construction', 'Upcoming'])->default('Under Construction')->after('possession_date');
            
            $table->enum('availability_status', ['Available', 'Sold', 'Reserved', 'On Hold'])->default('Available')->after('possession_status');
            $table->boolean('is_featured')->default(false)->after('availability_status');
            $table->boolean('is_active')->default(true)->after('is_featured');
            
            $table->softDeletes()->after('updated_at');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            // Restore old structure
            $table->dropColumn([
                'name', 'builder_id', 'project_name', 'location', 'city', 'state', 'pincode', 'rera_number',
                'property_type', 'listing_type', 'carpet_area', 'built_up_area', 'area_unit',
                'bedrooms', 'bathrooms', 'balconies', 'parking', 'floor_number', 'total_floors',
                'price_min', 'price_max', 'price_unit', 'amenities', 'possession_date', 'possession_status',
                'availability_status', 'is_featured', 'is_active'
            ]);
            
            $table->dropSoftDeletes();
            
            $table->string('address');
            $table->decimal('price', 10, 2);
            $table->enum('status', ['For Sale', 'For Rent', 'Sold'])->default('For Sale');
            $table->enum('type', ['House', 'Apartment', 'Condo']);
            $table->text('features')->nullable();
        });
    }
};
