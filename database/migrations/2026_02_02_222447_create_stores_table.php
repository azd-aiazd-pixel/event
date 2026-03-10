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
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade'); 
            $table->enum('workflow_type', ['direct', 'queue'])->default('direct');
            $table->string('name');
            $table->string('logo')->nullable();
              $table->string('theme_primary_color')->nullable();
            $table->string('theme_bg_color')->nullable();
            $table->string('theme_text_color')->nullable();
            $table->string('theme_bg_image')->nullable();
            $table->string('theme_body_bg_image')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
              $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
