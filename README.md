# LSBProjectRequestBundle

```
Caution: bundle will not have updates in favor of new symfony attribute argument resolvers, consider use it instead of this package
```

[![SymfonyInsight](https://insight.symfony.com/projects/0e64da25-252b-4d3f-9752-9ed18f93c9f1/mini.svg)](https://insight.symfony.com/projects/0e64da25-252b-4d3f-9752-9ed18f93c9f1) \
Request bundle created to represent request data as a strict objects.

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
$ composer require lsbproject/request-bundle
```

## Usage

Create a request class and extend AbstractRequest:

```php
use LSBProject\RequestBundle\Request\RequestInterface;

class TestRequest implements RequestInterface
{
    public $fooBar;
}
```

That's all. This will require `foo_bar` parameter to be present in request, query, cookie, head or attribute (All types you can find in `RequestStorage`). \
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
use LSBProject\RequestBundle\Request\RequestInterface;
use App\Model\SomeAwesomeClass;

class TestRequest implements RequestInterface
{
    public SomeAwesomeClass $fooBar;
}
```

If you are not using PHP 7.4, you can point the class with annotations `@var` or `@PropConverter` (attributes also available right now with the same annotation class)

```php
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Request\RequestInterface;

class TestRequest implements RequestInterface
{
    /**
     * @var App\Model\SomeAwesomeClass
     */
    public $fooBar;

    // or

    /**
     * @PropConverter(App\Model\SomeAwesomeClass::class)
     */
    public $fooBarBaz;

    // or

    #[PropConverter(App\Model\SomeAwesomeClass::class)]
    public $fooBarBaz;
}
```
(Be aware that you need to specify full classname with a namespace)

### Configuring property

As you could notice there is a useful annotation `@PropConverter` which is in fact is an adapter to `@ParamConverter` of sensio-framework-bundle. 
Be free to modify any of parameters, as they are working in the same way as in the original one.

```php
use LSBProject\RequestBundle\Request\RequestInterface;
use LSBProject\RequestBundle\Configuration as LSB;

class TestRequest implements RequestInterface
{
    /**
     * @LSB\PropConverter(App\Model\SomeAwesomeClass::class, converter="awesome_converter", options={"mapping": {"user_id": "id"}})
     */
    public $fooBarBaz;
}
```

### Request storage

By default all parameters from body, request, headers, cookies or path will be used. \
To restrict parameter to be located in exact place you can use `@RequestStorage` annotation

```php
use LSBProject\RequestBundle\Request\RequestInterface;
use LSBProject\RequestBundle\Configuration as LSB;

/**
 * @LSB\RequestStorage({LSB\RequestStorage::BODY, LSB\RequestStorage::PATH})
 */
class TestRequest implements RequestInterface
{
    public $fooBaz;

    /**
     * @LSB\RequestStorage({LSB\RequestStorage::BODY})
     */
    public $fooBar;
}
```

From example above you will get `foo_baz` parameter from request body or path, and `foo_bar` parameter exactly from request body. \
There are 5 types of storage: `query`, `body`, `path`, `cookie` and `header`.

### Validation

You can use `symfony/validation` to validate parameters in request.
Install component and use it as usual

```php
use LSBProject\RequestBundle\Request\RequestInterface;

class TestRequest implements RequestInterface
{
    /**
     * @Assert\NotBlank()
     */
    public int $userId;
}
```

### Using mutators

To specify property you also can use setters instead of `public` properties to add some additional logic.

```php
use LSBProject\RequestBundle\Request\RequestInterface;

class TestRequest implements RequestInterface
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
use LSBProject\RequestBundle\Request\RequestInterface;
use App\Entity\User;

class TestRequest implements RequestInterface
{
    /**
     * @LSB\Entity(App\Entity\User::class, expr="repository.find(id)", mapping={"id": "user_id"})
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

final class CamelCaseToSnakeConversion implements NamingConversionInterface
{
    /**
     * {@inheritDoc}
     */
    public function normalize($value)
    {
        return strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($value)) ?: '');
    }

    /**
     * {@inheritDoc}
     */
    public function denormalize($value)
    {
        $camelCasedName = preg_replace_callback('/(^|_|\.)+(.)/', function ($match) {
            return ('.' === $match[1] ? '_' : '') . strtoupper($match[2]);
        }, $value);

        return lcfirst($camelCasedName);
    }
}
```

then you should register it as a service and point it out in the bundle configuration.

```yaml
# ./config/packages/lsb_project_request.yaml

lsb_project_request:
    naming_conversion: my_custom_conversion
```

You can also apply your conversion to the separate object or its part. Just register your conversion
as a service and point its name in `RequestStorage::converter`

### Using DTOs as property

There is also a possibility to specify deeper nested level in the request. To do it, specify special option of `PropConverter::isDto`
for class property. This will prevent standart `ParamConverter` to be applied and will recursively perform `AbstractRequest` converter to the object.

```php
use LSBProject\RequestBundle\Request\RequestInterface;
use LSBProject\RequestBundle\Configuration as LSB;
use Symfony\Component\HttpFoundation\Request;
use App\Request\DTO\Data;

/**
 * @LSB\RequestStorage({@LSB\RequestStorage::BODY})
 */
class JsonRpcRequest extends Request implements RequestInterface
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

To specify an array of objects which should be converted, use `isCollection` property or use typehinting. You can use it in combination \
with `isDto` to specify array of objects to be set. \
Be aware that it's not recommended to use collection for entities as it's very inefficient way of getting data as it \
will perform request to DB.

```php
use LSBProject\RequestBundle\Request\RequestInterface;
use LSBProject\RequestBundle\Configuration as LSB;
use App\Request\DTO\Data;

/**
 * @LSB\RequestStorage({@LSB\RequestStorage::BODY})
 */
class JsonRpcRequest implements RequestInterface
{
    public string $jsonrpc;

    /**
     * 'method' property is already present in a base Request class, so alias should be used
     *
     * @LSB\PropConverter(name="method")
     */
    public string $methodName;

    public int $id;

    /** 
     * @LSB\PropConverter(isDto=true)
     * @var App\Request\DTO\Data[]
     */
    public array $params;
}
```

### Using discriminator field

Sometimes we do not know which object will be returned. To resolve this discriminator mechanism appears in game.

```php
use LSBProject\RequestBundle\Configuration as LSB;
use LSBProject\RequestBundle\Request\RequestInterface;

final class TestDiscriminatedRequest implements RequestInterface
{
    #[LSB\PropConverter(isDto: true)]
    #[LSB\Discriminator(
        field: 'type',
        mapping: [
            'foo' => new LSB\PropConverter(class: DiscriminatorParamsFoo::class, isDto: false),
            'bar' => DiscriminatorParamsBar::class
        ]
    )]
    public DiscriminatorParamsFoo|DiscriminatorParamsBar $discriminated;
}

abstract class AbstractDiscriminatorParams
{
    public string $type;
}

final class DiscriminatorParamsBar extends AbstractDiscriminatorParams
{
    public string $bar;
}

final class DiscriminatorParamsFoo extends AbstractDiscriminatorParams
{
    public string $foo;
}
```

Correspondingly to `field` field in `Discriminator` attribute object will be configured accordingly to mapping.

### Use on a custom objects

There is also possibility to apply LSB converter to the object
not inheriting AbstractRequest. You can use `@LSB\Request` annotation
to point out parameter in controller.

```php
    use LSBProject\RequestBundle\Configuration as LSB;

    //...

    /**
     * @Route("/foo")
     * @LSB\Request("params", storage=@LSB\RequestStorage({@LSB\RequestStorage::HEAD}))
     */
    public function testHeadRequest(TestParamsA $params): Response
    {
        return new Response($params->foo);
    }
```

## Examples

More examples you can find [here](https://github.com/22116/request-bundle/tree/master/tests/E2e)

## Writing documentation

*OpenApi:* https://github.com/22116/request-doc-bundle

## Known issues

* Multiple types such as a mix of classes and arrays are not supported, but you can still use a mix of scalar types
