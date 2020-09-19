# LSBProjectRequestBundle
[![SymfonyInsight](https://insight.symfony.com/projects/0e64da25-252b-4d3f-9752-9ed18f93c9f1/mini.svg)](https://insight.symfony.com/projects/0e64da25-252b-4d3f-9752-9ed18f93c9f1) \
Request bundle created to represent request data as a strict objects.

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
$ composer require lsbproject/request-bundle
```

This command requires you to have Composer installed globally, as explained in the installation chapter of the Composer documentation.

### Step 2: Enable the Bundle (If composer flex is not installed)
Then, enable the bundle by adding it to the list of registered bundles in the config/bundles.php file of your project:

```php
// config/bundles.php

return [
    // ...
    LSBProject\RequestBundle\LSBProjectRequestBundle::class => ['all' => true],
];
```

## Usage

Create a request class and extend AbstractRequest:

```php
use LSBProject\RequestBundle\Request\AbstractRequest;

class TestRequest extends AbstractRequest
{
    public $fooBar;
}
```

That's all. This will require `foo_bar` parameter to be present in request, query or attributes. \
Then use it in controller:
```php
    /**
     * @Route("/test")
     */
    public function test(TestRequest $testRequest): Response
    {
        return new Response($testRequest->fooBar);
    }
```

### Using objects

Also this bundle supports loading objects like you usually do with `@ParamConverter`. All ParamConverts will be performed to the property.

```php
use LSBProject\RequestBundle\Request\AbstractRequest;
use App\Model\SomeAwesomeClass;

class TestRequest extends AbstractRequest
{
    public SomeAwesomeClass $fooBar;
}
```

If you are not using PHP 7.4, you can point the class with annotations `@var` or `@PropConverter`

```php
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Request\AbstractRequest;

class TestRequest extends AbstractRequest
{
    /**
     * @var App\Model\SomeAwesomeClass
     */
    public $fooBar;
    
    // or
    
    /**
     * @PropConverter("App\Model\SomeAwesomeClass")
     */
    public $fooBarBaz;
}
```
(Be aware that you need to specify full classname with a namespace)

### Configuring property

As you could notice there is a useful annotation `@PropConverter` which is in fact is an adapter to `@ParamConverter` of sensio-framework-bundle. 
Be free to modify any of parameters, as they are working in the same way as in the original one.

```php
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Configuration as LSB;

class TestRequest extends AbstractRequest
{
    /**
     * @LSB\PropConverter("App\Model\SomeAwesomeClass", converter="awesome_converter", options={"mapping": {"user_id": "id"}})
     */
    public $fooBarBaz;
}
```

### Request storage

By default all parameters from body, request, headers or path attributes will be used. \
To restrict parameter to be located in exact place you can use `@RequestStorage` annotation

```php
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Configuration\RequestStorage;
use LSBProject\RequestBundle\Configuration as LSB;

/**
 * @RequestStorage({@LSB\RequestStorage::BODY, @LSB\RequestStorage::ATTR})
 */
class TestRequest extends AbstractRequest
{
    public $fooBaz;

    /**
     * @LSB\RequestStorage({@LSB\RequestStorage::BODY})
     */
    public $fooBar;
}
```

From example above you will get `foo_baz` parameter from request body or path, and `foo_bar` parameter exactly from request body. \
There are 3 types of storage to be set: `query`, `body`, `attributes`.

### Validation

You can use `symfony/validation` to validate parameters in request.
Install component and use it as usual

```php
use LSBProject\RequestBundle\Request\AbstractRequest;

class TestRequest extends AbstractRequest
{
    /**
     * @Assert\NotBlank()
     */
    public int $userId;
}
```

For a complex request validation there are an optional methods `validate`, `getErrorMessage`.

```php
use LSBProject\RequestBundle\Request\AbstractRequest;
use Psr\Container\ContainerInterface;

class TestRequest extends AbstractRequest
{
    private const ADMIN = 0;

    /**
     * @Assert\NotBlank()
     */
    public int $userId;
    public ?string $comment;

    public function validate(ContainerInterface $container): bool
    {
        return $this->userId === self::ADMIN || ($this->comment && preg_match('/super/', $this->comment));
    }

    public function getErrorMessage(): string
    {
        return 'Only admin can use "super" word in a comment text';
    }
}
```

If translation component is installed, it will be performed to the message

### Additional setting logic

To specify property you also can use setters instead of `public` properties to add some additional logic.

```php
use LSBProject\RequestBundle\Request\AbstractRequest;

class TestRequest extends AbstractRequest
{
    private string $comment;

    public function setComment(string $comment): void
    {
        $this->comment = trim($comment);
    }
}
```

### Working with entities

There is an annotation `@Entity` which is almost equal to the sensio annotation.

```php
use LSBProject\RequestBundle\Configuration as LSB;
use LSBProject\RequestBundle\Request\AbstractRequest;
use App\Entity\User;

class TestRequest extends AbstractRequest
{
    /**
     * @LSB\Entity("App\Entity\User", expr="repository.find(id)", mapping={"id": "user_id"})
     */
    public $userA;
    
    // or

    /**
     * @LSB\Entity(options={"id"="user_id"})
     */
    public User $userB;

    // or

    /**
     * @LSB\Entity(options={"mapping": {"user_id": "id"}})
     */
    public User $userC;    
}
```

Use `mapping` property to point aliases from the request to the original parameters names.

### Custom naming conversion

By default all properties will be converter to snake_case style. You can change this behaviour 
by creating a class which implements `LSBProject\RequestBundle\Util\NamingConversion\NamingConversionInterface`

```php
<?php

namespace LSBProject\RequestBundle\Util\NamingConversion;

class SnakeConversion implements NamingConversionInterface
{
    /**
     * {@inheritDoc}
     */
    public function convert($value)
    {
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($value)) ?: '');
    }
}
```

then you should register it as a service and point it out in the bundle configuration

```yaml
# ./config/packages/lsb_project_request.yaml

lsb_project_request:
    naming_conversion: my_custom_conversion
```

### Using DTOs as property

There is also a possibility to specify deeper nested level in the request. To do it, specify special option of `PropConverter::isDto`
for class property. This will recursively perform AbstractRequest converter to the object.

```php
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Configuration as LSB;
use App\Request\DTO\Data;

/**
 * @LSB\RequestStorage({@LSB\RequestStorage::BODY})
 */
class JsonRpcRequest extends AbstractRequest
{
    public string $jsonrpc;

    /**
     * 'method' property is already present in a base Request class, so alias should be used
     *
     * @LSB\PropConverter(name="method")
     */
    public string $methodName;

    public int $id;

    /** @LSB\PropConverter(isDto=true) */
    public Data $params;
}
```

### Using Collections

To specify an array of objects which should be converted, use `isCollection` property. You can use it in combination \
with `isDto` to specify array of objects to be set. \
Be aware that it's not recommended to use collection for entities as it's very inefficient way of getting data as it \
will perform request to DB.

```php
use LSBProject\RequestBundle\Request\AbstractRequest;
use LSBProject\RequestBundle\Configuration as LSB;
use App\Request\DTO\Data;

/**
 * @LSB\RequestStorage({@LSB\RequestStorage::BODY})
 */
class JsonRpcRequest extends AbstractRequest
{
    public string $jsonrpc;

    /**
     * 'method' property is already present in a base Request class, so alias should be used
     *
     * @LSB\PropConverter(name="method")
     */
    public string $methodName;

    public int $id;

    /** @LSB\PropConverter("App\Request\DTO\Data", isDto=true, isCollection=true) */
    public array $params;
}
```

### Use on a custom objects

There is also possibility to apply LSB converter to the object
not inheriting AbstractRequest. You can use `@LSB\Request` annotation
to point out parameter in controller.

```php
    use LSBProject\RequestBundle\Configuration as LSB;

    //...

    /**
     * @Route("/foo")
     * @LSB\Request("params", sources={LSB\RequestStorage::HEAD})
     */
    public function testHeadRequest(TestParamsA $params): Response
    {
        return new Response($params->foo);
    }
```

## Examples

More examples you can find [here](https://github.com/22116/request-bundle/tree/master/tests/E2e)
