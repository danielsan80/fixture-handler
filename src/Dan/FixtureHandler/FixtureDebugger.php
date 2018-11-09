<?php

namespace Dan\FixtureHandler;

use Dan\FixtureHandler\Exception\LoadFixtureFailedException;
use Dan\FixtureHandler\Exception\Report\LoadedFixtureReport;
use Dan\FixtureHandler\Exception\Report\LoadedFixtureReports;
use Dan\FixtureHandler\Exception\Report\NotLoadedFixtureReports;
use Dan\FixtureHandler\Exception\UnresolvableDependenciesException;
use Dan\FixtureHandler\Exception\WrongFixtureDependenciesException;
use Dan\FixtureHandler\Fixture\FixtureInterface;

class FixtureDebugger
{
    const NO_FIXTURE_HASH = 'no_fixture_hash';

    /** @var string */
    private $currentFixtureHash = self::NO_FIXTURE_HASH;

    /** @var array */
    private $readKeys = [];

    /** @var array */
    private $writtenKeys = [];

    /** @var FixtureInterface[] */
    private $loadedFixtures = [];

    public function setCurrentFixture($hash, FixtureInterface $fixture)
    {
        $this->currentFixtureHash = $hash;
        $this->loadedFixtures[$hash] = $fixture;
    }

    public function resetCurrentFixture()
    {
        $this->currentFixtureHash = self::NO_FIXTURE_HASH;
    }

    public function addDetectedReadKeys($keys)
    {
        if (!isset($this->readKeys[$this->currentFixtureHash])) {
            $this->readKeys[$this->currentFixtureHash] = [];
        }
        foreach ($keys as $key) {
            $this->readKeys[$this->currentFixtureHash][] = $key;
        }
        $this->readKeys[$this->currentFixtureHash] = array_values(array_unique($this->readKeys[$this->currentFixtureHash]));
    }

    public function addDetectedWrittenKey($key)
    {
        if (!isset($this->writtenKeys[$this->currentFixtureHash])) {
            $this->writtenKeys[$this->currentFixtureHash] = [];
        }
        $this->writtenKeys[$this->currentFixtureHash][] = $key;
        $this->writtenKeys[$this->currentFixtureHash] = array_values(array_unique($this->writtenKeys[$this->currentFixtureHash]));
    }


    public function checkDetectedDependencies(string $hash, FixtureInterface $fixture)
    {
        $dependencies = $fixture->dependsOn();
        $detectedDependencies = [];
        $detectedSetRefs = [];

        if (isset($this->readKeys[$hash])) {
            $detectedDependencies = $this->readKeys[$hash];
        }

        if (isset($this->writtenKeys[$hash])) {
            $detectedSetRefs = $this->writtenKeys[$hash];
        }
        $detectedDependencies = array_diff($detectedDependencies, $detectedSetRefs);

        sort($detectedDependencies);
        sort($dependencies);

        if ($dependencies != $detectedDependencies) {
            throw new WrongFixtureDependenciesException($fixture, $detectedDependencies);
        }
    }

    public function declareLoadFixtureFailed(\Throwable $e, array $notLoadedFixtures, array $availableRefs)
    {
        $notLoadedFixtures = new NotLoadedFixtureReports(array_values($notLoadedFixtures), array_keys($availableRefs));
        throw new LoadFixtureFailedException($e, $this->loadedFixtures(), $notLoadedFixtures);
    }

    public function declareUnresolvable(array $notLoadedFixtures, array $availableRefs)
    {
        $notLoadedFixtures = new NotLoadedFixtureReports(array_values($notLoadedFixtures), array_keys($availableRefs));
        throw new UnresolvableDependenciesException($this->loadedFixtures(), $notLoadedFixtures);
    }

    protected function loadedFixtures(): LoadedFixtureReports
    {
        $items = [];
        foreach($this->loadedFixtures as $hash => $fixture) {
            $readKeys = [];
            if (isset($this->readKeys[$hash])) {
                $readKeys = $this->readKeys[$hash];
            }
            $writtenKeys = [];
            if (isset($this->writtenKeys[$hash])) {
                $writtenKeys = $this->writtenKeys[$hash];
            }
            $items[] = new LoadedFixtureReport($fixture, $readKeys, $writtenKeys);
        }
        return new LoadedFixtureReports($items);
    }

}