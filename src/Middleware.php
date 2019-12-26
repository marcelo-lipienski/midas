<?php declare(strict_types = 1);

namespace Midas;

use ReflectionClass;
use RuntimeException;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Midas\Contracts\StrategyInterface;

class Middleware implements MiddlewareInterface
{

  protected $strategy;

  public function __construct(string $strategy)
  {
    $class = new ReflectionClass($strategy);

    if (!$class->implementsInterface(StrategyInterface::class)) {
      throw new RuntimeException("Given strategy doesn't implement StrategyInterface");
    }

    $this->strategy = $strategy;
  }

  public function process(Request $request, Handler $handler) : ResponseInterface
  {
    $this->strategy = new $this->strategy($request, $handler);
    
    if ($this->strategy->authorized()) {
      return $handler->handle($request);
    }
    
    $response = $handler->handle($request);
    return $response->withStatus(401);
  }

}