<?php

namespace Dan\FixtureHandler\Fixture;

use Dan\FixtureHandler\FixtureHandler;
abstract class AbstractFixture implements FixtureInterface
{
    /** @var FixtureHandler */
    protected $handler;
    public function setHandler(FixtureHandler $fixtureHandler)
    {
        if ($this->handler) {
            throw new \RuntimeException('The Handler was set yet');
        }
        $this->handler = $fixtureHandler;
    }
    public function hasRef($key)
    {
        $args = func_get_args();
        return (bool) $this->handler->hasRef(...$args);
    }
    public function setRef($key, $value)
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
    public function dependsOn()
    {
        return [];
    }
}