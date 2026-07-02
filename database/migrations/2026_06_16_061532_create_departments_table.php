<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departments', function (Blueprint $blueprint) {
            $blueprint->uuid('id')->primary(); // 🛡️ استفاده از UUID به جای عدد
            $blueprint->json('title'); // 🌐 ذخیره دو زبانه به صورت JSON
            $blueprint->string('slug')->unique(); 
            $blueprint->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('departments');
    }
};