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
        Schema::table('employees', function (Blueprint $table) {
            $table->string('document_type')->nullable()->after('first_name');
            $table->string('document_number')->nullable()->after('document_type');
            $table->string('email')->nullable()->after('address');
            $table->string('phone')->nullable()->after('email');
            $table->string('job_title')->nullable()->after('phone');
            $table->enum('status', ['activo', 'inactivo'])->default('activo')->after('job_title');
            $table->index('document_number');
            $table->index('status');
            $table->index('job_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['document_type', 'document_number', 'email', 'phone', 'job_title', 'status']);
        });
    }
};
