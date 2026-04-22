<?php

declare(strict_types=1);

namespace Xtheme\ArtisanUI\Actions;

use Illuminate\Console\Command;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\BufferedOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Xtheme\ArtisanUI\ArtisanUI;

class ExecuteArtisanCommand
{
    public function __invoke(string $name, ArtisanUI $artisanUI, Request $request): JsonResponse
    {
        $command = $artisanUI->findOrFail($name)->getArtisanCommand();
        $input = $this->getInputFromRequest($request);
        $input->setInteractive(false);
        $output = new BufferedOutput(OutputInterface::VERBOSITY_NORMAL, decorated: true);

        try {
            $returnCode = $command->run($input, $output);
        } catch (Throwable $exception) {
            return response()->json([
                'success' => false,
                'output' => $exception->getMessage(),
            ]);
        }

        return response()->json([
            'success' => $returnCode === Command::SUCCESS,
            'output' => $output->fetch(),
        ]);
    }

    protected function getInputFromRequest(Request $request): InputInterface
    {
        $arguments = collect($request->get('arguments', []))
            ->reject(fn ($value) => is_null($value));
        $options = collect($request->get('options', []))
            ->reject(fn ($value) => is_null($value))
            ->mapWithKeys(fn ($value, $key) => ["--$key" => $value]);

        return new ArrayInput($arguments->merge($options)->toArray());
    }
}
