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
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voting_session_id')->constrained('voting_sessions')->onDelete('cascade');
            $table->foreignId('voter_id')->constrained('voters')->onDelete('cascade');
            $table->string('token', 64)->unique();
            $table->enum('status', ['pending', 'voted', 'expired'])->default('pending');
            $table->dateTime('voted_at')->nullable();
            $table->dateTime('expires_at');
            $table->timestamps();

            $table->unique(['voting_session_id', 'voter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
