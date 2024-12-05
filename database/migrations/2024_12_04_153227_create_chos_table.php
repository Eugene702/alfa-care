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
        Schema::create('chos', function (Blueprint $table) {
            $table->id();
            $table->string('agentId');
            $table->string('sample_name');
            $table->string('ticket');

            $table->integer('sla_response');
            $table->string('sla_response_note')->nullable();

            $table->integer('ticket_closure_sla');
            $table->string('ticket_closure_sla_note')->nullable();

            $table->integer('email_punctuation_check');
            $table->string('email_punctuation_check_note')->nullable();

            $table->integer('ticket_text_content');
            $table->string('ticket_text_content_note')->nullable();

            $table->integer('case_linkage_check');
            $table->string('case_linkage_check_note')->nullable();

            $table->integer('task_status_check');
            $table->string('task_status_check_note')->nullable();

            $table->integer('zendesk_category_check');
            $table->string('zendesk_category_check_note')->nullable();

            $table->integer('is_language_ethical');
            $table->string('is_language_ethical_note')->nullable();

            $table->integer('analysis_correctness');
            $table->string('analysis_correctness_note')->nullable();

            $table->integer('complete_solution_status');
            $table->string('complete_solution_status_note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chos');
    }
};
