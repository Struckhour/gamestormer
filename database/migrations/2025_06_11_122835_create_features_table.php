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
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('time_allotted')->nullable(); // In hours, days, etc. - define your unit
            $table->text('progress')->nullable();
            $table->text('content')->nullable(); // For rich text content if you implement editor later
            $table->integer('sort_order')->nullable(); // For ordering features within a project
            $table->dateTime('deadline')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            // Foreign Keys
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('department_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('subdepartment_id')->nullable()->constrained()->onDelete('set null');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
