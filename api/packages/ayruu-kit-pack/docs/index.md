Usage
=====

## 1. Services

### Printers

##### Decorator

This service will **add** a new `print` method to your default logger object.

> Warning: in order for this to work, your Logger must implement the interface `LoggerInterface`
>   AND the `LoggerInterface` should be mapped to a service in your `services.yaml` file.

Inject the `printer.logger.decorator` as your logger in order to use it.

Example:

```php
<?php

class Foo
{
    public function __construct(private PrinterLoggerDecorator $logger)
    {
    }
    
    public function bar()
    {
        $this->logger->info('I can log !'); // > the logger will work normally ... 
        $this->logger->print('... and I can print !'); // > now the decorator will echo "..." to the console
    }
}
```

##### Adapter

This service will **replace** all logs by console prints.
Inject the `printer.logger.adapter` as your logger in order to use it.

Example:

```php
<?php

class Foo
{
    public function __construct(private PrinterLoggerAdapter $logger)
    {
    }
    
    public function bar()
    {
        $this->logger->info('It cannot log ... but I can print !'); // > the adapter will echo "..." to the console
    }
}
```

## 2. Helpers

> WIP

## 3. Traits

##### AdapterTrait

This trait will make your class an "adapter-like" one ; for it to work you'll need to:
- have a `protected Adapted $parent` class's property
- have a `public const PARENT = Adapted::class` constant

Example:
```php
<?php

// the adapted class
final class Identity
{
    private $name = 'Aleksandr';

    public function getName(): string
    {
        return $this->name;
    }
    
    public function getFullName(): string
    {
        throw new \Exception('There is no such thing !');
    }
    
    public static function getClassName(): string
    {
        return static::class;
    }
}

// the adapter
/**
 * @property string $name
 *
 * @method        string getName()
 * @method static string notExistingStaticMethod()
 * @method static string getClassName()
 */
final class User extends SymfonyUser 
{
    use AdapterTrait;
    public const PARENT = Identity::class;

    public function __construct(private Identity $parent)
    {
    }
    
    public function getFullName(): string
    {
        return 'My fullname is: ' . $this->getName();
    }
}

// your client code
$user = new User(new Identity()); // > note that you should dependency injection, this is just for illustration
$user->getFullname(); // > this will return "My fullname is: Aleksandr"
User::getClassName(); // > this will return "User::class"
```

As it can be hard to explain, you can see the unit tests to further understanding. 

##### LogAwareTrait

This trait will:
- inject your default logger into your class's constructor
- add a `setLogger` method to your class
- log any kind of invalid method call in your class

##### ValuableTrait

This trait can be added to any kind of `BackedEnum`. It will add a `values` method, which return all values from your enums.

Example:
```php
<?php

enum DummyBackedEnum: string
{
    use ValueableTrait; // > use it like this

    case JSON = 'json';
    case HTML = 'html';
}

// > then call it like that
DummyBackedEnum::values(); // > will return "[DummyBackedEnum::JSON->value, DummyBackedEnum::HTML->value]"
```
