<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Knowledge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KnowledgeAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    // 他人の知識の詳細は見れない（403）
    public function test_user_cannot_view_others_knowledge(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $this->actingAs($other)
            ->get(route('knowledges.show', $knowledge))
            ->assertForbidden();
    }

    // 他人の知識の編集画面は開けない（403）
    public function test_user_cannot_edit_others_knowledge(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $this->actingAs($other)
            ->get(route('knowledges.edit', $knowledge))
            ->assertForbidden();
    }

    // 他人の知識は更新できないし（403）し、内容も変わらない
    public function test_user_cannot_update_others_knowledge(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();
        $knowledge = Knowledge::factory()->for($book)->create(['title' => 'Original Title']);

        $this->actingAs($other)
            ->patch(route('knowledges.update', $knowledge),[
                'title'   => 'Hacked Titile',
                'content' => 'Hacked body',
            ])
            ->assertForbidden();

        $this->assertDatabaseHas('knowledges',[
            'id'    => $knowledge->id,
            'title' => 'Original Title', // 変わっていない
        ]);
    }

    // 他人の知識は削除できない（403）
    public function test_user_cannot_delete_others_knowledge(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $this->actingAs($other)
            ->delete(route('knowledges.destroy', $knowledge))
            ->assertForbidden();

        $this->assertNotSoftDeleted($knowledge);
    }

    // 他人の本に知識を作成できない（バリデーションで弾かれ、登録されない）
    public function test_user_cannot_create_knowledge_on_others_book(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $otherBook = Book::factory()->for($owner)->create();

        $response = $this->actingAs($other)
            ->from(route('knowledges.create'))
            ->post(route('knowledges.store'),[
                'book_id' => $otherBook->id, // 他人の本を指定
                'title'   => 'Sneaky Knowledge',
                'content' => 'body,'
            ]);
        // Policy(403)でなく、book_idのバリデーションエラーになる
        $response->assertSessionHasErrors('book_id')
            ->assertRedirect(route('knowledges.create'));

        $this->assertDatabaseCount('knowledges', 0);
    }

}
