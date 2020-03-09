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

```
// config/bundles.php

return [
    // ...
    LSBProject\RequestBundle\LSBProjectRequestBundle::class => ['all' => true],
];
```

## Usage

Create a request class and extend AbstractRequest:
```php
<?php declare(strict_types=1);

namespace App\DTO;

use App\DTO\TestDTO;
use LSBProject\RequestBundle\Configuration\Entity;
use LSBProject\RequestBundle\Configuration\PropConverter;
use LSBProject\RequestBundle\Request\AbstractRequest;
use Symfony\Component\Validator\Constraints as Assert;

class TestRequest extends AbstractRequest
{
    /**
     * @Assert\NotBlank()
     */
    public string $fooBar;  // This will require `foo_bar` parameter to be present in request, query or attribute

    public TestDTO $foo;    // This will emit ParamConvert-ers to `$service` property

    /**
     * @var App\DTO\TestDTO
     */
    public $bar;            // This will lead to the same result as in a previous property

    /**
     * @PropConverter("App\DTO\TestDTO", converter="exact.converter")
     */
    public $baz;            // Another way of declaring the property type. Additional parameters can be put here

    /**
     * @Entity("App\Entity\User", expr="repository.find(user_id)", options={"user_id" = "id"})
     */
    public $user;           // Used to get entity from repository. `user_id` is an alias to `id` parameter from request

    private int $qux;       // Setters can be used to specify value

    public function setQux(int $qux): void
    {
        $this->qux = $qux;
    }

    public function getQux(): int
    {
        return $this->qux;
    }

    /**
     * Optional method
     * Sometimes complex validator is needed, such logic can be put here
     */
    public function validate()
    {
        return $this->qux > 10;
    }

    /**
     * Optional method
     * Used to specify exact message from validator
     */
    public function getErrorMessage()
    {
        return 'Qux must be greater than 10';
    }
}

```

Then use it in controller:
```php
<?php declare(strict_types=1);

namespace App\Controller;

use App\DTO\TestRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/")
     */
    public function test(TestRequest $testRequest): Response
    {
        return new Response($testRequest->user->getId());
    }
}

```
