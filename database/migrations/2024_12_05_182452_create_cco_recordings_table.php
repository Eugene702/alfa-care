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
        Schema::create('cco_recordings', function (Blueprint $table) {
            $table->id();
            $table->string('agentId');
            $table->string('sample_name');
            $table->string('ticket');

            $table->integer('cco_correct_opening_greeting');
            $table->string('cco_correct_opening_greeting_note')->nullable();

            $table->integer('cco_asks_customer_name');
            $table->string('cco_asks_customer_name_note')->nullable();

            $table->integer('cco_confirms_service_adequacy');
            $table->string('cco_confirms_service_adequacy_note')->nullable();

            $table->integer('cco_correct_closing_greeting');
            $table->string('cco_correct_closing_greeting_note')->nullable();

            $table->integer('hold_line');
            $table->string('hold_line_note')->nullable();

            $table->integer('cco_good_tone_and_intonation');
            $table->string('cco_good_tone_and_intonation_note')->nullable();

            $table->integer('cco_good_speaking_speed_articulation_volume');
            $table->string('cco_good_speaking_speed_articulation_volume_note')->nullable();

            $table->integer('cco_mentions_customer_name');
            $table->string('cco_mentions_customer_name_note')->nullable();

            $table->integer('cco_good_service_ethics_language');
            $table->string('cco_good_service_ethics_language_note')->nullable();

            $table->integer('cco_confirms_customer_complaint');
            $table->string('cco_confirms_customer_complaint_note')->nullable();

            $table->integer('invalid_case_type_category');
            $table->string('invalid_case_type_category_note')->nullable();

            $table->integer('task_suitability');
            $table->string('task_suitability_note')->nullable();

            $table->integer('other_zendesk');
            $table->string('other_zendesk_note')->nullable();

            $table->integer('cco_gives_positive_statements');
            $table->string('cco_gives_positive_statements_note')->nullable();

            $table->integer('cco_does_not_reask_customer_info');
            $table->string('cco_does_not_reask_customer_info_note')->nullable();

            $table->integer('cco_explores_customer_info_needs');
            $table->string('cco_explores_customer_info_needs_note')->nullable();

            $table->integer('no_service_transfer');
            $table->string('no_service_transfer_note')->nullable();

            $table->integer('cco_explanation_accuracy_complete');
            $table->string('cco_explanation_accuracy_complete_note')->nullable();

            $table->integer('cco_provides_complete_solutions');
            $table->string('cco_provides_complete_solutions_note')->nullable();

            $table->integer('cco_clear_voice_quality');
            $table->string('cco_clear_voice_quality_note')->nullable();

            $table->integer('no_background_noise');
            $table->string('no_background_noise_note')->nullable();

            $table->timestamps();
            $table->foreign('agentId')->references('id')->on('agents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cco_recordings');
    }
};
