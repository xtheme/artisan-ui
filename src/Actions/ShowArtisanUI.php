<?php

declare(strict_types=1);

namespace Xtheme\ArtisanUI\Actions;

use Illuminate\View\View;
use Xtheme\ArtisanUI\ArtisanUI;

class ShowArtisanUI
{
    public function __invoke(ArtisanUI $artisanUI): View
    {
        return view('artisan-ui::home')
            ->with('commands', $artisanUI->allGroupedByNamespace());
    }
}
