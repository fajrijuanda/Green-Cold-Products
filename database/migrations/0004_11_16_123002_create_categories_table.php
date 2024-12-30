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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Untuk Name dari kategori
            $table->string('slug')->unique(); // Untuk slug
            $table->text('category_detail')->nullable(); // Untuk deskripsi kategori
            $table->string('cat_image')->nullable()->default('');
            $table->enum('status', ['Published', 'Scheduled', 'Inactive'])->default('Published'); // Status kategori
            $table->timestamps();
            $table->softDeletes(); // Untuk soft delete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
