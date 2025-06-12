<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('spk', function (Blueprint $table) {
            $table->id('spk_id');
            $table->unsignedBigInteger('fasilitas_id');
            $table->foreign('fasilitas_id')
                  ->references('fasilitas_id')
                  ->on('fasilitas')
                  ->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('spk');
    }
};
