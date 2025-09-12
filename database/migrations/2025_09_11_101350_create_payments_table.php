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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            // User information
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('user_email')->nullable();
            $table->string('user_name')->nullable();

            // Payment details
            $table->string('razorpay_payment_id')->unique();
            $table->string('razorpay_order_id');
            $table->string('razorpay_signature');

            // Plan information
            $table->string('plan_name');
            $table->string('plan_type'); // Standard, Pro Monthly, Premier Yearly
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('INR');

            // Payment status
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending');
            $table->timestamp('payment_date')->nullable();

            // Additional information
            $table->string('receipt_id')->nullable();
            $table->json('razorpay_response')->nullable(); // Store full Razorpay response
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();

            // Subscription details
            $table->timestamp('subscription_start')->nullable();
            $table->timestamp('subscription_end')->nullable();
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // Indexes
            $table->index(['user_id', 'status']);
            $table->index(['plan_name', 'status']);
            $table->index('payment_date');
            $table->index('razorpay_payment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
