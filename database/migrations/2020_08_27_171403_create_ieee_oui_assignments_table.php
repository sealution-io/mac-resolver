<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIeeeOuiAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ieee_oui_assignments', function (Blueprint $table) {
            $table->string('oui', 9)->primary();
            $table->string('organization', 150);
            $table->string('address', 250);
            $table->string('registry', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ieee_oui_assignments');
    }
}
