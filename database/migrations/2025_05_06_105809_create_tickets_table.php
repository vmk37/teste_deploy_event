<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->string('ticket_number')->unique();
            $table->string('user_name');
            $table->string('user_email');
            $table->string('user_cpf');
            $table->string('event_headline');
            $table->dateTime('event_date');
            $table->string('event_location');
            $table->decimal('price', 8, 2)->default(0.00);
            $table->enum('type', ['normal', 'vip'])->default('normal');
            $table->string('qr_code')->nullable();
            $table->timestamps();
            $table->index(['user_id', 'event_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};