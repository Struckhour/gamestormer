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
        Schema::table('project_user', function (Blueprint $table) {
            // Check if the old column exists before trying to rename it
            // This is good practice, especially if you might run migrations on different environments
            if (Schema::hasColumn('project_user', 'created_by')) {
                $table->renameColumn('created_by', 'attached_by_user_id'); // <-- Change 'created_by' to your old name
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_user', function (Blueprint $table) {
            // In the down method, we reverse the action
            // If the new column exists, rename it back to the old one
            if (Schema::hasColumn('project_user', 'attached_by_user_id')) { // <-- Use your new column name here
                $table->renameColumn('attached_by_user_id', 'created_by'); // <-- Change 'created_by' to your old name
            }
        });
    }
};
