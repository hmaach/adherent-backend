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
        Schema::create('announces', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\User::class, "user_id");
            $table->bigInteger('order')->nullable();
            $table->boolean('approved')->default(false);
            $table->text('desc')->nullable();
            $table->dateTime('debut');
            $table->dateTime('fin')->nullable();
            $table->string('img')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announces');
    }
};
