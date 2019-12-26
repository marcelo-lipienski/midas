<?php declare(strict_types = 1);

namespace Tests\Strategies;

use PHPUnit\Framework\TestCase;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Midas\Contracts\StrategyInterface;
use Midas\Factories\StrategyFactory;
use Midas\Strategies\JWTStrategy;

use GuzzleHttp\Client;

class JWTStrategyTest extends TestCase
{

  public function testAuthorized() : void
  {

    $checkCredentials = function ($credentials) {
      if ($credentials->user->cpf == '00000000000') {
        return true;
      }

      return false;
    };

    $strategy = StrategyFactory::use(JWTStrategy::class, $checkCredentials);

    $expectedToken = getenv('JWT');

    $request = $this->createMock(Request::class);
    $request->expects($this->once())
      ->method('getHeaders')
      ->willReturn([
        "Authorization" => [ "Bearer $expectedToken" ]
      ]);

    $handler = $this->createMock(Handler::class);

    $strategy->set($request, $handler);

    $isAuthenticated = $strategy->authorized();

    $this->assertTrue($strategy instanceof StrategyInterface);
    $this->assertTrue($isAuthenticated);
  }
}