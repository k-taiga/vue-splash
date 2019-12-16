<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserApiTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        // テストユーザー作成
        $this->user = factory(User::class)->create();
    }

    // ログイン中のユーザーの値を返すかのテスト
    public function test_login_user_data_return()
    {
        $response = $this->actingAs($this->user)->json('GET', route('user'));

        $response
            ->assertStatus(200)
            ->assertJson([
                'name' => $this->user->name,
            ]);
    }

    // ログインしていない場合は空文字を返却する
    public function test_login_user_data_not_return()
    {
        $response = $this->json('GET' , route('user'));

        $response->assertStatus(200);
        // 返ってきた値が空かどうかチェック
        $this->assertEquals("", $response->content());
    }
}
