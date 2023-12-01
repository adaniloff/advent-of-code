# Development tools

The purpose of this directory is to provide tools for our dev/test environments.
The classes in this directory **should never** bo loaded in a production environment !

### Folder syntax tree

It should be like the following:
- dev *- the main directory*
    - lib-dev *- the name of the library suffixed with -dev*
        - src   *- contains dev/tests code*
        - tests *- contains tests about the library code*

### composer.json registering

You must register the namespace under prod & dev environments with respectively composer's `autoload` (for src) and `autoload-dev` (for tests).
See [the example](./README.md#Example) for registration examples.

### Documentations

See the documentation relative to each subpackage:
- no documentation exists at this point !

### Example

Let's say we want to create a small library to help to generate custom UUIDs in our main project tests.

We could then do the following:

##### The directory structure

- dev
    - ayruu-dev
        - src
            - Factory
                - UuidFactoryGeneratorDevTool.php
        - tests
            - Factory
                - UuidFactoryGeneratorDevToolTest.php

#### A client code sample

```php
<?php

# We are in the project main client test suite...
namespace App\Tests\Factory;

# ... and we want to use a developer tool "UuidFactoryGeneratorDevTool"
use Dev\Ayruu\App\Factory\UuidFactoryGeneratorDevTool;

final class FooFactoryTest
{
    protected function setUp(): void
    {
        $this->factory = new UuidFactoryGeneratorDevTool();
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
    "autoload-dev": {
        "psr-4": {
            "App\\": "src/",
            "Dev\\Ayruu\\App\\": "dev/ayruu-dev/src", // add this line
            "Dev\\Ayruu\\Tests\\": "dev/ayruu-dev/tests" // add this line
        }
    }
}
```

Then run `just comp cc && just comp dump-a`.

##### Enjoy

We can now use our `FooFactory` in our test code !
