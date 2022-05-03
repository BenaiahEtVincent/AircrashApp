<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_the_application_returns_a_successful_response()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_the_application_return_list_incidents()
    {
        $response = $this->get('/api/incidents');

        $response->assertStatus(200);
    }

    public function test_the_application_return_list_incidents_by_date()
    {
        $response = $this->get('/api/incidents/2020/01/01');

        $response->assertStatus(200);
    }

    public function test_the_application_return_list_incidents_by_date_with_invalid_date()
    {
        $response = $this->get('/api/incidents/2020/13/01');

        $response->assertStatus(404);
    }

    public function test_the_application_return_list_incidents_by_date_with_invalid_date_month()
    {
        $response = $this->get('/api/incidents/2020/00/01');

        $response->assertStatus(404);
    }

    public function test_the_application_return_list_incidents_by_date_with_invalid_date_day()
    {
        $response = $this->get('/api/incidents/2020/01/00');

        $response->assertStatus(404);
    }

    public function test_the_application_return_list_incidents_by_date_with_invalid_date_day_and_month()
    {
        $response = $this->get('/api/incidents/2020/00/00');

        $response->assertStatus(404);
    }

    public function test_the_application_return_list_incidents_by_date_with_invalid_date_day_and_month_and_year()
    {
        $response = $this->get('/api/incidents/2020/00/00/00');

        $response->assertStatus(404);
    }

    public function test_the_application_return_list_incidents_with_search()
    {
        $response = $this->get('/api/search/test');
        
        $response->assertStatus(200);
    }
}
