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
        Schema::create('ccos', function (Blueprint $table) {
            $table->id();
            $table->string('agentId');
            $table->string('sla_response');
            $table->string('email_format_valid');
            $table->string('zendesk_ticket_content');
            $table->string('related_case_check');
            $table->string('task_status_check');
            $table->string('category_validation_status');
            $table->string('service_language_quality');
            $table->string('cso_analysis_completeness');
            $table->string('solution_fulfillment');
            $table->timestamps();

            $table->foreign('agentId')->references('id')->on('agents');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ccos');
    }
};
