<?php

namespace Dan\FixtureHandler\Scenario;

use Dan\FixtureHandler\Fixture\AbstractFixture;
use Dan\FixtureHandler\Fixture\FixtureInterface;

abstract class AbstractScenario extends AbstractFixture implements ScenarioInterface
{
    protected function addFixture(FixtureInterface $fixture)
    {
        $this->handler->addFixture($fixture);
    }

    protected function addScenario(ScenarioInterface $scenario)
    {
        $this->handler->addScenario($scenario);
    }

    final function dependsOn(): array
    {
        return parent::dependsOn();
    }
}