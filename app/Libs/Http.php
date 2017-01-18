<?php
namespace app\Libs;

use Log;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

class Http
{

    private $client;
    
    /**
     * Http constructor.
     *
     * @param GuzzleClientInterface|null $client
     */
    public function __construct(GuzzleClientInterface $client = null)
    {
        $this->client = $client ?: new Client();
    }
    
    /**
     * @param null  $url
     * @param array $params
     *
     * @return bool|mixed
     * @throws \Exception
     */
    public function get($url = null, $params = array())
    {        
        if (is_null($url)) {
            return false;
        }

        $res = $this->client->get($url, $params);

        if ($res->getStatusCode() !== 200) {
            throw new \Exception($res->getBody(), $res->getStatusCode());
        }

        $response = $res->getBody();
        
        Log::info("response : " . $response);

        return $response;
    }

    /**
     * @param null  $url
     * @param array $params
     *
     * @return bool|\Psr\Http\Message\StreamInterface
     * @throws \Exception
     */
    public function postJson($url = null, $params = array())
    {
        if (is_null($url)) {
            return false;
        }

        $body = json_encode($params);
        
        $res = $this->client->post(
          $url,
          [
            'future' => true,
            'headers' => ['Content-Type' => 'application/json'],
            'body' => $body
          ]
        );

        if ($res->getStatusCode() !== 200) {
            throw new \Exception($res->getBody(), $res->getStatusCode());
        }

        $response = $res->getBody();

        Log::info("response : " . $response);

        return $response;
    }
}