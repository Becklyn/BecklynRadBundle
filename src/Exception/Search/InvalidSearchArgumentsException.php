<?php declare(strict_types=1);

namespace Becklyn\Rad\Exception\Search;

use Becklyn\Rad\Exception\RadException;

final class InvalidSearchArgumentsException extends \InvalidArgumentException implements RadException
{
}
