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

    public function __construct(FixtureInterface $fixture, array $readKeys, array $writenKeys)
    {
        $this->fixture = $fixture;
        $this->readKeys = $readKeys;
        $this->writtenKeys = $writenKeys;
    }

    /**
     * @return FixtureInterface
     */
    public function fixture(): FixtureInterface
    {
        return $this->fixture;
    }

    /**
     * @return array
     */
    public function readKeys(): array
    {
        return $this->readKeys;
    }

    /**
     * @return array
     */
    public function writtenKeys(): array
    {
        return $this->writtenKeys;
    }

}