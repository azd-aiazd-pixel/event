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
        Schema::create('products', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->foreignId('store_id')->constrained('stores')->onDelete('cascade');
        $table->foreignId('category_id')->nullable()->constrained()->onDelete('set null');
     $table->foreignId('unit_measure_id')->nullable()->constrained()->onDelete('set null');
        $table->decimal('unit_price', 10, 2);
        $table->string('picture')->nullable();
        
        $table->boolean('is_stockable')->default(true);
            $table->integer('quantity')->nullable();
        
        $table->boolean('is_active')->default(true);
          $table->softDeletes();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
