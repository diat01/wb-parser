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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->date('last_change_date')->nullable();
            $table->string('supplier_article')->nullable();
            $table->string('tech_size')->nullable();
            $table->bigInteger('barcode');
            $table->integer('quantity')->default(0);
            $table->boolean('is_supply')->default(false)->nullable();
            $table->boolean('is_realization')->default(false)->nullable();
            $table->integer('quantity_full')->default(0)->nullable();
            $table->string('warehouse_name');
            $table->integer('in_way_to_client')->default(0)->nullable();
            $table->integer('in_way_from_client')->default(0)->nullable();
            $table->bigInteger('nm_id');
            $table->string('subject')->nullable();
            $table->string('category')->nullable();
            $table->string('brand')->nullable();
            $table->bigInteger('sc_code')->nullable();
            $table->decimal('price', 12, 2);
            $table->decimal('discount', 5, 2)->default(0);
            $table->timestamps();

            // Indexes
            $table->index(['nm_id', 'warehouse_name']);
            $table->index(['supplier_article', 'tech_size']);
            $table->index('date');
            $table->index('last_change_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
