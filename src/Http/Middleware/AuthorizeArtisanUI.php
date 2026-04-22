<?php

declare(strict_types=1);

namespace Xtheme\ArtisanUI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Xtheme\ArtisanUI\Facades\ArtisanUI;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthorizeArtisanUI
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (! ArtisanUI::check($request)) {
            throw new HttpException(403);
        }

        return $next($request);
    }
}
