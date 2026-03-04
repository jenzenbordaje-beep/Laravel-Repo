<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_welcome_when_no_users_exist()
    {
        // Fresh install: there are no users yet so the welcome screen is
        // rendered to allow initial setup or registration.
        $response = $this->get(route('home'));

        $response->assertOk();
        // ensure the response is rendering the welcome Inertia component
        $response->assertSee('"component":"Welcome"');
    }

    public function test_home_redirects_to_login_when_users_exist()
    {
        // once at least one user exists the route will no longer render the
        // welcome component, and guests are forwarded to the login page.
        // create a user so the count is > 0
        // (factory available via RefreshDatabase)
        // we only need one, details are irrelevant
        // the `canRegister` prop is not relevant here so we don't bother
        // with it.
        //
        // NOTE: we cannot import User class in test easily as this file
        // already has a namespace, so we just refer to it directly.
        //
        // (alternatively we could use User::factory() with a use statement)
        // I'll just add the import at the top of the file.)
        $user = \App\Models\User::factory()->create();

        $response = $this->get(route('home'));

        $response->assertRedirect(route('login'));
    }
}
