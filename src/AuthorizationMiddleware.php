<?php declare(strict_types = 1);

namespace Midas;

use ReflectionClass;
use RuntimeException;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Midas\Contracts\StrategyInterface;

class AuthorizationMiddleware implements MiddlewareInterface
{
  protected $strategy;

  public function __construct(StrategyInterface $strategy)
  {
    $this->strategy = $strategy;
  }

  public function process(Request $request, Handler $handler) : ResponseInterface
  {
    $this->strategy->set($request, $handler);
    
    if ($this->strategy->authorized()) {
      return $handler->handle($request);
    }
    
    $response = $handler->handle($request);
    return $response->withStatus(401);
  }

}