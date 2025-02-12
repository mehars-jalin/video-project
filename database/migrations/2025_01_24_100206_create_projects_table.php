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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('company_id'); // Foreign key to companies table
            $table->string('project_name');
            $table->string('project_type');

            // Additional fields
            $table->integer('videos_allotted');
            $table->integer('videos_completed');
            $table->text('project_notes')->nullable();
            $table->string('status');

            // One-time fields
            $table->date('due_date')->nullable();
            $table->text('video_topics')->nullable(); // You can use JSON if needed: ->json('video_topics')->nullable();

            // Recurring fields
            $table->integer('months_between_shoots')->nullable();
            $table->date('last_shoot_date')->nullable();

            // Foreign key constraint
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->timestamp('created_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
