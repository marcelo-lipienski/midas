<?php declare(strict_types = 1);

namespace Midas\Strategies;

use ReflectionClass;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as Handler;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

use Midas\Contracts\AbstractStrategy;

class JWTStrategy extends AbstractStrategy
{
  const HTTP_HEADER_AUTHORIZATION = 'Authorization';
  const BEARER_TOKEN_POSITION = 0;
  const HTTP_HEADER_POSITION = 1;
  const JWT_PAYLOAD_POSITION = 1;

  protected $checkCredentials;

  public function __construct(callable $checkCredentials)
  {
    $this->checkCredentials = $checkCredentials;
  }

  /**
   * @method authorized
   * @return bool 
   */
  public function authorized() : bool
  {
    $credentials = json_decode($this->getCredentials());

    return call_user_func($this->checkCredentials, $credentials);
  }

  protected function getBearer()
  {
    $httpAuthenticationHeaders = $this->getHttpAuthorizationHeaders();
    $bearer = $httpAuthenticationHeaders[self::BEARER_TOKEN_POSITION];

    return explode(' ', $bearer)[self::HTTP_HEADER_POSITION];
  }

  protected function getHttpAuthorizationHeaders() : array
  {
    return $this->request->getHeaders()[self::HTTP_HEADER_AUTHORIZATION];
  }

  protected function getCredentials() : string
  {
    $token = $this->getBearer();
    $credentials = explode('.', $token)[self::JWT_PAYLOAD_POSITION];

    return base64_decode($credentials);
  }
}