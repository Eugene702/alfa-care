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
            $table->integer('sla_response')->change();
            $table->integer('email_format_valid')->change();
            $table->integer('zendesk_ticket_content')->change();
            $table->integer('related_case_check')->change();
            $table->integer('task_status_check')->change();
            $table->integer('category_validation_status')->change();
            $table->integer('service_language_quality')->change();
            $table->integer('cso_analysis_completeness')->change();
            $table->integer('solution_fulfillment')->change();
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
