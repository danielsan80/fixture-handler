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
                " - %s:\n%sdependsOn: \n%s",
                get_class($fixture),
                $this->indent(5),
                $this->indent(7).implode("\n".$this->indent(7), $this->formatDependsOn($fixture->dependsOn()))
            );
        }
        return implode("\n\n", $stack);
    }

    protected function indent($length)
    {
        return str_pad('',$length,' ');
    }

    protected function formatDependsOn(array $dependsOn)
    {
        $items = [];
        foreach ($dependsOn as $value) {
            if (in_array($value, $this->availableRefKeys)) {
                $available = '[✔]';
            } else {
                $available = '[ ]';
            }
            $items[] = $available . " '$value'";;
        }

        return $items;
    }

}