<?php declare(strict_types = 1);

namespace Midas\Factories;

use ReflectionClass;
use RuntimeException;

use Midas\Contracts\StrategyInterface;

class StrategyFactory
{

  private function __construct() {}

  public static function use(string $strategy, callable $checkCredentials) : StrategyInterface
  {
    $class = new ReflectionClass($strategy);

    if (!$class->implementsInterface(StrategyInterface::class)) {
      throw new RuntimeException("Given strategy doesn't implement StrategyInterface");
    }

    return new $strategy($checkCredentials);
  }

}