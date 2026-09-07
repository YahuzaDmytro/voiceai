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
        Schema::create('calls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('provider')->nullable();
            $table->string('external_id')->nullable();

            $table->string('status')->default('pending');

            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable();

            $table->unsignedInteger('duration')->nullable();

            $table->text('transcript')->nullable();
            $table->text('recording_url')->nullable();

            $table->jsonb('metadata')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('calls');
    }
};
