<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['title','book_page','timing','place','target_person','detail','reason','method','tag1','tag2','tag3'])]
class Action extends Model
{
    use HasFactory, SoftDeletes;

    // 行動は本に紐づく（必須）
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    // 行動は知識に紐づくこともある（任意・nullable）
    public function knowledge(): BelongsTo
    {
        return $this->belongsTo(Knowledge::class);
    }


}
