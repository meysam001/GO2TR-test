<?php


namespace Tests\Feature;

use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PhotoControllerTest extends TestCase
{
    private $prefix = 'api/admin/photo';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIndex()
    {
        $response = $this
            ->withHeader('Authorization', env('API_TOKEN'))
        ->get('/api/admin/photo');
        $response->assertStatus(200);
    }

    public function testStore()
    {
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this
            ->withHeader('Authorization', env('API_TOKEN'))
        ->post('/api/admin/photo', [
            'description' => 'only for test',
            'file' => $file,
        ]);
        $response->assertStatus(201);
    }

    public function testShow()
    {
        $photo = Photo::take(1)->first();
        $response = $this
            ->withHeader('Authorization', env('API_TOKEN'))
        ->get('/api/admin/photo/'.$photo->id);
        $response->assertStatus(200);
    }

    public function testUpdate()
    {
        $photo = Photo::take(1)->first();
        Storage::fake('avatars');
        $file = UploadedFile::fake()->image('avatar.jpg');

        $response = $this
            ->withHeader('Authorization', env('API_TOKEN'))
            ->put('/api/admin/photo/'.$photo->id, [
                'description' => 'updated title',
                'file' => $file,
            ]);
        $response->assertStatus(204);
    }

    public function testDestroy()
    {
        $photo = Photo::take(1)->first();

        $response = $this
            ->withHeader('Authorization', env('API_TOKEN'))
            ->delete('/api/admin/photo/'.$photo->id);
        $response->assertStatus(200);
    }
}
