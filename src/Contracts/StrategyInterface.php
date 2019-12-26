<?php declare(strict_types = 1);

namespace Midas\Contracts;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

interface StrategyInterface
{

  public function __construct(Request $request, Handler $handler);
  public function authorized() : bool;

}