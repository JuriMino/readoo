<?php

namespace Tests\Feature;

use App\Models\Action;
use App\Models\User;
use App\Models\Book;
use App\Models\Knowledge;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActionAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    // 更新で使う「バリデーションを通る最小の有効データ」
    private function validData(Book $book, array $overrides =[]): array
    {
        return array_merge([
            'book_id' => $book->id,
            'title'   => 'Some Action',
            'timing'  => '起床後',
            'place'   => '自宅のデスク',
            'reason'  => '習慣化のため',
            'method'  => 'スモールステップで',
        ], $overrides);
    }

    // 他人の行動の詳細は見れない（403）
    public function test_user_cannot_view_others_action(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();
        $action = Action::factory()->for($book)->create();

        $this->actingAs($other)
            ->get(route('actions.show', $action))
            ->assertForbidden();
    }

    // 他人の行動の編集画面は開けない（403）
    public function test_user_cannot_edit_others_action(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();
        $action = Action::factory()->for($book)->create();

        $this->actingAs($other)
            ->get(route('actions.edit', $action))
            ->assertForbidden();
    }

    // 他人の行動は更新できない（403）し、内容も変わらない
    public function test_user_cannot_update_others_action(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();
        $action = Action::factory()->for($book)->create(['title' => 'Original Action']);

        // バリデーションは通る有効データ → 認可（Policy）で403になることを確かめる
        $this->actingAs($other)
            ->patch(route('actions.update', $action), $this->validData($book,['title' => 'Hacked Action']))
            ->assertForbidden();

        $this->assertDatabaseHas('actions',[
            'id'    => $action->id,
            'title' => 'Original Action', // 変わってない
        ]);
    }

    // 他人の行動は削除できない（403）
    public function test_user_cannot_delete_others_action(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();
        $action = Action::factory()->for($book)->create();

    $this->actingAs($other)
        ->delete(route('actions.destroy', $action))
        ->assertForbidden();

    $this->assertNotSoftDeleted($action);

    }

    // 他人の本に行動を作成できない（book_idのバリデーションで弾かれる）
    public function test_user_cannot_action_on_others_book(): void
    {
        $owner = User::factory()->create();
        $other = User::factory()->create();
        $book = Book::factory()->for($owner)->create();

        $this->actingAs($other)
            ->from(route('actions.create'))
            ->post(route('actions.store'), $this->validData($book))
            ->assertSessionHasErrors('book_id')
            ->assertRedirect(route('actions.create'));

        $this->assertDatabaseCount('actions', 0);
    }

    // 整合性：別の本に属する知識は紐付けられない（knowledge_id のバリデーションで弾かれる）
    public function test_user_cannot_attach_knowledge_of_another_book(): void
    {
        $user = User::factory()->create();
        $book1 = Book::factory()->for($user)->create();
        $book2 = Book::factory()->for($user)->create();
        $knowledgeOnBook2 = Knowledge::factory()->for($book2)->create();

        // book１に対する行動なのに、book2の知識を紐づけようとする
        $this->actingAs($user)
            ->from(route('actions.create'))
            ->post(route('actions.store'), $this->validData($book1,[
                'knowledge_id' => $knowledgeOnBook2->id,
            ]))
            ->assertSessionHasErrors('knowledge_id')
            ->assertRedirect(route('actions.create'));

        $this->assertDatabaseCount('actions', 0);
    }

    // 整合性：更新時も別の本に属する知識は紐づけられない（knowledge_idのバリデーションで弾かれる）
    public function test_user_cannot_attach_knowledge_of_another_book_on_update(): void
    {
        $user = User::factory()->create();
        $book1 = Book::factory()->for($user)->create();
        $book2 = Book::factory()->for($user)->create();
        $knowledgeOnBook2 = Knowledge::factory()->for($book2)->create();
        $action = Action::factory()->for($book1)->create();

        $this->actingAs($user)
            ->from(route('actions.edit', $action))
            ->patch(route('actions.update', $action), $this->validData($book1, [
                'knowledge_id' => $knowledgeOnBook2->id,
            ]))
            ->assertSessionHasErrors('knowledge_id')
            ->assertRedirect(route('actions.edit', $action));

        $this->assertDatabaseHas('actions', [
            'id'           => $action->id,
            'knowledge_id' => null, // 変わらない
        ]);
    }

}
