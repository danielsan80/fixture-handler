<?php

namespace Dan\FixtureHandler\Fixture;

use Dan\FixtureHandler\FixtureHandler;

interface FixtureInterface
{
    public function setHandler(FixtureHandler $fixtureHandler): void;

    public function load(): void;

    public function dependsOn(): array;
}