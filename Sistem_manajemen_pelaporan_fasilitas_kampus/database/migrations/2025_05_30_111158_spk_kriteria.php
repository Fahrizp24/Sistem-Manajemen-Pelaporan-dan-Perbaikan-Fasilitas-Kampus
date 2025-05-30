<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('spk_kriteria', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('spk_id');
            $table->unsignedBigInteger('kriteria_id');
            $table->decimal('nilai', 10, 2);

            $table->foreign('spk_id')
                ->references('spk_id')
                ->on('spk')
                ->onDelete('cascade');

            $table->foreign('kriteria_id')
                ->references('kriteria_id')
                ->on('kriteria')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spk_kriteria');
    }
};
