<?php declare(strict_types = 1);

namespace Midas\Strategies;

use ReflectionClass;
use Composer\Autoload\ClassLoader;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use Dotenv\Dotenv;

use Midas\Contracts\StrategyInterface;

class JWTStrategy implements StrategyInterface
{
  const HTTP_HEADER_AUTHORIZATION = 'Authorization';
  const HTTP_HEADER_POSITION = 1;
  const JWT_PAYLOAD_POSITION = 1;

  protected $request;
  protected $handler;

  public function __construct(Request $request, Handler $handler)
  {
    $this->request = $request;
    $this->handler = $handler;

    $reflection = new ReflectionClass(ClassLoader::class);
    $vendorDir = dirname($reflection->getFileName(), 3);

    $dotenv = Dotenv::create($vendorDir);
    $dotenv->load();
  }

  /**
   * @method authorized
   * @return bool 
   */
  public function authorized() : bool
  {
    $credentials = json_decode($this->getCredentials());

    $client = new Client;

    try {
      $response = $client->request(
        'GET',
        getenv('AUTH_ENDPOINT'),
        [
          'query' => [
            'cpf' => $credentials->user->cpf
          ]
        ]
      );
    } catch (ClientException $e) {
      // 401 unauthorized response from liber-auth.
      return false;
    }

    // yay! user has permission.
    return true;
  }

  protected function getToken() : string
  {
    $header = $this->request->getHeaders()[self::HTTP_HEADER_AUTHORIZATION];
    return explode(' ', $header)[self::HTTP_HEADER_POSITION];
  }

  protected function getCredentials() : string
  {
    $token = $this->getToken();
    $credentials = explode('.', $token)[self::JWT_PAYLOAD_POSITION];

    return base64_decode($credentials);
  }

  protected function authorize() : bool
  {

  }
}