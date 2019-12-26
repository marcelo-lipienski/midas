<?php declare(strict_types = 1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Midas\Middleware;
use Midas\Strategies\JWTStrategy;

class MiddlewareTest extends TestCase
{

  public function testStrategy() : void
  {
    $request = $this->createMock(Request::class);
    $handler = $this->createMock(Handler::class);

    $middleware = new Middleware(JWTStrategy::class);

    $this->assertTrue($middleware instanceof MiddlewareInterface);
  }

  public function testProcess() : void
  {
    $expectedToken = getenv('JWT');

    $request = $this->createMock(Request::class);
    $request->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
              "Authorization" => "Bearer $expectedToken"
            ]);

    $handler = $this->createMock(Handler::class);

    $middleware = new Middleware(JWTStrategy::class);

    $response = $middleware->process($request, $handler);

    $this->assertTrue($response instanceof ResponseInterface);
  }

  public function testAuthorization() : void
  {
    $expectedToken = getenv('JWT');

    $request = $this->createMock(Request::class);
    $request->expects($this->once())
            ->method('getHeaders')
            ->willReturn([
              "Authorization" => "Bearer $expectedToken"
            ]);

    $handler = $this->createMock(Handler::class);

    $middleware = new Middleware(JWTStrategy::class);

    $expectsResponseInterface = $middleware->process($request, $handler);
    $this->assertTrue($expectsResponseInterface instanceof ResponseInterface);
  }
}