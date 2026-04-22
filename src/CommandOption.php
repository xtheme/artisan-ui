<?php

declare(strict_types=1);

namespace Lorisleiva\ArtisanUI;

class CommandOption extends CommandInput
{
    public function isRequired(): bool
    {
        return $this->input->isValueRequired();
    }

    public function getType(): string
    {
        return 'options';
    }

    public function isBoolean(): bool
    {
        return ! $this->input->acceptValue();
    }
}
