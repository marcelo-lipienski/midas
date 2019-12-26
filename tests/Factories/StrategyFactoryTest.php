<?php declare(strict_types = 1);

namespace Tests\Factories;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use Midas\Contracts\StrategyInterface;
use Midas\Factories\StrategyFactory;
use Midas\Strategies\JWTStrategy;

use PHPUnit\Framework\TestCase;

class StrategyFactoryTest extends TestCase
{

  public function testCanCreateAStrategy()
  {
    $strategy = StrategyFactory::use(JWTStrategy::class);

    $this->assertTrue($strategy instanceof StrategyInterface);
  }

  public function testFactoredStrategyCanSet()
  {
    $request = $this->createMock(Request::class);
    $handler = $this->createMock(Handler::class);

    $strategy = StrategyFactory::use(JWTStrategy::class);

    $strategy->set($request, $handler);

    $this->assertEquals(
      $request,
      $strategy->getRequest()
    );

    $this->assertEquals(
      $handler,
      $strategy->getHandler()
    );
  }

}