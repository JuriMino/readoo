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
        Schema::create('actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_id');
            $table->foreign('book_id','action_book_id')->references('id')->on('books')->cascadeOnDelete();
            $table->index('book_id');
            $table->unsignedBigInteger('knowledge_id')->nullable();
            $table->foreign('knowledge_id','action_knowledge_id')->references('id')->on('knowledges')->cascadeOnDelete();
            $table->index('knowledge_id');
            $table->string('title',100);
            $table->string('book_page',100)->nullable();
            $table->string('timing',255);
            $table->string('place',255);
            $table->string('target_person',255)->nullable();
            $table->string('detail',255)->nullable();
            $table->string('reason',255);
            $table->string('method',255);
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
        Schema::table('actions', function(Blueprint $table){
            $table->dropForeign('action_book_id');
            $table->dropForeign('action_knowledge_id');
        });
        Schema::dropIfExists('actions');
    }
};
