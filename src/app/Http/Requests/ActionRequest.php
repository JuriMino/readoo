<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ActionRequest extends FormRequest
{
    // 認可はPolicyで行うため、ここでは true
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
            'title'         => ['required', 'string', 'max:100'],
            'book_page'     => ['nullable', 'string', 'max:100'],
            'timing'        => ['required', 'string', 'max:255'],
            'place'         => ['required', 'string', 'max:255'],
            'target_person' => ['nullable', 'string', 'max:255'],
            'detail'        => ['nullable', 'string', 'max:255'],
            'reason'        => ['required', 'string', 'max:255'],
            'method'        => ['required', 'string', 'max:255'],
            'tag1'          => ['nullable', 'string', 'max:50'],
            'tag2'          => ['nullable', 'string', 'max:50'],
            'tag3'          => ['nullable', 'string', 'max:50'],
        ];

        // 新規登録（POST）の時だけ、参照元の本と（任意の）知識を検証
        if($this->isMethod('post')){
            // 本：必須かつ「自分の本」に固定
            $rules['book_id'] = [
                'required',
                Rule::exists('books', 'id')->where('user_id', $this->user()->id),
            ];
            // 知識：任意。送られてきたら「そのbook_idの本に関する知識」に固定
            $rules['knowledge_id'] = [
                'nullable',
                Rule::exists('knowledges', 'id')->where('book_id', $this->input('book_id')),
            ];
        }

        return $rules;
    }

    public function attributes(): array
    {
        return [
            'book_id' => '本（参照元）',
            'knowledge_id' => '関連知識',
            'title' => 'タイトル',
            'book_page' => '該当箇所',
            'timing' => 'WHEN(いつ)',
            'place' => 'WHERE(どこで)',
            'target_person' => 'WHO(誰に)',
            'detail' => 'WHAT(何を)',
            'reason' => 'WHY(なぜ)',
            'method' => 'HOW(どうやって)',
            'tag1' => 'タグ１',
            'tag2' => 'タグ２',
            'tag3' => 'タグ３',
        ];
    }
}
