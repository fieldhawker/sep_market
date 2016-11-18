<?php
namespace App\Services;

use Log;
use Util;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

/**
 * Class LiveDoorService
 */
class LiveDoorService
{

    private $weather_info;
    private $client;

    const WEATHER_PREF         = '130010';  // 東京
    const GET_WEATHER_INFO_URL = 'http://weather.livedoor.com/forecast/webservice/json/v1';

    /**
     * LiveDoorService constructor.
     *
     */
    public function __construct(GuzzleClientInterface $client = null)
    {

        Util::generateLogMessage('START');

        $this->client = $client ?: new Client();

        Util::generateLogMessage('END');

    }


    /**
     * 天気情報をアクセサに設定
     *
     * @return bool
     */
    public function setWeatherData()
    {

        Util::generateLogMessage('START');

        // 天気を取得
        $response_weather = $this->requestWeather();

        // 必要な情報を抽出
        $weather_info = $this->createWeatherInfo($response_weather);

        // アクセサに設定
        $this->setWeatherInfo($weather_info);

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * 天気情報を取得します
     *
     * @return mixed
     * @throws \Exception
     */
    public function requestWeather()
    {

        Util::generateLogMessage('START');

        $res = $this->client->get(self::GET_WEATHER_INFO_URL, [
          'query' => [
            'city' => self::WEATHER_PREF,
          ],
        ]);

        if ($res->getStatusCode() !== 200) {
            throw new \Exception($res->getBody(), $res->getStatusCode());
        }

        $weather = json_decode($res->getBody(), true);

        Log::info("取得した天気情報", $weather);

        Util::generateLogMessage('END');

        return $weather;

    }

    /**
     * @return mixed
     */
    public function getWeatherInfo()
    {
        return $this->weather_info;
    }

    /**
     * @param mixed $weather_info
     */
    public function setWeatherInfo($weather_info)
    {
        $this->weather_info = $weather_info;
    }

    /**
     * @param $weather
     *
     * @return mixed
     */
    public function createWeatherInfo($weather)
    {

        Util::generateLogMessage('START');

        $weather_info['pref']  = $weather['location']['prefecture'];
        $weather_info['label'] = $weather['forecasts'][0]['dateLabel'];
        $weather_info['telop'] = $weather['forecasts'][0]['telop'];

        $is_min = (
          is_array($weather['forecasts'][0]['temperature']['min'])
          &&
          array_key_exists('celsius', $weather['forecasts'][0]['temperature']['min']));

        $is_max = (
          is_array($weather['forecasts'][0]['temperature']['max'])
          &&
          array_key_exists('celsius', $weather['forecasts'][0]['temperature']['max']));

        $weather_info['temp_min'] = ($is_min) ? $weather['forecasts'][0]['temperature']['min']['celsius'] : null;
        $weather_info['temp_max'] = ($is_max) ? $weather['forecasts'][0]['temperature']['max']['celsius'] : null;

        Log::info("出力に使用する天気情報", $weather_info);

        Util::generateLogMessage('END');

        return $weather_info;

    }
}