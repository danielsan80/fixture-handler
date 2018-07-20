<?php

namespace Dan\FixtureHandler;

use Dan\FixtureHandler\Exception\MissingRequiredDependencyException;
use Dan\FixtureHandler\Fixture\FixtureInterface;
use Dan\FixtureHandler\Scenario\ScenarioInterface;

class FixtureHandler
{

    /** @var FixtureInterface[] */
    protected $fixtures = [];

    /** @var FixtureInterface[] */
    protected $loadedFixtures = [];

    /** @var array */
    protected $refs = [];

    /** @var bool */
    private $loading = false;

    /** @var FixtureDebugger */
    private $fixtureDebugger;

    public function __construct()
    {
        $this->fixtureDebugger = new FixtureDebugger();
    }

    public function addScenario(ScenarioInterface $scenario)
    {
        $this->addFixture($scenario);
    }

    public function addFixture(FixtureInterface $fixture)
    {
        $hash = uniqid('', true);
        $this->fixtures[$hash] = $fixture;
        $fixture->setHandler($this);
    }

    public function setRef($key, $value)
    {
        $this->fixtureDebugger->addDetectedWrittenKey($key);
        return $this->_setRef($key, $value);
    }

    private function _setRef($key, $value)
    {
        if ($this->_hasRef($key)) {
            throw new \InvalidArgumentException("'$key' is set yet");
        }

        $this->refs[$key] = $value;
        return $value;
    }

    private function _hasRef($key)
    {
        if (func_num_args() == 1) {
            return array_key_exists($key, $this->refs);
        }

        $args = func_get_args();
        unset($args[0]);
        return array_key_exists($key, $this->refs) && $this->_hasRef(...$args);
    }

    public function getRef($key, $default = null)
    {
        if ($this->hasRef($key)) {
            return $this->_getRef($key);
        }

        return $default;
    }

    public function hasRef($key): bool
    {
        $this->ensureFixturesAreLoaded();

        $args = func_get_args();

        $this->fixtureDebugger->addDetectedReadKeys($args);

        return $this->_hasRef(...$args);
    }

    protected function ensureFixturesAreLoaded()
    {
        if ($this->loading) {
            return;
        }
        $this->loading = true;

        $fixturesStatus = $this->getFixturesStatus();

        while (count($this->fixtures)) {
            foreach ($this->fixtures as $hash => $fixture) {
                if ($this->fixtureDependenciesAreLoaded($fixture)) {
                    $this->loadFixture($hash, $fixture);
                }
            }
            $newFixturesStatus = $this->getFixturesStatus();
            if ($newFixturesStatus == $fixturesStatus) {
                $this->fixtureDebugger->declareUnresolvable($this->fixtures);
            }
            $fixturesStatus = $newFixturesStatus;
        }

        $this->loading = false;
    }

    private function getFixturesStatus()
    {
        return implode(',', array_keys($this->fixtures));
    }

    private function fixtureDependenciesAreLoaded(FixtureInterface $fixture)
    {
        $dependencies = $fixture->dependsOn();
        sort($dependencies);
        $intersection = array_intersect($dependencies, array_keys($this->refs));
        sort($intersection);
        return $dependencies == $intersection;
    }

    private function loadFixture(string $hash, FixtureInterface $fixture)
    {
        unset($this->fixtures[$hash]);
        $this->loadedFixtures[$hash] = $fixture;
        $this->fixtureDebugger->setCurrentFixture($hash, $fixture);
        try {
            $fixture->load();
        } catch (\Throwable $e) {
            $this->fixtureDebugger->declareLoadFixtureFailed($e, $this->fixtures);
        }
        $this->fixtureDebugger->checkDetectedDependencies($hash, $fixture);
        $this->fixtureDebugger->resetCurrentFixture();
    }

    private function _getRef($key)
    {
        if (!$this->_hasRef($key)) {
            throw new \InvalidArgumentException("'$key' is not set");
        }

        return $this->refs[$key];
    }

    public function getRefOrFail($key)
    {
        if ($this->hasRef($key)) {
            return $this->_getRef($key);
        }

        throw new MissingRequiredDependencyException($key);
    }

    public function loadFixtures()
    {
        $this->ensureFixturesAreLoaded();
    }

}