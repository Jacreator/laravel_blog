<?php

use App\Models\Post;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->string('title');
            $table->string('slug')->unique()->nullable();
            $table->string('image');
            $table->string('status')->default(Post::UNVERIFIED_POST);
            $table->string('content');
            $table->bigInteger('author')->unsigned();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('posts', function ($table) {
            $table->foreign('author')->references('id')->on('Users');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
