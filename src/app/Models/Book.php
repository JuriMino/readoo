<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['title','author','publisher','status','genre','started_at','finished_at','summary','memo'])]

class Book extends Model
{
    use HasFactory, SoftDeletes;

    // 読書状態：値 => 表示ラベル
    public const STATUSES = [
        'unread'   => '未読',
        'reading'  => '読書中',
        'finished' => '読了',
    ];

    // ジャンル：値 => 表示ラベル
    public const GENRES = [
        'business'      => 'ビジネス',
        'self_help'     => '自己啓発',
        'technology'    => '技術',
        'novel'         => '小説',
        'liberal_arts'  => '教養',
        'history'       => '歴史',
        'science'       => '科学',
        'health_beauty' => '健康・美容',
        'economics'     => '経済',
        'philosophy'    => '哲学',
        'psychology'    => '心理学',
        'culture'       => '文化',
        'other'         => 'その他',
    ];

    // 読書開始日と読了日を日付操作できるようにcastしておく
    protected function casts(): array
    {
        return[
            'started_at' => 'date',
            'finished_at' => 'date',
        ];
    }

    // 本はユーザーに紐づく（ログインユーザー１名）
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // 本は複数の知識情報を持つ
    public function knowledges(): HasMany
    {
        return $this->hasMany(Knowledge::class);
    }

    // 本は複数の行動情報を持つ
    public function actions(): HasMany
    {
        return $this->hasMany(Action::class);
    }

}
