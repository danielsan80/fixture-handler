<?php

namespace Dan\FixtureHandler\Exception\Report;

class NotLoadedFixtureReports extends \ArrayObject
{
    protected $availableRefKeys;

    public function __construct(array $notLoadedFixtures, array $availableRefKeys)
    {
        parent::__construct($notLoadedFixtures);
        $this->availableRefKeys = $availableRefKeys;
    }

    public function __toString(): string
    {
        $stack = [];

        foreach ($this as $i => $fixture) {
            $stack[] = sprintf(
                " - %s:\n    dependsOn: [%s]",
                get_class($fixture),
                $this->dependsOnAsString($fixture->dependsOn())
            );
        }
        return implode("\n\n", $stack);
    }

    protected function dependsOnAsString(array $dependsOn)
    {
        $items = [];
        foreach ($dependsOn as $value) {
            $value = "'$value'";
            if (!in_array($value, $this->availableRefKeys)) {
                $value .= '(m)';
            }
            $items[] = $value;
        }

        return implode(', ', $items);
    }

}