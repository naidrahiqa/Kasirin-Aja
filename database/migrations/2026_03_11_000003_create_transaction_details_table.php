<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates the transaction_details table (line items inside an invoice).
     * Stores the unit_price_at_time_of_sale so the receipt stays accurate
     * even if the product price is updated later.
     */
    public function up(): void
    {
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transaction_id')
                ->constrained('transactions')
                ->onDelete('cascade'); // If a transaction is deleted, remove its details too
            $table->foreignId('product_id')
                ->constrained('products')
                ->onDelete('restrict'); // Prevent hard-deleting products that have transaction history
            $table->integer('quantity');
            $table->decimal('unit_price_at_time_of_sale', 10, 2);
            $table->decimal('subtotal', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaction_details');
    }
};
