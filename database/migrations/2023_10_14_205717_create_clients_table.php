<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Family;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 100);
            $table->string('second_name', 100);
            $table->string('third_name', 100);
            $table->date('birthday');
            $table->string('mail', 100)->unique()->nullable();
            $table->json('phones')->nullable();
            $table->enum('family',[Family::values()]);
            $table->text('about_me');
            $table->json('files')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
