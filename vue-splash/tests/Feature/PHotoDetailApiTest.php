<?php

namespace Tests\Feature;

use App\Photo;
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
        factory(Photo::class)->create();
        $photo = Photo::first();

        $response = $this->json('GET', route('photo.show', [
            'id' => $photo->id,
        ]));

        // var_dump($photo);
        // exit;

        $response->assertStatus(200)
            // JSONのフォーマットチェック
            ->assertJsonFragment([
                'id' => $photo->id,
                'url' => $photo->url,
                'owner' => [
                    'name' => $photo->owner->name,
                ],
            ]);
    }
}
