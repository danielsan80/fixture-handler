# FixtureHandler



You can use FixtureHandler in your php test to compact your setup.

It is inspired by Doctrine Fixtures but FixtureHandler is not related only to the model or Doctrine entities.

It is inspired by the Symfony Dependency Injection Container but is not only a way to get services.

FixtureHandler allows you to save the references of data, models and services in a key-value registry.

It allows you to define some Fixtures and load them in a lazy way.

It allows you to define dependencies between Fixtures.

It allows you to define some Scenarios.

## Getting started

Add FixtureHandler as dev requirement in your composer.json

```
composer require dansan/fixture-handler --dev
```

Use it in a PHPUnit TestCase

```php
...
use Dan\FixtureHandler\FixtureHandler;
...
$fh = new FixtureHandler();
$fh->setRef('user.mario', new User('Mario');
...
$mario = $fh->getRef('user.mario');
...
```

## How it works

The basic use is...

```php
$fh = new FixtureHandler();
$fh->setRef('user.mario', new User('Mario');
$fh->setRef('user.luigi', new User('Luigi');

$mario = $fh->getRef('user.mario');
$luigi = $fh->getRef('user.luigi');

$mario->helps($luigi);
...
```

But it make sense to put the user instantiation into a Fixture...

```php
class UserFixture extends AbstractFixture
{
    public function load(): void
    {
        $this->setRef('user.mario', new User('Mario');
        $this->setRef('user.luigi', new User('Luigi');
    }
}
```

...and add it to the FixtureHandler  

```php
$fh = new FixtureHandler();
$fh->addFixture(new UserFixture());

$mario = $fh->getRef('user.mario');
$luigi = $fh->getRef('user.luigi');

$mario->helps($luigi);
...
```

You can define an item fixture depending on the users (not depending on the UserFixture)...

```php
class ItemFixture extends AbstractFixture
{
    public function load(): void
    {
        $this->setRef('item.mushroom', $mushroom = new Item('mushroom'));
        $this->setRef('item.star', $star = new Item('star'));
        $this->setRef('item.flower', $flower = new Item('flower'));
        
        $this->>getRef('user.mario')->collect($star);
        $this->>getRef('user.mario')->collect($mushroom);
        $this->>getRef('user.luigi')->collect($flower);
    }
    
    puplic function dependsOn(): array
    {
        return [
            'user.mario',
            'user.luigi',
        ];
    }
}
```

You don't need to add Fixture in the right order: FixtureHandler will load added fixture in the right
order thanks to dependsOn() method.


```php
$fh = new FixtureHandler();
$fh->addFixture(new ItemFixture());
$fh->addFixture(new UserFixture());

$mario = $fh->getRef('user.mario');
$luigi = $fh->getRef('user.luigi');
$mushroom = $this->getRef('item.mushroom');

$mario->give($luigi, $mushroom);
...
```

> dependsOn() method specifies the list of keys that load() method needs for the getRef() calls.
Anyway if dependsOn() is not in sync with load() FixtureHandler will notify you
the missing and exceeding keys so you can fix it.


You can create a Scenario, a special fixture depending on nothing,
to add several fixtures or set some refs...

```php
class MyScenario extends AbstractScenario
{
    public function load()
    {
        $this->setRef('guzzle', new FakeGuzzleClient());
    
        $this->addFixture(new ItemFixture());
        $this->addFixture(new UserFixture());
    }
}

```

...so you can be ready in a few lines of code

```php
$fh = new FixtureHandler();
$fh->addScenario(new MyScenario());

$guzzle = $fh->getRef('guzzle');
...
```

About refs...
- you can set refs,
- you can get refs and specify a default,
- you can get refs and specify to trig an exception if they don't exist,
- you can check if refs exist.

```php
$fh = new FixtureHandler();

$fh->setRef('a_key', 'a value');

$value = $fh->getRef('a_key');
$value = $fh->getRef('a_not_existing_key', 'a default value');

$value = $fh->getRefOrFail('a_not_existing_key');

if ($fh->hasRef('a_key')) {
    ...
}
...
```

You can use the trait in your TestCase...

```php
MyTest extends TestCase
{
    use FixtureHandlerTrait;
    
     public function setUp()
     {
        parent::setUp();

        $this->addFixture(new UserFixture());
     }
     
     /**
      * @test
      */
     public it_works()
     {
        $mario = $this->getRef('user.mario');
        ...
     }
     
     ...
}

```

Fixture loading will happen when you ask for a ref for the first time...

```php
$fh = new FixtureHandler();
$fh->addFixture(new ItemFixture()); // <--- NOT HERE
$fh->addFixture(new UserFixture()); // <--- NOT HERE
...
$mario = $fh->getRef('user.mario'); // <--- HERE
...
```

...but you can force the fixture loading

```php
$fh = new FixtureHandler();
$fh->addFixture(new ItemFixture()); // <--- NOT HERE
$fh->addFixture(new UserFixture()); // <--- NOT HERE
$fh->loadFixtures(); // <--- HERE
...
$mario = $fh->getRef('user.mario'); // <--- LOADED YET
...
```

Why I should force the fixture loading? For example...

```php
$fh = new FixtureHandler();
$fh->addFixture(new ItemFixture());
$fh->addFixture(new UserFixture());
$fh->loadFixtures();
$entityManager->flush();
...
```

That's all!



## Development

Clone the project:

```
git clone <url> <project_dir>
cd <project_dir>
```

Run `cp .env.dist .env` and edit the `.env` file properly.

> The `dc` script will copy your ssh keys from your `~/.ssh` dir into the `php` container.

Start te project and run the tests:

```
./dc up -d
./dc enter
test
```

## Credits

Thanks to [Matiux](https://github.com/matiux) for the Docker images used in the project


  



