<?php
namespace App\Services;

use Log;
use Util;
use GuzzleHttp\Client;

/**
 * Class LiveDoorService
 */
class LiveDoorService
{

    private $client;
    private $weather_info;
    
    const WEATHER_PREF         = '130010';  // 東京
    const GET_WEATHER_INFO_URL = 'http://weather.livedoor.com/forecast/webservice/json/v1';

    /**
     * LiveDoorService constructor.
     *
     */
    public function __construct()
    {

        Util::generateLogMessage('START');


        Util::generateLogMessage('END');

    }


    /**
     * 興味深い記事をサイボウズLIVEに投稿する
     *
     * @return bool
     */
    public function setWeatherData()
    {

        Util::generateLogMessage('START');

        // 天気を取得
        $this->requestWeather();

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * 天気を取得する
     *
     * @return bool
     */
    public function requestWeather()
    {

        Util::generateLogMessage('START');

        $client = new Client();
        $res    = $client->get(self::GET_WEATHER_INFO_URL, [
          'query' => [
            'city' => self::WEATHER_PREF,
          ],
        ]);

        $weather = json_decode($res->getBody(), true);

        $weather_info['pref']     = $weather['location']['prefecture'];
        $weather_info['label']    = $weather['forecasts'][0]['dateLabel'];
        $weather_info['telop']    = $weather['forecasts'][0]['telop'];

        $weather_info['temp_min'] =  (array_key_exists('celsius', $weather['forecasts'][0]['temperature']['min'])) 
            ? $weather['forecasts'][0]['temperature']['min']['celsius'] : null;

        $weather_info['temp_max'] =  (array_key_exists('celsius', $weather['forecasts'][0]['temperature']['max']))
          ? $weather['forecasts'][0]['temperature']['max']['celsius'] : null;

        Log::info("取得した天気情報", $weather_info);

        $this->setWeatherInfo($weather_info);

        Util::generateLogMessage('END');

        return true;

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
}