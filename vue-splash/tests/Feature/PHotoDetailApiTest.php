<?php

namespace Tests\Feature;

use App\Photo;
use App\Comment;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;

class PHotoDetailApiTest extends TestCase
{

    use RefreshDatabase;

    public function test_return_correct_json()
    {
        Storage::fake('s3');

        // Photoをcreateする度にCommentを3つ作成する
        factory(Photo::class)->create()->each(function ($photo) {
            $photo->comments()->saveMany(factory(Comment::class, 3)->make());
        });
        $photo = Photo::first();

        // var_dump($photo);
        // exit;
        $response = $this->json('GET', route('photo.show', [
            'id' => $photo->id,
        ]));


        $response->assertStatus(200)
            // JSONのフォーマットチェック
            ->assertJsonFragment([
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name,
                ],
                'comments' => $photo->comments
                    ->sortByDesc('id')
                    ->map(function ($comment) {
                        return [
                            'author' => [
                                'name' => $comment->author->name,
                            ],
                            'content' => $comment->content,
                        ];
                    })
                    ->all(),
            ]);
    }
}
