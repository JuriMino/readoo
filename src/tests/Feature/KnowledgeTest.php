<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Knowledge;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Middleware\ValidatePathEncoding;
use PhpParser\Node\Expr\FuncCall;
use Tests\TestCase;

class KnowledgeTest extends TestCase
{
    use RefreshDatabase;

    // 一覧：自分の本に紐づく知識だけが表示され、他人のは出ない
    public function test_index_display_only_own_knowledges(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $ownBook = Book::factory()->for($user)->create();
        $otherBook = Book::factory()->for($other)->create();

        Knowledge::factory()->for($ownBook)->create(['title' => 'My Own Knowledge']);
        Knowledge::factory()->for($otherBook)->create(['title' => 'Someone Else Knowledge']);

        $this->actingAs($user)
            ->get(route('knowledges.index'))
            ->assertOk()
            ->assertSee('My Own Knowledge')
            ->assertDontSee('Someone Else Knowledge');
    }

    // 登録画面が表示できる
    public function test_create_screen_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('knowledges.create'))
            ->assertOk();
    }

    // 登録できる：DBに入り、既定では本の詳細へ戻る
    public function test_knowledge_can_be_stored(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $response = $this->actingAs($user)->post(route('knowledges.store'),[
            'book_id'   => $book->id,
            'title'     => 'Deep Work',
            'content'   => '集中して価値を生む',
            'book_page' => 'P.42',
            'tag1'      => '集中',
        ]);

        $response->assertRedirect(route('books.show',$book));

        $this->assertDatabaseHas('knowledges',[
            'title'   => 'Deep Work',
            'book_id' => $book->id,
        ]);
    }

    // 登録：from=index の時は知識一覧へ戻る
    public function test_store_redirects_to_index_when_from_is_index(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $this->actingAs($user)->post(route('knowledges.store'),[
            'book_id' => $book->id,
            'title'   => 'From Index',
            'content' => 'body',
            'from'    => 'index',
        ])->assertRedirect(route('knowledges.index'));
    }

    // バリデーション：タイトル未入力は弾かれ、登録されない
    public function test_knowledge_requires_title(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();

        $response = $this->actingAs($user)
            ->from(route('knowledges.create'))
            ->post(route('knowledges.store'),[
                'book_id' => $book->id,
                // タイトルを送らない
                'content' => 'body',
            ]);

        $response->assertSessionHasErrors('title')
            ->assertRedirect(route('knowledges.create'));

        $this->assertDatabaseCount('knowledges', 0);
    }

    // 詳細：自分の知識の詳細が見れる
    public function test_show_displays_own_knowledge(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book)->create(['title' => 'My Detail Knowledge']);

        $this->actingAs($user)
            ->get(route('knowledges.show', $knowledge))
            ->assertOk()
            ->assertSee('My Detail Knowledge');
    }

    // 編集画面が表示できる
    public function test_edit_screencan_be_rendered(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $this->actingAs($user)
            ->get(route('knowledges.edit', $knowledge))
            ->assertOk();
    }

    // 更新できる
    public function test_knowledge_can_be_updated(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $response = $this->actingAs($user)->patch(route('knowledges.update', $knowledge),[
            'title'   => 'Updated Knowledge',
            'content' => 'Updated body',
        ]);

        $response->assertRedirect(route('knowledges.show', $knowledge));

        $this->assertDatabaseHas('knowledges',[
            'id'    => $knowledge->id,
            'title' => 'Updated Knowledge',
        ]);
    }

    // 更新：別の本のIDを送っても参照元は変わらない（book_id は update で除外）
    public function test_update_cannot_change_book_id(): void
    {
        $user = User::factory()->create();
        $book1 = Book::factory()->for($user)->create();
        $book2 = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book1)->create();

        $this->actingAs($user)->patch(route('knowledges.update', $knowledge),[
            'title'   => 'Keep Book',
            'content' => 'body',
            'book_id' => $book2->id, // book_idの付け替えを試みる
        ])->assertRedirect(route('knowledges.show',$knowledge));

        $this->assertDatabaseHas('knowledges',[
            'id'      => $knowledge->id,
            'book_id' => $book1->id, // 元のまま
        ]);
    }

    // 削除：論理削除され、親の本の詳細へ戻る
    public function test_knowledge_can_be_soft_deleted(): void
    {
        $user = User::factory()->create();
        $book = Book::factory()->for($user)->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $this->actingAs($user)
            ->delete(route('knowledges.destroy',$knowledge))
            ->assertRedirect(route('books.show',$book));

        $this->assertSoftDeleted($knowledge);
    }

    // 未ログインは login へリダイレクト
    public function test_guest_is_redirected_to_login(): void
    {
        $book = Book::factory()->create();
        $knowledge = Knowledge::factory()->for($book)->create();

        $this->get(route('knowledges.index'))->assertRedirect('/login');
        $this->get(route('knowledges.show',$knowledge))->assertRedirect('/login');
    }

}
