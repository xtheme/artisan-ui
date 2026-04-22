<?php

declare(strict_types=1);

namespace Lorisleiva\ArtisanUI;

use Closure;
use Illuminate\Console\Command as ArtisanCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;

class ArtisanUI
{
    public ?Closure $authUsing = null;

    public function auth(?Closure $callback): self
    {
        $this->authUsing = $callback;

        return $this;
    }

    public function check(Request $request): bool
    {
        return ($this->authUsing ?: function () {
            return app()->environment('local');
        })($request);
    }

    public function all(): Collection
    {
        $commands = collect(Artisan::all())
            ->filter(fn ($command) => $command instanceof ArtisanCommand)
            ->mapInto(Command::class);

        $whitelist = config('artisan-ui.command_whitelist');
        $allowed = is_null($whitelist)
            ? null
            : collect($whitelist)->filter()->values();

        $blacklist = collect(config('artisan-ui.command_blacklist', []))
            ->filter()
            ->values();

        return $commands->filter(function (Command $command) use ($allowed, $blacklist) {
            $name = $command->getName();

            if ($this->isBlacklisted($name, $blacklist)) {
                return false;
            }

            if (! is_null($allowed) && ! $this->matchesRules($name, $allowed)) {
                return false;
            }

            return true;
        });
    }

    protected function isBlacklisted(string $name, Collection $blacklist): bool
    {
        return $this->matchesRules($name, $blacklist);
    }

    protected function matchesRules(string $name, Collection $rules): bool
    {
        return $rules->contains(function (string $rule) use ($name) {
            // Allow prefix patterns such as "db:*" or exact command names.
            if (str_ends_with($rule, '*')) {
                $prefix = substr($rule, 0, -1);

                return str_starts_with($name, $prefix);
            }

            return $rule === $name;
        });
    }

    public function allGroupedByNamespace(): Collection
    {
        $namespaces = $this->all()
            ->map(fn (Command $command) => $command->getNamespace())
            ->unique()
            ->values();

        return $this->all()
            ->groupBy(function (Command $command) use ($namespaces) {
                if ($namespaces->contains($command->getName())) {
                    return $command->getName();
                }

                return $command->getNamespace();
            })
            ->sortKeys();
    }

    public function find(string $name): ?Command
    {
        return $this->all()->get($name);
    }

    public function findOrFail(string $name): Command
    {
        return tap($this->find($name), function ($command) {
            abort_unless($command, 404, 'Command not found.');
        });
    }
}
