<?php

declare(strict_types=1);

namespace Xtheme\ArtisanUI;

use Illuminate\Support\Facades\Route;
use Xtheme\ArtisanUI\Commands\ArtisanUIInstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class ArtisanUIServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('artisan-ui')
            ->hasConfigFile()
            ->hasViews()
            ->hasTranslations()
            ->hasAssets()
            ->hasRoute('web')
            ->hasCommand(ArtisanUIInstallCommand::class);
    }

    public function packageRegistered(): void
    {
        $this->app->singleton(ArtisanUI::class);
    }

    public function packageBooted(): void
    {
        // In local/development, serve assets directly from the package's public/ directory
        // without requiring vendor:publish to be run.
        if ($this->app->environment('local', 'testing')) {
            Route::get('vendor/artisan-ui/{file}', function (string $file) {
                $path = __DIR__ . '/../public/' . $file;

                abort_unless(file_exists($path), 404);

                $mimeTypes = [
                    'css' => 'text/css',
                    'js' => 'application/javascript',
                    'png' => 'image/png',
                    'svg' => 'image/svg+xml',
                ];
                $ext = pathinfo($path, PATHINFO_EXTENSION);
                $mime = $mimeTypes[$ext] ?? 'application/octet-stream';

                return response(file_get_contents($path), 200, ['Content-Type' => $mime]);
            })->name('artisan-ui.assets');
        }
    }
}
