<?php

namespace Dan\FixtureHandler\Exception\Report;

class LoadedFixtureReports extends \ArrayObject
{
    public function __toString()
    {
        $stack = [];
        foreach ($this as $i => $loadedFixture) {
            $stack[] = sprintf("%s: %s:\n    read keys: [%s]\n    written keys: [%s]", str_pad($i, 2, ' ', STR_PAD_LEFT), get_class($loadedFixture->fixture()), implode(', ', $loadedFixture->readKeys()), implode(', ', $loadedFixture->writtenKeys()));
        }
        return (string) implode("\n\n", $stack);
    }
}