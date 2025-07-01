<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('headline');
            $table->text('details');
            $table->boolean('exclusive');
            $table->string('picture')->nullable();
            $table->dateTime('date_event');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
