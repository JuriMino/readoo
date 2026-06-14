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
        Schema::create('knowledges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->foreign('book_id','knowledge_book_id')->references('id')->on('books')->cascadeOnDelete();
            $table->index('book_id');
            $table->string('title',100);
            $table->string('book_page',100)->nullable();
            $table->text('content');
            $table->string('tag1',50)->nullable();
            $table->string('tag2',50)->nullable();
            $table->string('tag3',50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('knowledges', function(Blueprint $table){
            $table->dropForeign('knowledge_book_id');
        });
        Schema::dropIfExists('knowledges');
    }
};
