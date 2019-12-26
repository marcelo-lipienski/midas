<?php declare(strict_types = 1);

namespace Midas\Contracts;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

interface StrategyInterface
{
  public function __construct(callable $checkCredentials);
  public function set(Request $request, Handler $handler) : void;
  public function getRequest() : Request;
  public function getHandler() : Handler;
  public function authorized() : bool;
}