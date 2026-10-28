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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subdocument_id')->constrained('subdocuments')->cascadeOnDelete();
            $table->foreignId('opd_id')->constrained('opds')->cascadeOnDelete();
            $table->enum('status', [
                'not_uploaded',
                'pending_verification',
                'needs_revision',
                'resubmitted',
                'verified',
                'rejected',
            ])->default('not_uploaded');
            $table->unsignedBigInteger('current_version_id')->nullable();
            $table->foreignId('submitted_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['subdocument_id', 'opd_id']);
            $table->index(['opd_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
