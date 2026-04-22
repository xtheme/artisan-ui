<?php

declare(strict_types=1);

namespace Xtheme\ArtisanUI\Actions;

use Illuminate\View\View;
use Xtheme\ArtisanUI\ArtisanUI;

class ShowArtisanCommand
{
    public function __invoke(string $name, ArtisanUI $artisanUI): View
    {
        return view('artisan-ui::detail')
            ->with('command', $artisanUI->findOrFail($name));
    }
}
