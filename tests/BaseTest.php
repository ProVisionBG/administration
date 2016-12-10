<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

namespace ProVision\Administration\Tests;

class BaseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testRun()
    {
        $this->visit('/')->see('Laravel');
        $this->visit('/admin')->see('ProVision');
    }

    public function testLogin()
    {
        $this->visit('admin')
            ->type('admin@provision.bg', 'email')
            ->type('password', 'password')
            ->press('sign_in')
            ->see('Администраторско табло');
    }
}
