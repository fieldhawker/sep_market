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
        
        $query['query'] = $params;

        $res = $this->client->get($url, $query);

        if ($res->getStatusCode() !== 200) {
            throw new \Exception($res->getBody(), $res->getStatusCode());
        }

        $response = json_decode($res->getBody(), true);

        Log::info("response : ", $response);

        return $response;
    }
}