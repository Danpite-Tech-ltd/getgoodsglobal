<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('measurement_units', function (Blueprint $table) {
            $table->string('custom')->default(0);
            $table->boolean('is_ml')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('measurement_units', function (Blueprint $table) {
            $table->dropColumn(['custom', 'is_ml']);
        });
    }
};
