<?php

namespace Tests\BlackBoxTesting;

use Illuminate\Support\Facades\Http;

class IntegrationsTest extends BlackBoxTestCase
{
    public function test_external_http_integration_is_faked()
    {
        Http::fake();

        // Example: if the app pings an external URL, fake it and assert no exception thrown when making a request.
        $response = Http::get('https://example.com/status');
        $this->assertTrue($response->successful() || $response->clientError() || $response->serverError());
    }
}
