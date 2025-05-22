<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            // Composite index for subject-date queries
            $table->index(['subject_id', 'date']);
            
            // Composite index for student-subject queries
            $table->index(['student_id', 'subject_id']);
            
            // Single index for date-only queries
            $table->index(['date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['subject_id', 'date']);
            $table->dropIndex(['student_id', 'subject_id']);
            $table->dropIndex(['date']);
        });
    }
}
