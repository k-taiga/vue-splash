<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        // factoryでテストユーザー作成
        $this->user = factory(User::class)->create();

    }


    public function test_user_login()

    {

        $response = $this->json('POST', route('login'), [
            'email' => $this->user->email,
            'password' => 'secret',
        ]);

        // 200のPOST: 操作の結果を表すリソースがメッセージ本文で送信されたという意味
        $response->assertStatus(200)
                 ->assertJson(['name' => $this->user->name]);

        $this->assertAuthenticatedAs($this->user);

    }
}
