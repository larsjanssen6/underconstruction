<?php

namespace LarsJanssen\UnderConstruction\Test\Integration;

use LarsJanssen\UnderConstruction\Test\TestCase;

class UnderConstructionModeTest extends TestCase
{
    /** @test */
    public function it_redirects_users_who_have_not_been_granted_access_to_a_under_construction_page()
    {
        $this->assertCannotVisitProductionSite();
    }

    /** @test */
    public function it_only_redirects_users_if_under_construction_is_enabled()
    {
        $this->app['config']->set('under-construction.enabled', false);
        $this->assertCanVisitProductionSite();
    }

    /** @test */
    public function it_sets_session_correctly_after_successful_login()
    {
        $this->app['config']->set('under-construction.hash', '$2y$10$c103PP/1gdUtfVC.REX1H.9PfiLU0n99jWwtL6v7Fb6R8gSoT4N8C');

        $this->post('/under/check', ['code' => '1234'])
            ->assertSessionHas('can_visit', true);
    }

    /** @test */
    public function it_disables_login_for_three_minute_after_three_incorrect_attempts()
    {
        $this->unsuccessfulLogin()
            ->assertStatus(401)
            ->assertJson([
                'too_many_attempts' => false,
                'attempts_left' => 'Attempts left: 2',
            ]);

        $this->unsuccessfulLogin()
            ->assertStatus(401)
            ->assertJson([
                'too_many_attempts' => false,
                'attempts_left' => 'Attempts left: 1',
            ]);

        $this->unsuccessfulLogin()
            ->assertStatus(401)
            ->assertJson([
                'seconds_message' => 'Too many attempts please try again in 300 seconds.',
                'too_many_attempts' => true,
            ]);
    }

    /** @test */
    public function it_sets_title_correctly()
    {
        $this->get('/under/construction')
            ->assertSee('title');
    }

    /** @test */
    public function it_sets_back_button_translation_correctly()
    {
        $this->get('/under/construction')
            ->assertSee('back-button');
    }

    /** @test */
    public function it_sets_show_button_translation_correctly()
    {
        $this->get('/under/construction')
            ->assertSee('show');
    }

    /** @test */
    public function it_sets_hide_button_translation_correctly()
    {
        $this->get('/under/construction')
            ->assertSee('hide');
    }

    protected function unsuccessfulLogin()
    {
        return $this->post('/under/check', ['code' => 1235]);
    }

    protected function assertCanVisitProductionSite()
    {
        $this->get('/test')
            ->assertStatus(200)
            ->assertSee('production site!');
    }

    protected function assertCannotVisitProductionSite()
    {
        $this->get('/test')
            ->assertSessionMissing('can_visit')
            ->assertRedirect()
            ->assertHeader('location', '/under/construction');
    }
}
