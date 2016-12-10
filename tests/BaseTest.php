<?php

/*
 * ProVision Administration, http://ProVision.bg
 * Author: Venelin Iliev, http://veneliniliev.com
 */

class BaseTest extends TestCase
{
    public function testVersion()
    {
        $this->visit('/bg/admin/login');

        dd($this->response->getContent());

//        $this->visit('/bg/' . config('provision_administration.url_prefix') . '/login')
//            ->see('ProVisionnn');
    }
}
