<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterApiTest extends TestCase
{

    use RefreshDatabase;


    public function should_new_use_regist()
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
        $this->assetEquals($data['name'], $user->name);
        $response->assertStatus(200)
                 ->assertJson(['name'-> $user->name]);
        ;
    }
}
