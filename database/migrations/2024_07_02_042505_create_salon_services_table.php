<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('salon_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('salon_id')->constrained('salons');
            $table->foreignId('category_id')->constrained('categories');
            $table->string('service_name');
            $table->string('service_description')->nullable();
            $table->string('price');
            $table->string('discount_price')->nullable();
            $table->string('service_image');
            $table->string('service_status')->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salon_services');
    }
};
