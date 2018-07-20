<?php

namespace Dan\FixtureHandler\Exception;


use Dan\FixtureHandler\Exception\Report\LoadedFixtureReports;
use Dan\FixtureHandler\Exception\Report\NotLoadedFixtureReports;

class UnresolvableDependenciesException extends \LogicException
{
    public function __construct(LoadedFixtureReports $loadedFixtures, NotLoadedFixtureReports $notLoadedFixtures)
    {
        $message = sprintf("It is not possible to resolve the fixture dependencies.\n\nLoaded fixtures:\n\n%s\n\nNot loaded fixtures:\n\n%s\n\n",
            $loadedFixtures,
            $notLoadedFixtures
        );
        parent::__construct($message);

    }
}