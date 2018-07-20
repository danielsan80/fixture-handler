<?php

namespace Dan\FixtureHandler\Fixture;

use Dan\FixtureHandler\FixtureHandler;

abstract class AbstractFixture implements FixtureInterface
{
    /** @var FixtureHandler */
    protected $handler;

    public function setHandler(FixtureHandler $fixtureHandler): void
    {
        if ($this->handler) {
            throw new \RuntimeException('The Handler was set yet');
        }

        $this->handler = $fixtureHandler;
    }

    public function hasRef(string $key): bool
    {
        $args = func_get_args();
        return $this->handler->hasRef(...$args);
    }

    public function setRef(string $key, $value)
    {
        return $this->handler->setRef($key, $value);
    }

    public function getRef($key, $default = null)
    {
        return $this->handler->getRef($key, $default);
    }

    public function getRefOrFail($key)
    {
        return $this->handler->getRefOrFail($key);
    }

    public function dependsOn(): array
    {
        return [];
    }
}