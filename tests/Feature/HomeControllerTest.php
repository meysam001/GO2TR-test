<?php


namespace Tests\Feature;

use App\Models\Photo;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HomeControllerTest extends TestCase
{
    private $prefix = 'api/admin/photo';

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function testIndex()
    {
        $response = $this->get('/api/content');
        $response->assertStatus(200);
    }
}
