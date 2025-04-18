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
    {Schema::create('logements', function (Blueprint $table) {
        $table->id('id_log');
        $table->float('prix_log');
        $table->string('localisation_log');
        $table->date('date_creation_log');
        $table->enum('type_log', ['studio', 'appartement', 'maison']);
        $table->string('photo_log');
        $table->integer('nombre_colocataire_log');
        $table->string('ville');
        $table->integer('views')->default(0);
        $table->foreignId('proprietaire_id')->constrained('utilisateurs')->onDelete('cascade');
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logements');
    }
};
