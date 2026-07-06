<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('contrat_id')->constrained('contrats')->onDelete('cascade');
            $table->float('montant');
            $table->date('date');
            $table->enum('moyen', ['wave', 'orange_money']);
            $table->string('reference_paytech')->nullable();
            $table->enum('statut', ['en_attente', 'confirme', 'echoue'])->default('en_attente');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('paiements');
    }
};
