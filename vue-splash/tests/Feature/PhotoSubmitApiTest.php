<?php

namespace Tests\Feature;

// API
use App\Photo;
use App\User;
// DB
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
// Upload&Storage
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

use Tests\TestCase;

class PhotoSubmitApiTest extends TestCase
{

    use RefreshDatabase;

    public function setUp()
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    public function test_upload_file()
    {
        // S3ではなくテスト用のストレージを使用
        // ->storage/framework/testing
        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                // ダミーファイルを作成し送信
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        // レスポンスがCREATED(201)であること
        $response->assertStatus(201);

        // 写真のIDが12桁のランダムな文字列であることを正規表現で判断
        $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $photo->id);

        // 実際にDBに保存されたファイル名のファイルがストレージに保存されていること
        Storage::cloud()->assertExists($photo->filename);
    }

    public function test_not_save_for_db_error()
    {
        // DBエラーを起こす
        Schema::drop('photos');

        Storage::fake('s3');

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        // レスポンスがINTERNAL SERVER ERRORであること
        $response->assertStatus(500);

        // ストレージにファイルが保存されていないこと
        $this->assertEquals(0, count(Storage::colud()->files()));

    }

    public function test_not_save_for_upload_error()
    {
        // ストレージをモックして保存時にエラーを起こす
        Storage::shouldReceive('cloud')
            ->once()
            ->andReturnNull();

        $response = $this->actingAs($this->user)
            ->json('POST', route('photo.create'), [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]);

        // レスポンスがINTERNAL SERVER ERRORであること
        $response->assertStatus(500);

        // データベースに何も挿入されていないこと
        $this->assertEmpty(Photo::all());

    }

}
