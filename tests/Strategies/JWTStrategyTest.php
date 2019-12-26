<?php declare(strict_types = 1);

namespace Tests\Strategies;

use PHPUnit\Framework\TestCase;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Midas\Contracts\StrategyInterface;
use Midas\Strategies\JWTStrategy;

class JWTStrategyTest extends TestCase
{

  public function testAuthorized() : void
  {
    $expectedToken = getenv('JWT');

    $request = $this->createMock(Request::class);
    $request->expects($this->once())
      ->method('getHeaders')
      ->willReturn([
        "Authorization" => "Bearer $expectedToken"
      ]);

    $handler = $this->createMock(Handler::class);

    $strategy = new JWTStrategy($request, $handler);

    $isAuthenticated = $strategy->authorized();

    $this->assertTrue($strategy instanceof StrategyInterface);
    $this->assertTrue($isAuthenticated);
  }
}