<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salon_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->cascadeOnDelete();
            $table->foreignId('salon_id')->nullable()->constrained('salons')->cascadeOnDelete();
            $table->foreignId('payment_detail_id')->nullable()->constrained('payment_details')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('salon_services')->cascadeOnDelete();
            $table->timestamp('order_confirmation_date');
            $table->double('payment');
            $table->double('curlu_earning');
            $table->double('salon_earning');
            $table->enum('status',['Upcoming','Past'])->default('Upcoming');
            $table->string('schedule_date');
            $table->string('schedule_time');
            $table->string('invoice_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salon_invoices');
    }
};
