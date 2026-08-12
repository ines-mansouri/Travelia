<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHajjUmrahsTable extends Migration
{
    public function up()
    {
        Schema::create('hajj_umrahs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('content');
            $table->string('image');
            $table->integer('category_id');
            $table->enum('type', ['hajj', 'umrah'])->default('umrah');
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('duration_days')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('hajj_umrahs');
    }
}
