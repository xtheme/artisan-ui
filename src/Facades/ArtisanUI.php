<?php

declare(strict_types=1);

namespace Lorisleiva\ArtisanUI\Facades;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Facade;
use Lorisleiva\ArtisanUI\ArtisanUI as ConcreteArtisanUI;

/**
 * @see ConcreteArtisanUI
 * @method ConcreteArtisanUI auth(?Closure $callback)
 * @method bool check(Request $request)
 */
class ArtisanUI extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return ConcreteArtisanUI::class;
    }
}
