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
        Schema::create('resin_ratios', function (Blueprint $table) {
            $table->id();
            $table->string('resin');
            $table->string('hardener');
            $table->string('resin_density');
            $table->string('hardener_density');
            $table->string('wastage');
            $table->string('status')->default(1);
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
        Schema::dropIfExists('resin_ratios');
    }
};
