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
        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->index('invoice_number');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['invoice_number']);
            $table->dropIndex(['created_at']);
        });
    }
};
