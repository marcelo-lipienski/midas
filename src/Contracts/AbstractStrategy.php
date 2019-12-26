<?php declare(strict_types = 1);

namespace Midas\Contracts;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Midas\Contracts\StrategyInterface;

abstract class AbstractStrategy implements StrategyInterface
{

  protected $request;

  protected $handler;

  abstract public function __construct(callable $checkCredentials);

  public function set(Request $request, Handler $handler) : void
  {
    $this->request = $request;
    $this->handler = $handler;
  }

  public function getRequest() : Request
  {
    return $this->request;
  }

  public function getHandler() : Handler
  {
    return $this->handler;
  }

  abstract public function authorized() : bool;

}