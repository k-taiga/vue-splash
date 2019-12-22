<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AddCommentApiTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function test_add_comment()
    {
        factory(Photo::class)->create();
        $photo = Photo::first();

        $content = 'sample content';

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.comment', [
                'photo' => $photo->id,
            // array('content' => $content)と同じ意味
            ]), compact('content'));


        $response->assertStatus(201)
            ->assertJsonFragment([
                "author" => [
                    "name" => $this->user->name,
                ],
                "content" => $content,
            ]);

        $comments = $photo->comments()->get();

        // DBにコメントが一件登録されていること
        $this->assertEquals(1, $comments->count());
        // 内容がAPIでリクエストしたものであること
        $this->assertEquals($content, $comments[0]->content);


    }
}
