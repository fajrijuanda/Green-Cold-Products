<?php

// database/migrations/xxxx_xx_xx_create_user_activities_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Hubungkan ke tabel users
            $table->string('activity'); // Deskripsi aktivitas
            $table->string('type'); // Tipe aktivitas (misal: create, update, delete)
            $table->string('description');
            $table->timestamp('activity_date'); // Waktu aktivitas
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_activities');
    }
};
