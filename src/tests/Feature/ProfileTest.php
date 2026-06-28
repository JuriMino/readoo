<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
    }

    public function test_profile_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'username' => 'Test User',
                'email' => 'test@example.com',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('Test User', $user->username);
        $this->assertSame('test@example.com', $user->email);
        $this->assertNull($user->email_verified_at);
    }

    public function test_user_can_delete_their_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->delete('/profile', [
                'password' => 'password',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/');

        $this->assertGuest();
        $this->assertSoftDeleted($user);
    }

    public function test_correct_password_must_be_provided_to_delete_account(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->from('/profile')
            ->delete('/profile', [
                'password' => 'wrong-password',
            ]);

        $response
            ->assertSessionHasErrorsIn('userDeletion', 'password')
            ->assertRedirect('/profile');

        $this->assertNotNull($user->fresh());
    }

    // 未ログインで /profile にアクセスすると login へリダイレクトされる
    public function test_guest_is_redirected_to_login(): void
    {
        $response = $this->get('/profile');

        $response->assertRedirect('/login');
    }

    // 退会すると、紐づく本は物理削除される（論理削除済みの本も含めて消える）
    public function test_account_deletion_force_deletes_books(): void
    {
        $user        = User::factory()->create();
        $activeBook  = Book::factory()->for($user)->create();
        $trashedBook = Book::factory()->for($user)->create();
        $trashedBook->delete(); //事前に論理削除しておく

        $this->actingAs($user)
            ->delete('/profile', ['password' => 'password'])
            ->assertRedirect('/');

        $this->assertSoftDeleted($user);    // ユーザーは論理削除
        $this->assertDatabaseMissing('books',['id' => $activeBook->id]);    // 本は物理削除
        $this->assertDatabaseMissing('books',['id' => $trashedBook->id]);   // 論理削除済みも物理削除
    }

    // 退会後、同じメールアドレスで再登録できる（メール退避の確認）
    public function test_email_can_be_reused_after_account_deletion(): void
    {
        $user = User::factory()->create(['email' => 'reuse@example.com']);

        $this->actingAs($user)
            ->delete('/profile',['password' => 'password']);

        // 退会者のメールアドレスは退避され、元アドレスを使う「現役ユーザー」はいない
        $this->assertDatabaseMissing('users',[
            'email'      => 'reuse@example.com',
            'deleted_at' => null,
        ]);

        // 同じメールアドレスで新規登録できる
        $this->post('/register',[
            'username'              => 'New User',
            'email'                 => 'reuse@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasNoErrors();

        $this->assertDatabaseHas('users',[
            'email'      => 'reuse@example.com',
            'deleted_at' => null,
        ]);
    }

}
