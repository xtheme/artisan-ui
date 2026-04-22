<?php

declare(strict_types=1);

namespace Lorisleiva\ArtisanUI;

class CommandArgument extends CommandInput
{
    public function isRequired(): bool
    {
        return $this->input->isRequired();
    }

    public function getType(): string
    {
        return 'arguments';
    }
}
