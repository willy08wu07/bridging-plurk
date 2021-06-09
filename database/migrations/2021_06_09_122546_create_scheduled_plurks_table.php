<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScheduledPlurksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scheduled_plurks', function (Blueprint $table) {
            $table->id();
            $table->char('token', 12)->comment('噗浪提供的使用者 token。');
            $table->char('token_secret', 32)->comment('噗浪提供的使用者 token。');
            $table->enum('qualifier', [
                '',
                'plays',
                'buys',
                'sells',
                'loves',
                'likes',
                'shares',
                'hates',
                'wants',
                'wishes',
                'needs',
                'has',
                'will',
                'hopes',
                'asks',
                'wonders',
                'feels',
                'thinks',
                'draws',
                'is',
                'says',
                'eats',
                'writes',
                'whispers',
            ])->comment('修飾詞。');
            $table->string('content', 360)->comment('噗文內容。長度限制與噗浪系統相同。');
            $table->timestamp('scheduled_time')->comment('排程的發噗時間。')->index();
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
        Schema::dropIfExists('scheduled_plurks');
    }
}
