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
                " - %s:\n%sdependsOn: %s",
                $this->indent(5),
                get_class($fixture),
                "\n".$this->indent(7).implode("\n".$this->indent(7), $this->formatDependsOn($fixture->dependsOn()))
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
            $value = "'$value'";
            if (!in_array($value, $this->availableRefKeys)) {
                $value .= '(m)';
            }
            $items[] = $value;
        }

        return $items;
    }

}