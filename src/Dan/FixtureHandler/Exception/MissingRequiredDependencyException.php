<?php

namespace Dan\FixtureHandler\Exception;

class MissingRequiredDependencyException extends \LogicException
{

    public function __construct(string $key)
    {
        $message = sprintf("The required ref '%s' is missing",
            $key
        );
        parent::__construct($message);
    }
}