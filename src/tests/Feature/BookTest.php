<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookTest extends TestCase
{
    use RefreshDatabase;

    // 一覧：自分の本だけが表示され、他人の本は出ない
    public function test_index_displays_only_own_books(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        Book::factory()->for($user)->create(['title' => 'My Own Book']);
        Book::factory()->for($other)->create(['title' => 'Someone Else Book']);

        $this->actingAs($user)
            ->get(route('books.index'))
            ->assertOk()
            ->assertSee('My Own Book')
            ->assertDontSee('Someone Else Book');
    }

    // 登録画面が表示できる
    public function test_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('books.create'))
            ->assertOk();
    }

    // 登録できる：DBに入り、user_idが自分になっている
    public function test_book_can_be_stored(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post(route('books.store'),[
            'title'     => 'Test Driven Development',
            'author'    => 'Kent Beck',
            'publisher' => 'Ohmsha',
            'status'    => 'unread',
            'genre'     => 'technology',
        ]);

        $response->assertRedirect(route('books.index'));

        $this->assertDatabaseHas('books',[
            'title'   => 'Test Driven Development',
            'user_id' => $user->id,  // ← 自分のIDで保存されている（mass assignment防御の確認）
        ]);
    }

    // バリデーション：タイトル未入力は弾かれ、登録されない
    public function test_book_requires_title(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->from(route('books.create'))
            ->post(route('books.store'),[
                // titleを送らない
                'author' => 'No Title',
                'status' => 'unread',
                'genre'  => 'novel',
            ]);

        $response->assertSessionHasErrors('title')
            ->assertRedirect(route('books.create'));

        $this->assertDatabaseCount('books', 0);

    }

    // 詳細：自分の本の詳細が見れる
    public function test_show_displays_own_book(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create(['title' => 'My Detail Book']);

        $this->actingAs($user)
            ->get(route('books.show', $book))
            ->assertOk()
            ->assertSee('My Detail Book');

    }

    // 編集画面が表示できる
    public function test_edit_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $this->actingAs($user)
            ->get(route('books.edit', $book))
            ->assertOk();
    }

    // 更新できる
    public function test_book_can_be_updated(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $response = $this->actingAs($user)->patch(route('books.update', $book),[
            'title'  => 'Updated Title',
            'author' => $book->author,
            'status' => 'reading',
            'genre'  => $book->genre,
        ]);

        $response->assertRedirect(route('books.show', $book));

        $this->assertDatabaseHas('books',[
            'id'     => $book->id,
            'title'  => 'Updated Title',
            'status' => 'reading',
        ]);
    }

    // 削除：論理削除される（行は残り deleted_at が入る）
    public function test_book_can_besoft_deleted(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $this->actingAs($user)
            ->delete(route('books.destroy', $book))
            ->assertRedirect(route('books.index'));

        $this->assertSoftDeleted($book);
    }

    // 未ログインは loginへリダイレクト
    public function test_guest_is_redirected_to_login() :void
    {
        $book = Book::factory()->create();

        $this->get(route('books.index'))->assertRedirect('/login');
        $this->get(route('books.show', $book))->assertRedirect('/login');
    }


}
