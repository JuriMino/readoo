<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['title','book_page','content','tag1','tag2','tag3'])]

class Knowledge extends Model
{
    use HasFactory, SoftDeletes;

    // Knowledgeは不可算名詞なので複数形化されないため、明示しておく
    protected $table = 'knowledges';

    // 知識は本に紐づく
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

}
