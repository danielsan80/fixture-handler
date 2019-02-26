<?php

namespace Dan\FixtureHandler\Fixture;

use Dan\FixtureHandler\FixtureHandler;
interface FixtureInterface
{
    public function setHandler(FixtureHandler $fixtureHandler);
    public function load();
    public function dependsOn();
}