<?php

namespace Tests\Feature;

use App\Photo;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Schema;
// use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoSubmitTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create();
    }

    /**
     * @test
     */
    public function should_can_upload_file()
    {
        // S3ではなくテスト用のストレージを使用する
        // → storage/framework/testing
        // Storage::fake('s3');

        $response = $this->actingAs($this->user)->json(
            'POST',
            route('photo.create'),
            [
                'photo' => UploadFile::fake()->image('photo.jpg'),
            ]
        );

        // レスポンスが201(CREATED)であること
        $response->assertStatus(201);

        $photo = Photo::first();

        // 写真のIDが12桁のランダムな文字列であること
        $this->assertRegExp('/^[0-9a-zA-Z-_]{12}$/', $photo->id);

        // DBに挿入されたファイル名のファイルがストレージに保存されていること
        // Storage::cloud()->assertExists($photo->filename);
    }

    /**
     * @test
     */
    public function should_not_save_in_case_of_database_error()
    {
        Schema::drop('photos');

        // Storage::fake('s3');

        $response = $this->actingAs($this->user)->json(
            'POST',
            route('photo.create'),
            [
                'photo' => UploadedFile::fake()->image('photo.jpg'),
            ]
        );

        // レスポンスが500(INTERNAL SERVER ERROR)であること
        $response->assertStatus(500);

        // ストレージにファイルが保存されていないこと
        // $this->assertEquals(0, count(Storage::cloud()->files()));
    }

    /**
     * @test
     */
    public function should_not_save_to_DB_when_save_error_occurs()
    {
        // ストレージをモックして保存時にエラーを起こさせる
        // Storage::shouldReceive('cloud')
        //     ->once()
        //     ->andReturnNull();

        // $response = $this->actingAs($this->user)
        //     ->json('POST', route('photo.create'), [
        //         'photo' => UploadedFile::face()->image('photo.jpg'),
        //     ]);

        // レスポンスが500(INTERNAL SERVER ERROR)であること
        // $response->assertStatus(500);

        // データベースに何も挿入されていないこと
        // $this->assertEmpty(Photo::all());
    }
}
