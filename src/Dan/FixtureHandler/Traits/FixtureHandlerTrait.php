<?php

namespace Dan\FixtureHandler\Traits;

use Dan\FixtureHandler\Fixture\FixtureInterface;
use Dan\FixtureHandler\FixtureHandler;
use Dan\FixtureHandler\Scenario\ScenarioInterface;

trait FixtureHandlerTrait
{
    /** @var FixtureHandler */
    protected $fixtureHandler;

    protected function ensureFixtureHandleExists()
    {
        if (!$this->fixtureHandler) {
            $this->fixtureHandler = new FixtureHandler();
        }
    }

    protected function getFixtureHandler()
    {
        $this->ensureFixtureHandleExists();
        return $this->fixtureHandler;
    }

    protected function addFixture(FixtureInterface $fixture)
    {
        $this->ensureFixtureHandleExists();
        $this->fixtureHandler->addFixture($fixture);
    }

    protected function addScenario(ScenarioInterface $scenario)
    {
        $this->ensureFixtureHandleExists();
        $this->fixtureHandler->addScenario($scenario);
    }

    protected function loadFixtures()
    {
        $this->ensureFixtureHandleExists();
        $this->fixtureHandler->loadFixtures();
    }

    protected function hasRef($key)
    {
        $this->ensureFixtureHandleExists();
        $args = func_get_args();
        return $this->fixtureHandler->hasRef(...$args);
    }

    protected function getRef($key, $default = null)
    {
        $this->ensureFixtureHandleExists();
        return $this->fixtureHandler->getRef($key, $default);
    }

    protected function getRefOrFail($key)
    {
        $this->ensureFixtureHandleExists();
        return $this->fixtureHandler->getRefOrFail($key);
    }

    protected function setRef($key, $value)
    {
        $this->ensureFixtureHandleExists();
        return $this->fixtureHandler->setRef($key, $value);
    }

}