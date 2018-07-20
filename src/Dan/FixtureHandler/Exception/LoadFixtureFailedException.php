<?php

namespace Dan\FixtureHandler\Exception;

use Dan\FixtureHandler\Exception\Report\LoadedFixtureReports;
use Dan\FixtureHandler\Exception\Report\NotLoadedFixtureReports;

class LoadFixtureFailedException extends \LogicException
{
    public function __construct(\Throwable $e, LoadedFixtureReports $loadedFixtures, NotLoadedFixtureReports $notLoadedFixtures)
    {
        $message = sprintf("%s\n\n Loaded fixtures:\n\n%s\n\nNot loaded fixtures:\n\n%s\n\n",
            $e->getMessage(),
            $loadedFixtures,
            $notLoadedFixtures
        );
        parent::__construct($message, 0, $e);
    }


}