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
        Schema::table('ccos', function (Blueprint $table) {
            $table->string('sla_response_note');
            $table->string('email_format_valid_note');
            $table->string('zendesk_ticket_content_note');
            $table->string('related_case_check_note');
            $table->string('task_status_check_note');
            $table->string('category_validation_status_note');
            $table->string('service_language_quality_note');
            $table->string('cso_analysis_completeness_note');
            $table->string('solution_fulfillment_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ccos', function (Blueprint $table) {
            //
        });
    }
};
