<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn('exclusive');
            $table->decimal('price', 8, 2)->default(0.00)->after('details');
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            $table->boolean('exclusive')->default(false)->after('details');
            $table->dropColumn('price');
        });
    }
};