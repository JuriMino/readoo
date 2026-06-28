<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BookAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    // 他人の本の詳細は見れない（403）
    public function test_user_cannot_view_others_book(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get(route('books.show', $book))
            ->assertForbidden();
    }

    // 他人の本の編集画面は開けない（403）
    public function test_user_cannot_eidt_others_book(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();

        $this->actingAs($other)
            ->get(route('books.edit', $book))
            ->assertForbidden();
    }

    // 他人の本は更新できない（403）し、内容も変わらない
    public function test_user_cannot_update_others_book(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create(['title' => 'Original Title']);

        $this->actingAs($other)
            ->patch(route('books.update', $book),[
                'title'  => 'Hacked Title',
                'author' => $book->author,
                'status' => 'reading',
                'genre'  => $book->genre,
            ])
            ->assertForbidden();

        // 中身が変わっていないことまで確認
        $this->assertDatabaseHas('books',[
            'id' => $book->id,
            'title' => 'Original Title',
        ]);
    }

    // 他人の本は削除できない（403）し、削除もされていない
    public function test_user_cannot_delete_others_book(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();

        $this->actingAs($other)
            ->delete(route('books.destroy', $book))
            ->assertForbidden();

        $this->assertNotSoftDeleted($book);
    }
}
