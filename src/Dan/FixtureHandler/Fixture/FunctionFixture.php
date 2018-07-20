<?php

namespace Dan\FixtureHandler\Fixture;

use Closure;

class FunctionFixture extends AbstractFixture
{
    /** @var Closure */
    protected $func;

    /** @var array */
    protected $dependsOn;

    public function __construct(Closure $func, $dependsOn = [])
    {
        $this->func = $func;
        $this->dependsOn = $dependsOn;
    }

    public function load(): void
    {
        $func = $this->func;

        $func($this);
    }

    public function dependsOn(): array
    {
        return $this->dependsOn;
    }

}