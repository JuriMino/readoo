<?php

namespace App\Policies;

use App\Models\Knowledge;
use App\Models\User;
use App\Models\Book;

class KnowledgePolicy
{
    // 一覧：自分の知識だけをコントローラ側で絞り込む前提で許可
    public function viewAny(User $user): bool
    {
        return true;
    }

    // 詳細：知識が紐づく「本の持ち主と一致するか」（間接判定）
    public function view(User $user, Knowledge $knowledge): bool
    {
        return $user->id === $knowledge->book?->user_id;
    }

    // 作成：その本の持ち主だけが知識を追加できる（本を受け取って判定）
    public function create(User $user, Book $book): bool
    {
        return $user->id === $book->user_id;
    }

    // 更新：本の持ち主と一致するか
    public function update(User $user, Knowledge $knowledge): bool
    {
        return $user->id === $knowledge->book?->user_id;
    }

    // 削除：本の持ち主と一致するか
    public function delete(User $user, Knowledge $knowledge): bool
    {
        return $user->id === $knowledge->book?->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Knowledge $knowledge): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Knowledge $knowledge): bool
    {
        return false;
    }
}
