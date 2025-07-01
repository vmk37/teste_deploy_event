    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        public function up(): void
        {
            Schema::create('addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('event_id')->constrained()->onDelete('cascade');
                $table->string('cep')->nullable();
                $table->string('street')->nullable();
                $table->string('neighborhood')->nullable();
                $table->string('municipality')->nullable();
                $table->string('state')->nullable();
                $table->string('addressNumber')->nullable();
                $table->timestamps();
            });
        }

        public function down(): void
        {
            Schema::dropIfExists('addresses');
        }
    };
