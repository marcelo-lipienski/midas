<?php declare(strict_types = 1);

namespace Tests;

use PHPUnit\Framework\TestCase;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Midas\AuthorizationMiddleware;
use Midas\Factories\StrategyFactory;
use Midas\Strategies\JWTStrategy;

class AuthorizationMiddlewareTest extends TestCase
{

  protected $request;
  protected $handler;
  protected $strategy;

  public function setUp() : void
  {
    $expectedToken = getenv('JWT');

    $this->request = $this->createMock(Request::class);

    $this->request->expects($this->once())
      ->method('getHeaders')
      ->willReturn([
        "Authorization" => [ "Bearer $expectedToken" ]
      ]);

    $this->handler = $this->createMock(Handler::class);

    $this->strategy = StrategyFactory::use(
      JWTStrategy::class,
      function($credentials) {
        return true;
      }
    );
  }

  public function testProcess() : void
  {
    $expectedToken = getenv('JWT');

    $middleware = new AuthorizationMiddleware($this->strategy);

    $response = $middleware->process($this->request, $this->handler);

    $this->assertTrue($response instanceof ResponseInterface);
  }

  public function testAuthorization() : void
  {
    $middleware = new AuthorizationMiddleware($this->strategy);

    $expectsResponseInterface = $middleware->process($this->request, $this->handler);
    $this->assertTrue($expectsResponseInterface instanceof ResponseInterface);
  }

  public function tearDown() : void
  {
    $this->request = null;
    $this->handler = null;
    $this->strategy = null;
  }
}