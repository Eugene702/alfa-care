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
            $table->string('sla_response_note')->nullable()->change();
            $table->string('email_format_valid_note')->nullable()->change();
            $table->string('zendesk_ticket_content_note')->nullable()->change();
            $table->string('related_case_check_note')->nullable()->change();
            $table->string('task_status_check_note')->nullable()->change();
            $table->string('category_validation_status_note')->nullable()->change();
            $table->string('service_language_quality_note')->nullable()->change();
            $table->string('cso_analysis_completeness_note')->nullable()->change();
            $table->string('solution_fulfillment_note')->nullable()->change();
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
