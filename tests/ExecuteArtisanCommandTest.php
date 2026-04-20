<?php

it('successfully executes an artisan command', function () {
    $this->postJson(route('artisan-ui.execution', 'cache:clear'))
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['success', 'output']);
});

it('executes artisan command with arguments and options', function () {
    $this->postJson(route('artisan-ui.execution', 'make:model'), [
        'arguments' => ['name' => 'Subscription'],
        'options' => ['force' => true],
    ])
        ->assertOk()
        ->assertJsonPath('success', true)
        ->assertJsonStructure(['success', 'output']);
});

it('provides the output when something went wrong', function () {
    $this->postJson(route('artisan-ui.execution', 'make:model'))
        ->assertOk()
        ->assertJsonPath('success', false)
        ->assertJsonStructure(['success', 'output']);
});
