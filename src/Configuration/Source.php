<?php

declare(strict_types=1);

namespace LSBProject\RequestBundle\Configuration;

enum Source
{
    case Header;
    case Body;
    case Query;
    case Path;
    case Cookie;
    case File;
}
