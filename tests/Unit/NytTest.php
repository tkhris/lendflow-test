<?php
 
namespace Tests\Feature;
 
use Tests\TestCase;
use Illuminate\Support\Facades\Http;
 
class NytTest extends TestCase
{
    public function test_base_url()
    {
        Http::fake([
            'https://api.nytimes.com/svc/books/v3/lists/best-sellers/*' => Http::response([
                'status' => 'OK'
            ], 200),
        ]);

        // Base test
        $response = $this->getJson('/api/1/nyt/best-sellers');
        $response->assertStatus(200);
    }

    public function test_author()
    {
        Http::fake([
            'https://api.nytimes.com/svc/books/v3/lists/best-sellers/*' => Http::response([
                'status' => 'OK'
            ], 200),
        ]);

        $response = $this->getJson('/api/1/nyt/best-sellers?author=Test');
        $response->assertStatus(200);
    }

    public function test_isbn()
    {
        Http::fake([
            'https://api.nytimes.com/svc/books/v3/lists/best-sellers/*' => Http::response([
                'status' => 'OK'
            ], 200),
        ]);
        $response = $this->getJson('/api/1/nyt/best-sellers?isbn=1234567890');
        $response->assertStatus(200);

        $response = $this->getJson('/api/1/nyt/best-sellers?isbn=1234567890;1234567890123');
        $response->assertStatus(200);

        $response = $this->getJson('/api/1/nyt/best-sellers?isbn=123456789;1234567890123');
        $response->assertStatus(401);

        $response = $this->getJson('/api/1/nyt/best-sellers?isbn=1234567890;12345678901234');
        $response->assertStatus(401);
    }

    public function test_title()
    {
        Http::fake([
            'https://api.nytimes.com/svc/books/v3/lists/best-sellers/*' => Http::response([
                'status' => 'OK'
            ], 200),
        ]);

        $response = $this->getJson('/api/1/nyt/best-sellers?title=Test');
        $response->assertStatus(200);
    }

    public function test_offset()
    {
        Http::fake([
            'https://api.nytimes.com/svc/books/v3/lists/best-sellers/*' => Http::response([
                'status' => 'OK'
            ], 200),
        ]);

        $response = $this->getJson('/api/1/nyt/best-sellers?offset=20');
        $response->assertStatus(200);

        $response = $this->getJson('/api/1/nyt/best-sellers?offset=0');
        $response->assertStatus(200);

        $response = $this->getJson('/api/1/nyt/best-sellers?offset=40');
        $response->assertStatus(200);

        $response = $this->getJson('/api/1/nyt/best-sellers?offset=-20');
        $response->assertStatus(401);

        $response = $this->getJson('/api/1/nyt/best-sellers?offset=2');
        $response->assertStatus(401);
    }
}