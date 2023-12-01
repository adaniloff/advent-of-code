# Package tools

The purpose of this directory is to ensure separation of concern in our feature(s) development.
Each package should be considered as an individual library.

### Folder syntax tree

It should be like the following:
- packages *- the main directory*
    - lib-pack *- the name of the library suffixed with -pack*
        - src   *- contains production code*
        - tests *- contains tests about the package code*

### composer.json registering

You must register the namespace under prod & dev environments with respectively composer's `autoload` (for src) and `autoload-dev` (for tests).
See [the example](../packages/README.md#Example) for registration examples.

### Documentations

See the documentation relative to each subpackage:
- [ayruu-kit](./ayruu-kit-pack/README.md)

### Example

Let's say we want to create a small library to provide custom UUIDs, both in our application
and in our tests.

We could then do the following:

##### The directory structure

- packages
    - uuid-pack
        - src
            - Factory
                - UuidFactory.php
                - UuidFactoryInterface.php
        - tests
            - Factory
                - UuidFactory.php
                - UuidFactoryInterface.php

#### A client code sample

```php
<?php

# We are in the project main client code...
namespace App\Factory;

# ... and we want to use a packaged "UuidFactoryInterface"
use Package\Uuid\App\Factory\UuidFactoryInterface;

final class FooFactory
{
    public function __construct(private UuidFactoryInterface $factory)
    {
    }
    
    public function create(): Foo
    {
        return new Foo(uuid: $this->factory->generate());
    }
}
```

##### The composer.json update

Registering our namespaces:

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/",
            "Package\\Uuid\\App\\": "packages/uuid-pack/src" // add this line
        }
    },
    "autoload-dev": {
        "psr-4": {
            "App\\": "src/",
            "Package\\Uuid\\Tests\\": "packages/uuid-pack/tests" // add this line
        }
    }
}
```

Then run `just comp cc && just comp dump-a`.

##### Enjoy

We can now use our `UuidFactory` in our client code in `src/` !
