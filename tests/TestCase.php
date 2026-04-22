<?php

namespace Xtheme\ArtisanUI\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use Xtheme\ArtisanUI\ArtisanUIServiceProvider;
use Xtheme\ArtisanUI\Facades\ArtisanUI;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ArtisanUIServiceProvider::class,
        ];
    }

    public function getEnvironmentSetUp($app)
    {
        ArtisanUI::auth(function ($request) {
            return true;
        });
    }
}
