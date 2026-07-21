<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Knowledge;
use App\Models\Action;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ActionTest extends TestCase
{
    use RefreshDatabase;

    // 登録・更新で使う「バリデーションを通る最小の有効データ」
    private function validData(Book $book, array $overrides=[]): array
    {
        return array_merge([
            'book_id' => $book->id,
            'title'   => 'Morning Reading',
            'timing'  => '起床後', // WHEN 必須
            'place'   => '自宅のデスク', // WHERE 必須
            'reason'  => '習慣化のため', // WHY 必須
            'method'  => 'スモールステップで', // HOW 必須
        ], $overrides);
    }

    // 一覧：自分の本に紐づく行動だけが表示され、他人のは出ない
    public function test_index_displays_only_own_actions(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $ownBook = Book::factory()->for($user)->create();
        $otherBook = Book::factory()->for($other)->create();

        Action::factory()->for($ownBook)->create(['title' => 'My Own Action']);
        Action::factory()->for($otherBook)->create(['title' => 'Someone Else Action']);

        $this->actingAs($user)
            ->get(route('actions.index'))
            ->assertOk()
            ->assertSee('My Own Action')
            ->assertDontSee('Someone Else Action');
    }

    // 登録画面が表示できる
    public function test_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('actions.create'))
            ->assertOk();
    }

    // 登録できる：DBに入り、規定では本の詳細に戻る
    public function test_action_can_be_stored(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->post(route('actions.store'), $this->validData($book, ['title' => 'Deep Work Action']));

        $response->assertRedirect(route('books.show',$book));

        $this->assertDatabaseHas('actions',[
            'title'   => 'Deep Work Action',
            'book_id' => $book->id,
        ]);
    }

    // 登録：知識を紐付けて登録できる
    public function test_action_can_be_stored_with_knowledge() :void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $this->actingAs($user)
            ->post(route('actions.store'), $this->validData($book, ['knowledge_id' => $knowledge->id]));

        $this->assertDatabaseHas('actions',[
            'book_id'      => $book->id,
            'knowledge_id' => $knowledge->id,
        ]);
    }

    // 登録：from=index の時は行動一覧へ戻る
    public function test_store_redirects_to_index_when_from_is_index(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $this->actingAs($user)
            ->post(route('actions.store'), $this->validData($book, ['from' => 'index']))
            ->assertRedirect(route('actions.index'));
    }

    // 登録：from=knowledge の時は知識詳細へ戻る
    public function test_store_redirects_to_index_when_from_is_knowledge(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $this->actingAs($user)
            ->post(route('actions.store'), $this->validData($book, [
                'knowledge_id' => $knowledge->id,
                'from' => 'knowledge',
                ]))
            ->assertRedirect(route('knowledges.show', $knowledge));
    }

    // バリデーション：タイトル・5W1Hの必須項目が未入植なら弾かれ、登録されない
    public function test_action_requires_title_and_5w1h(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->from(route('actions.create'))
            ->post(route('actions.store'),[
                'book_id' => $book->id,
                // title・timing・place・reason・method を送らない
            ]);

        $response->assertSessionHasErrors(['title','timing','place','reason','method'])
            ->assertRedirect(route('actions.create'));

        $this->assertDatabaseCount('actions', 0);
    }

    // 詳細：自分の行動の詳細が見れる
    public function test_show_displays_own_action(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $action = Action::factory()->for($book)->create(['title' => 'My Detail Action']);

        $this->actingAs($user)
            ->get(route('actions.show',$action))
            ->assertOk()
            ->assertSee('My Detail Action');
    }

    // 編集画面が表示できる
    public function test_edit_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $action = Action::factory()->for($book)->create();

        $this->actingAs($user)
            ->get(route('actions.edit', $action))
            ->assertOk();
    }

    // 更新できる
    public function test_action_can_be_updated(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $action = Action::factory()->for($book)->create();

        $response = $this->actingAs($user)
            ->patch(route('actions.update',$action), $this->validData($book, ['title' => 'Updated Action']));

        $response->assertRedirect(route('actions.show',$action));

        $this->assertDatabaseHas('actions',[
            'id'      => $action->id,
            'title'   => 'Updated Action',
        ]);
    }

    // 更新：本の付け替えを試みても参照元は変わらない（book_id は update で除外）
    public function test_update_cannot_book(): void
    {
        $user = User::factory()->create();
        $book1 = Book::factory()->for($user)->create();
        $book2 = Book::factory()->for($user)->create();
        $action = Action::factory()->for($book1)->create();

        $this->actingAs($user)
            ->patch(route('actions.update', $action), $this->validData($book1,[
                'title'        => 'Keep Refs',
                'book_id'      => $book2->id,
            ]))
            ->assertRedirect(route('actions.show', $action));

        $this->assertDatabaseHas('actions',[
            'id'           => $action->id,
            'book_id'      => $book1->id,  // 元のまま
        ]);
    }

    // 更新：同じ本に属する知識なら関連知識を変更できる
    public function test_action_can_be_updated_with_knowledge(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book)->create();
        $action = Action::factory()->for($book)->create();

        $this->actingAs($user)
            ->patch(route('actions.update', $action), $this->validData($book, ['knowledge_id' => $knowledge->id,]))
            ->assertRedirect(route('actions.show', $action));

        $this->assertDatabaseHas('actions',[
            'id'           => $action->id,
            'knowledge_id' => $knowledge->id,
        ]);
    }

    // 更新：関連知識を空にすると紐付けが外れる
    public function test_action_can_be_updated_to_clear_knowledge(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book)->create();
        $action = Action::factory()->for($book)->create(['knowledge_id' => $knowledge->id]);

        $this->actingAs($user)
            ->patch(route('actions.update', $action), $this->validData($book, [
                'knowledge_id' => '',
            ]))
            ->assertRedirect(route('actions.show', $action));

        $this->assertDatabaseHas('actions', [
            'id'           => $action->id,
            'knowledge_id' => null,
        ]);
    }

    // 削除：論理削除され、親の本の詳細へ戻る
    public function test_action_can_be_soft_deletes() : void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $action = Action::factory()->for($book)->create();

        $this->actingAs($user)
            ->delete(route('actions.destroy', $action))
            ->assertRedirect(route('books.show',$book));

        $this->assertSoftDeleted($action);
    }

    // 未ログインは login へリダイレクト
    public function test_guest_is_redirected_to_login(): void
    {
        $book = Book::factory()->create();
        $action = Action::factory()->for($book)->create();

        $this->get(route('actions.index'))->assertRedirect('/login');
        $this->get(route('actions.show', $action))->assertRedirect('/login');
    }


}
