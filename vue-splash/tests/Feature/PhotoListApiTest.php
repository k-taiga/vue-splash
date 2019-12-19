<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PhotoListApiTest extends TestCase
{
    use RefreshDatabase;

    // 正しい構造のJSONかチェック
    public function test_correct_json ()
    {
        // 写真データを5つ作成
        factory(Photo::class, 5)->create();

        $response = $this->json('GET', route('photo.index'));

        $photos = Photo::with(['owner'])->orderBy('created_at', 'desc')->get();
    }

}
