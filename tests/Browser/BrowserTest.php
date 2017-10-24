<?php

namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class BrowserTest extends DuskTestCase
{
    /**
     * Check to see if the package is showing Under Construction title
     *
     * @return void
     */
    public function test_to_see_if_the_package_is_showing_the_under_construction_title()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertTitle('Under Construction')
                    ->assertSee('Under Construction');
        });
    }
     /**
     * Check to see if the number pressed is visible when you click the show button
     *
     * @return void
     */
    public function test_to_see_if_the_package_is_showing_the_enterd_number()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('#app > div > div.panel.flex.flex-column > div.flex-three > div > div:nth-child(3) > div > div:nth-child(1) > div > h3') //1
                    ->assertSeeIn('#app > div > div.panel.flex.flex-column > div.flex-one > div > div:nth-child(1) > div > h3', '-')
                    ->click('#app > div > div.panel.flex.flex-column > div.flex-three > div > div:nth-child(4) > div > div:nth-child(1) > div > h3') //show
                    ->assertSeeIn('#app > div > div.panel.flex.flex-column > div.flex-one > div > div:nth-child(1) > div > h3', '1');
        });
    }
    /**
     * Check to see if text Attemps error appers after entering wrong code
     *
     * @return void
     */
    public function test_to_see_if_the_error_is_shown_when_entered_a_wrong_code()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('#app > div > div.panel.flex.flex-column > div.flex-three > div > div:nth-child(3) > div > div:nth-child(1) > div > h3') //1
                    ->click('#app > div > div.panel.flex.flex-column > div.flex-three > div > div:nth-child(3) > div > div:nth-child(3) > div > h3') //3
                    ->click('#app > div > div.panel.flex.flex-column > div.flex-three > div > div:nth-child(3) > div > div:nth-child(3) > div > h3') //3
                    ->click('#app > div > div.panel.flex.flex-column > div.flex-three > div > div:nth-child(1) > div > div:nth-child(1) > div > h3') //7
                    ->pause(200)
                    ->assertSeeIn('#app > div > div:nth-child(2) > p', 'Attempts');

                    
                    
        });
    }
}
