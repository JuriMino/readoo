<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * 認可：Controller側のPolicy（$this->authorize）で行うので true
     */
    public function authorize(): bool
    {
        return true;
    }

   /**
     * バリデーションルール（store / update 共通）
     */
    public function rules(): array
    {
        return [
            'title'       => ['required', 'string', 'max:255'],
            'author'      => ['required', 'string', 'max:100'],
            'publisher'   => ['nullable', 'string', 'max:100'],
            'status'      => ['required', 'in:unread,reading,finished'],
            'genre'       => ['required', 'in:business,self_help,technology,novel,liberal_arts,history,science,health_beauty,economics,philosophy,psychology,culture,other'],
            'started_at'  => ['nullable', 'date'],
            'finished_at' => ['nullable', 'date', 'after_or_equal:started_at'],
            'summary'     => ['nullable', 'string'],
            'memo'        => ['nullable', 'string'],
        ];
    }

    /**
     * エラーメッセージ用の項目名（任意・日本語表示が綺麗になる）
     */

    public function attributes(): array
    {
        return[
            'title'       => 'タイトル',
            'author'      => '著者',
            'publisher'   => '出版社',
            'status'      => 'ステータス',
            'genre'       => 'ジャンル',
            'started_at'  => '読書開始日',
            'finished_at' => '読了日',
            'summary'     => '要約',
            'memo'        => 'メモ',
        ];
    }

}
