<?php

declare(strict_types=1);

namespace Lorisleiva\ArtisanUI;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

abstract class CommandInput
{
    protected InputArgument|InputOption $input;

    public function __construct(InputArgument|InputOption $input)
    {
        $this->input = $input;
    }

    abstract public function isRequired(): bool;
    abstract public function getType(): string;

    public function getName(): string
    {
        return $this->input->getName();
    }

    public function getDescription(): ?string
    {
        return $this->input->getDescription();
    }

    public function getDefault(): string|array|null
    {
        return $this->input->getDefault();
    }

    public function getDefaultToDisplay(): string
    {
        return is_string($this->getDefault()) ? $this->getDefault() : '';
    }

    public function isArray(): bool
    {
        return $this->input->isArray();
    }

    public function getAbsoluteKey(): string
    {
        return sprintf('%s["%s"]', $this->getType(), $this->getName());
    }
}
