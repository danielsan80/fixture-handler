<?php

namespace Dan\FixtureHandler\Exception\Report;

use Dan\FixtureHandler\Fixture\FixtureInterface;
class LoadedFixtureReport
{
    /** @var FixtureInterface */
    protected $fixture;
    /** @var array */
    protected $readKeys;
    /** @var array */
    protected $writtenKeys;
    public function __construct(FixtureInterface $fixture, $readKeys, $writenKeys)
    {
        $this->fixture = $fixture;
        $this->readKeys = $readKeys;
        $this->writtenKeys = $writenKeys;
    }
    /**
     * @return FixtureInterface
     */
    public function fixture()
    {
        return $this->fixture;
    }
    /**
     * @return array
     */
    public function readKeys()
    {
        return $this->readKeys;
    }
    /**
     * @return array
     */
    public function writtenKeys()
    {
        return $this->writtenKeys;
    }
}