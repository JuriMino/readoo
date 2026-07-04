<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class KnowledgeRequest extends FormRequest
{
    // 認可はPolicyで行うため、ここでは true を返す
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'title'     => ['required', 'string', 'max:100'],
            'book_page' => ['nullable', 'string', 'max:100'],
            'content'   => ['required', 'string'],
            'tag1'      => ['nullable', 'string', 'max:50'],
            'tag2'      => ['nullable', 'string', 'max:50'],
            'tag3'      => ['nullable', 'string', 'max:50'],
        ];

        // 新規登録（POST)の時だけ、紐づける本を必須かつ「自分の本」に限定
        if($this->isMethod('post')){
            $rules['book_id'] = [
                'required',
                Rule::exists('books', 'id')->where('user_id', $this->user()->id),
                ];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'book_id'   => '本（参照元）',
            'title'     => 'タイトル',
            'book_page' => '該当箇所',
            'content'   => '内容',
            'tag1'      => 'タグ１',
            'tag2'      => 'タグ２',
            'tag3'      => 'タグ３',
        ];
    }
}
