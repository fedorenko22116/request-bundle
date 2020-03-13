# LSBProjectRequestBundle

Request bundle created to handle all request related things in a separate module. Highly inspired by Laravel

## Installation

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
$ composer require lsbproject/request-bundle
```

This command requires you to have Composer installed globally, as explained in the installation chapter of the Composer documentation.

### Step 2: Enable the Bundle
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
class TestRequest extends AbstractRequest
{
    public SomeAwesomeClass $fooBar;
}
```

If you are not using PHP 7.4, you can point the class with annotations `@var` or `@PropConverter`

```php
use LSBProject\RequestBundle\Configuration\PropConverter;

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
class TestRequest extends AbstractRequest
{
    public $userId;

    /**
     * @PropConverter("App\Model\SomeAwesomeClass", converter="awesome_converter", options={"userId": "id"})
     */
    public $fooBarBaz;
}
```

### Validation

You can use symfony/validation to validate parameters in request.

```php
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
class TestRequest extends AbstractRequest
{
    private const ADMIN = 0;

    /**
     * @Assert\NotBlank()
     */
    public int $userId;
    public ?string $comment;

    public function validate(): bool
    {
        return $this->userId === self::ADMIN || ($this->comment && preg_match('/super/', $this->comment));
    }

    public function getErrorMessage(): string
    {
        return 'Only admin can use "super" word in a comment text';
    }
}
```

### Additional setting logic

To specify property you also can use setters instead of `public` properties to add some additional logic.

```php
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
use LSBProject\RequestBundle\Configuration\Entity;

class TestRequest extends AbstractRequest
{
    /**
     * @Entity("App\Entity\User", expr="repository.find(user_id)", options={"user_id" = "id"})
     */
    public $user;
}
```

Use options to point aliases from the request to the original parameters names.

## TODO

1) Validation group configuration
2) Possibility to specify where the data storage is (body, request, attributes)
3) Add translation support for `getErrorMessage`
4) Custom naming strategy
