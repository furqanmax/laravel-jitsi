<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('meeting_participants', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('meeting_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('status', ['waiting', 'approved', 'kicked'])->default('waiting');
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('meeting_participants');
    }
};
