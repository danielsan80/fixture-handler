<?php

namespace Dan\FixtureHandler\Exception\Report;

class NotLoadedFixtureReports extends \ArrayObject
{

    public function __toString(): string
    {
        $stack = [];

        foreach ($this as $i => $fixture) {
            $stack[] = sprintf(
                " - %s:\n    dependsOn: [%s]",
                get_class($fixture),
                implode(', ', $fixture->dependsOn())
            );
        }
        return implode("\n\n", $stack);
    }

}