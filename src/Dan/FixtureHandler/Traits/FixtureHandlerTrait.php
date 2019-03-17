<?php

namespace Dan\FixtureHandler\Traits;

use Dan\FixtureHandler\Fixture\FixtureInterface;
use Dan\FixtureHandler\FixtureHandler;
use Dan\FixtureHandler\Scenario\ScenarioInterface;

trait FixtureHandlerTrait
{
    /** @var FixtureHandler */
    protected $fixtureHandler;

    protected function ensureFixtureHandlerExists()
    {
        if (!$this->fixtureHandler) {
            $this->fixtureHandler = new FixtureHandler();
        }
    }

    protected function getFixtureHandler()
    {
        $this->ensureFixtureHandlerExists();
        return $this->fixtureHandler;
    }

    protected function addFixture(FixtureInterface $fixture)
    {
        $this->ensureFixtureHandlerExists();
        $this->fixtureHandler->addFixture($fixture);
    }

    protected function addScenario(ScenarioInterface $scenario)
    {
        $this->ensureFixtureHandlerExists();
        $this->fixtureHandler->addScenario($scenario);
    }

    protected function loadFixtures()
    {
        $this->ensureFixtureHandlerExists();
        $this->fixtureHandler->loadFixtures();
    }

    protected function hasRef($key)
    {
        $this->ensureFixtureHandlerExists();
        $args = func_get_args();
        return $this->fixtureHandler->hasRef(...$args);
    }

    protected function getRef($key, $default = null)
    {
        $this->ensureFixtureHandlerExists();
        return $this->fixtureHandler->getRef($key, $default);
    }

    protected function getRefOrFail($key)
    {
        $this->ensureFixtureHandlerExists();
        return $this->fixtureHandler->getRefOrFail($key);
    }

    protected function setRef($key, $value)
    {
        $this->ensureFixtureHandlerExists();
        return $this->fixtureHandler->setRef($key, $value);
    }

}