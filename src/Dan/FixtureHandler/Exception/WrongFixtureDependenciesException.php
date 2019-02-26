<?php

namespace Dan\FixtureHandler\Exception;

use Dan\FixtureHandler\Fixture\FixtureInterface;
class WrongFixtureDependenciesException extends \LogicException
{
    public function __construct(FixtureInterface $fixture, $detectedDependencies)
    {
        $exampleDependencies = [];
        foreach ($detectedDependencies as $detectedDependency) {
            $exampleDependencies[] = "'{$detectedDependency}'";
        }
        $exampleDependencies = implode(",\n            ", $exampleDependencies);
        $example = <<<EOT
The method dependsOn() should be:
        
    public function dependsOn(): array
    {
        return [
            {$exampleDependencies}
        ];
    }
EOT;
        $message = sprintf("Fixture of class %s dependencies are not in sync.\n\nDetected: [%s], Given: [%s]\n\n%s", get_class($fixture), implode(', ', $detectedDependencies), implode(', ', $fixture->dependsOn()), $example);
        parent::__construct($message);
    }
}