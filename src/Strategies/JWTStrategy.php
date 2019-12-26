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

    // $client = new Client;

    // try {
    //   $response = $client->request(
    //     $config['HTTP_METHOD'],
    //     $config['API_ENDPOINT'],
    //     [
    //       'query' => [
    //         $config[''] => $credentials->user->cpf
    //       ]
    //     ]
    //   );
    // } catch (ClientException $e) {
    //   // 401 unauthorized response from liber-auth.
    //   return false;
    // }

    // // yay! user has permission.
    // return true;
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