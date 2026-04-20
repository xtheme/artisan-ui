<?php

it('only displays whitelisted commands on the home page', function () {
    config()->set('artisan-ui.command_whitelist', ['key:generate']);

    $this->get(route('artisan-ui.home'))
        ->assertOk()
        ->assertSee('key:generate')
        ->assertDontSee('cache:clear');
});

it('only allows executing whitelisted commands', function () {
    config()->set('artisan-ui.command_whitelist', ['cache:clear']);

    $this->postJson(route('artisan-ui.execution', 'cache:clear'))
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->postJson(route('artisan-ui.execution', 'key:generate'))
        ->assertNotFound();
});

it('supports wildcard whitelist rules on the home page', function () {
    config()->set('artisan-ui.command_whitelist', ['key:*']);

    $this->get(route('artisan-ui.home'))
        ->assertOk()
        ->assertSee('key:generate')
        ->assertDontSee('cache:clear');
});

it('only allows executing commands that match wildcard whitelist rules', function () {
    config()->set('artisan-ui.command_whitelist', ['cache:*']);

    $this->postJson(route('artisan-ui.execution', 'cache:clear'))
        ->assertOk()
        ->assertJsonPath('success', true);

    $this->postJson(route('artisan-ui.execution', 'key:generate'))
        ->assertNotFound();
});

it('hides blacklisted commands from the home page', function () {
    config()->set('artisan-ui.command_whitelist', null);
    config()->set('artisan-ui.command_blacklist', ['key:*']);

    $this->get(route('artisan-ui.home'))
        ->assertOk()
        ->assertDontSee('key:generate')
        ->assertSee('cache:clear');
});

it('does not execute blacklisted commands even if whitelisted', function () {
    config()->set('artisan-ui.command_whitelist', ['cache:clear']);
    config()->set('artisan-ui.command_blacklist', ['cache:*']);

    $this->postJson(route('artisan-ui.execution', 'cache:clear'))
        ->assertNotFound();
});

