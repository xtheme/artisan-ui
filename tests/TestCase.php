<?php

namespace Xtheme\ArtisanUI\Tests;

use Xtheme\ArtisanUI\ArtisanUIServiceProvider;
use Xtheme\ArtisanUI\Facades\ArtisanUI;
use Orchestra\Testbench\TestCase as Orchestra;

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
