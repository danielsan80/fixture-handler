<?php

namespace Dan\FixtureHandler\Exception\Report;

class NotLoadedFixtureReports extends \ArrayObject
{
    protected $availableRefKeys;
    public function __construct($notLoadedFixtures, $availableRefKeys)
    {
        parent::__construct($notLoadedFixtures);
        $this->availableRefKeys = $availableRefKeys;
    }
    public function __toString()
    {
        $stack = [];
        foreach ($this as $i => $fixture) {
            $stack[] = sprintf(" - %s:\n%sdependsOn: \n%s", get_class($fixture), $this->indent(5), $this->indent(5) . implode("\n" . $this->indent(5), $this->formatDependsOn($fixture->dependsOn())));
        }
        return (string) implode("\n\n", $stack);
    }
    protected function indent($length)
    {
        return str_pad('', $length, ' ');
    }
    protected function formatDependsOn($dependsOn)
    {
        $items = [];
        foreach ($dependsOn as $value) {
            if (in_array($value, $this->availableRefKeys)) {
                $available = '🗸';
            } else {
                $available = ' ';
            }
            $items[] = $available . " '{$value}'";
        }
        return $items;
    }
}