<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Knowledge;
use App\Models\Action;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        $user = $request->user();

        return view('profile.edit', [
            'user' => $user,
            'bookCount' => $user->books()->count(),
            'knowledgeCount' => Knowledge::whereHas('book', fn($q) => $q->where('user_id', $user->id))->count(),
            'actionCount' => Action::whereHas('book', fn($q) => $q->where('user_id', $user->id))->count(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        DB::transaction(function () use ($user){
            // 本を物理削除 → 知識・行動はDBのカスケード制約で自動的に物理削除される
            $user->books()->withTrashed()->forceDelete();

            // メールアドレスを退避して元アドレスを解放
            $user->email = $user->email.'_deleted_'.$user->id;
            $user->save();

            // ユーザー本体は論理削除（記録を残す）
            $user->delete();

        });


        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
