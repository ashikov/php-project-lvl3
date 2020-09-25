<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Http;

class DomainCheckControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testStore()
    {
        $this->id = DB::table('domains')->insertGetId([
            'name' => 'http://google.com',
            "created_at" =>  \Carbon\Carbon::now(),
            "updated_at" => \Carbon\Carbon::now()
        ]);

        Http::fake([
            'google.com' => Http::response(200)
        ]);

        $response = $this->post(route('domains.checks.store', $this->id));
        $response->assertSessionHasNoErrors();
        $response->assertRedirect();
        $this->assertDatabaseHas('domain_checks', [
            'domain_id' => $this->id,
            'status_code' => 200
        ]);
    }
}