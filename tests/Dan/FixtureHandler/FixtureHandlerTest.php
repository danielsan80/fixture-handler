<?php

namespace Tests\Dan\FixtureHandler;

use Dan\FixtureHandler\Exception\LoadFixtureFailedException;
use Dan\FixtureHandler\Exception\UnresolvableDependenciesException;
use Dan\FixtureHandler\Exception\WrongFixtureDependenciesException;
use Dan\FixtureHandler\Fixture\FunctionFixture;
use Dan\FixtureHandler\FixtureHandler;
use PHPUnit\Framework\TestCase;

class FixtureHandlerTest extends TestCase
{

    /**
     * @test
     */
    public function abc()
    {
        $fh = new FixtureHandler();

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('a', 'a');
        }));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('b', $f->getRef('a') . 'b');
        }, ['a']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('c', $f->getRef('b') . 'c');
        }, ['b']));

        $this->assertEquals('abc', $fh->getRef('c'));
        $this->assertEquals('ab', $fh->getRef('b'));
        $this->assertEquals('a', $fh->getRef('a'));

    }

    /**
     * @test
     */
    public function bca()
    {
        $fh = new FixtureHandler();

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('b', $f->getRef('a') . 'b');
        }, ['a']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('c', $f->getRef('b') . 'c');
        }, ['b']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('a', 'a');
        }));


        $this->assertEquals('a', $fh->getRef('a'));
        $this->assertEquals('ab', $fh->getRef('b'));
        $this->assertEquals('abc', $fh->getRef('c'));

    }

    /**
     * @test
     */
    public function unresolvable_dependencies()
    {
        $fh = new FixtureHandler();

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('b', $f->getRef('a') . 'b');
        }, ['a']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('c', $f->getRef('b') . 'c');
        }, ['b']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('a', 'a');
        }, ['d']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('e', 'e');
        }, []));

        try {
            $fh->getRef('c');
            $this->fail('The FixtureHandler should detect the mistake');
        } catch (UnresolvableDependenciesException $e) {
//            throw $e;
        }

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function wrong_fixture_dependencies()
    {
        $fh = new FixtureHandler();

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('b', $f->getRef('a') . 'b');
        }, ['a']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('c', $f->getRef('b') . 'c');
        }, ['a', 'b']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('a', 'a');
        }));

        try {
            $fh->getRef('c');
            $this->fail('The FixtureHandler should detect the mistake');
        } catch (WrongFixtureDependenciesException $e) {
//            throw $e;
        }

        $this->assertTrue(true);
    }

    /**
     * @test
     */
    public function a_fixture_set_and_get_a_ref()
    {
        $fh = new FixtureHandler();

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('a', 'a');
        }, []));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('b', $f->getRef('a').'b');
            $f->getRef('b');
        }, ['a']));

        $this->assertEquals('a', $fh->getRef('a'));
        $this->assertEquals('ab', $fh->getRef('b'));

    }

    /**
     * @test
     */
    public function load_fixture_failed_exception()
    {
        $fh = new FixtureHandler();

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('b', $f->getRef('a') . 'b');
        }, ['a']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('c', $f->getRef('b') . 'c');
        }, ['b']));

        $fh->addFixture(new FunctionFixture(function (FunctionFixture $f) {
            $f->setRef('e', 'ciao');
            $f->setRef('a', $f->getRefOrFail('d'));
        }));

        try {
            $fh->getRef('c');
            $this->fail('The FixtureHandler should detect the mistake');
        } catch (LoadFixtureFailedException $e) {
//            throw $e;
        }

        $this->assertTrue(true);
    }


}