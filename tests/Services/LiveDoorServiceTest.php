<?php

use App\Services\LiveDoorService;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LiveDoorServiceTest extends TestCase
{

    private $service;


    /**
     *
     */
    function setUp()
    {

        parent::setUp();

        $this->service = New LiveDoorService();

    }

    /**
     *  天気情報の取得に成功しているか
     *
     * @return void
     */
    public function testGetWeather()
    {
        $this->service->setWeatherData();
        
        $weather_info = $this->service->getWeatherInfo();
        
        $this->assertGreaterThan(0, mb_strlen($weather_info["pref"]));
        $this->assertGreaterThan(0, mb_strlen($weather_info["label"]));
        $this->assertGreaterThan(0, mb_strlen($weather_info["telop"]));
        
    }
    
}
