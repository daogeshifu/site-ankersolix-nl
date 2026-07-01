<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPageTest extends TestCase
{
    public function test_privacy_page_returns_a_successful_response(): void
    {
        $response = $this->get(route('policy'));

        $response->assertOk();
        $response->assertSee('Privacybeleid', false);
        $response->assertSee('Privacycontact', false);
    }

    public function test_legacy_policy_path_redirects_to_privacy(): void
    {
        $response = $this->get('/policy');

        $response->assertRedirect('/privacy');
    }
}
