<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->index(); //親（users）が削除されたら子（books）も一緒に削除する
            $table->string('title',255);
            $table->string('author',100);
            $table->string('publisher',100)->nullable();
            $table->enum('status',['unread','reading','finished'])->default('unread')->index();
            $table->enum('genre',[
                'business','self_help','technology','novel',
                'liberal_arts','history','science','health_beauty',
                'economics','philosophy','psychology','culture','other'
            ])->index();
            $table->date('started_at')->nullable();
            $table->date('finished_at')->nullable();
            $table->text('summary')->nullable();
            $table->text('memo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('books', function(Blueprint $table){
            $table->dropForeign('1');
        });
        Schema::dropIfExists('books');
    }
};
