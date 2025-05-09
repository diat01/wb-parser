<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->integer('income_id');
            $table->string('number')->nullable();
            $table->date('date');
            $table->date('last_change_date');
            $table->string('supplier_article');
            $table->string('tech_size');
            $table->bigInteger('barcode');
            $table->integer('quantity');
            $table->decimal('total_price', 12, 2)->default(0);
            $table->date('date_close');
            $table->string('warehouse_name');
            $table->bigInteger('nm_id');
            $table->timestamps();

            // Indexes
            $table->index('income_id');
            $table->index('nm_id');
            $table->index('date');
            $table->index('date_close');
            $table->index('supplier_article');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
