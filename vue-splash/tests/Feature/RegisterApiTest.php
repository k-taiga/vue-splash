<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterApiTest extends TestCase
{

    use RefreshDatabase;


    public function test_new_user_regist()
    {

        $data = [
            'name' => 'vuesplash user',
            'email' => 'dummy@email.com',
            'password' => 'test1234',
            'password_confirmation' => 'test1234',
        ];

        // SPAのため画面遷移はせずjsonで$dataを送って、それがdbに登録されているかを見る
        $response = $this->json('POST', route('register'), $data);
        $user = User::first();
        $this->assertEquals($data['name'], $user->name);
        // 201: リクエストは成功し、その結果新たなリソースが作成されたという意味
        $response->assertStatus(201)
                 ->assertJson(['name' => $user->name]);
    }
}
