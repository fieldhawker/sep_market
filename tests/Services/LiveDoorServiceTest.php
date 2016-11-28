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
     *
     */
    public function testSetWeatherData()
    {
        $this->service->setWeatherData();

        $response = $this->service->getWeatherInfo();

        $this->assertArrayHasKey('pref', $response);
        $this->assertArrayHasKey('label', $response);
        $this->assertArrayHasKey('telop', $response);
        $this->assertArrayHasKey('temp_min', $response);
        $this->assertArrayHasKey('temp_max', $response);

    }

    /**
     *
     */
    public function testRequestWeather()
    {
        $response = $this->service->requestWeather();

        $comparison = '{"pinpointLocations":[{"link":"http://weather.livedoor.com/area/forecast/1310100","name":"千代田区"},{"link":"http://weather.livedoor.com/area/forecast/1310200","name":"中央区"},{"link":"http://weather.livedoor.com/area/forecast/1310300","name":"港区"},{"link":"http://weather.livedoor.com/area/forecast/1310400","name":"新宿区"},{"link":"http://weather.livedoor.com/area/forecast/1310500","name":"文京区"},{"link":"http://weather.livedoor.com/area/forecast/1310600","name":"台東区"},{"link":"http://weather.livedoor.com/area/forecast/1310700","name":"墨田区"},{"link":"http://weather.livedoor.com/area/forecast/1310800","name":"江東区"},{"link":"http://weather.livedoor.com/area/forecast/1310900","name":"品川区"},{"link":"http://weather.livedoor.com/area/forecast/1311000","name":"目黒区"},{"link":"http://weather.livedoor.com/area/forecast/1311100","name":"大田区"},{"link":"http://weather.livedoor.com/area/forecast/1311200","name":"世田谷区"},{"link":"http://weather.livedoor.com/area/forecast/1311300","name":"渋谷区"},{"link":"http://weather.livedoor.com/area/forecast/1311400","name":"中野区"},{"link":"http://weather.livedoor.com/area/forecast/1311500","name":"杉並区"},{"link":"http://weather.livedoor.com/area/forecast/1311600","name":"豊島区"},{"link":"http://weather.livedoor.com/area/forecast/1311700","name":"北区"},{"link":"http://weather.livedoor.com/area/forecast/1311800","name":"荒川区"},{"link":"http://weather.livedoor.com/area/forecast/1311900","name":"板橋区"},{"link":"http://weather.livedoor.com/area/forecast/1312000","name":"練馬区"},{"link":"http://weather.livedoor.com/area/forecast/1312100","name":"足立区"},{"link":"http://weather.livedoor.com/area/forecast/1312200","name":"葛飾区"},{"link":"http://weather.livedoor.com/area/forecast/1312300","name":"江戸川区"},{"link":"http://weather.livedoor.com/area/forecast/1320100","name":"八王子市"},{"link":"http://weather.livedoor.com/area/forecast/1320200","name":"立川市"},{"link":"http://weather.livedoor.com/area/forecast/1320300","name":"武蔵野市"},{"link":"http://weather.livedoor.com/area/forecast/1320400","name":"三鷹市"},{"link":"http://weather.livedoor.com/area/forecast/1320500","name":"青梅市"},{"link":"http://weather.livedoor.com/area/forecast/1320600","name":"府中市"},{"link":"http://weather.livedoor.com/area/forecast/1320700","name":"昭島市"},{"link":"http://weather.livedoor.com/area/forecast/1320800","name":"調布市"},{"link":"http://weather.livedoor.com/area/forecast/1320900","name":"町田市"},{"link":"http://weather.livedoor.com/area/forecast/1321000","name":"小金井市"},{"link":"http://weather.livedoor.com/area/forecast/1321100","name":"小平市"},{"link":"http://weather.livedoor.com/area/forecast/1321200","name":"日野市"},{"link":"http://weather.livedoor.com/area/forecast/1321300","name":"東村山市"},{"link":"http://weather.livedoor.com/area/forecast/1321400","name":"国分寺市"},{"link":"http://weather.livedoor.com/area/forecast/1321500","name":"国立市"},{"link":"http://weather.livedoor.com/area/forecast/1321800","name":"福生市"},{"link":"http://weather.livedoor.com/area/forecast/1321900","name":"狛江市"},{"link":"http://weather.livedoor.com/area/forecast/1322000","name":"東大和市"},{"link":"http://weather.livedoor.com/area/forecast/1322100","name":"清瀬市"},{"link":"http://weather.livedoor.com/area/forecast/1322200","name":"東久留米市"},{"link":"http://weather.livedoor.com/area/forecast/1322300","name":"武蔵村山市"},{"link":"http://weather.livedoor.com/area/forecast/1322400","name":"多摩市"},{"link":"http://weather.livedoor.com/area/forecast/1322500","name":"稲城市"},{"link":"http://weather.livedoor.com/area/forecast/1322700","name":"羽村市"},{"link":"http://weather.livedoor.com/area/forecast/1322800","name":"あきる野市"},{"link":"http://weather.livedoor.com/area/forecast/1322900","name":"西東京市"},{"link":"http://weather.livedoor.com/area/forecast/1330300","name":"瑞穂町"},{"link":"http://weather.livedoor.com/area/forecast/1330500","name":"日の出町"},{"link":"http://weather.livedoor.com/area/forecast/1330700","name":"檜原村"},{"link":"http://weather.livedoor.com/area/forecast/1330800","name":"奥多摩町"}],"link":"http://weather.livedoor.com/area/forecast/130010","forecasts":[{"dateLabel":"今日","telop":"晴のち曇","date":"2016-11-28","temperature":{"min":null,"max":null},"image":{"width":50,"url":"http://weather.livedoor.com/img/icon/5.gif","title":"晴のち曇","height":31}},{"dateLabel":"明日","telop":"晴時々曇","date":"2016-11-29","temperature":{"min":{"celsius":"6","fahrenheit":"42.8"},"max":{"celsius":"14","fahrenheit":"57.2"}},"image":{"width":50,"url":"http://weather.livedoor.com/img/icon/2.gif","title":"晴時々曇","height":31}},{"dateLabel":"明後日","telop":"晴のち曇","date":"2016-11-30","temperature":{"min":null,"max":null},"image":{"width":50,"url":"http://weather.livedoor.com/img/icon/5.gif","title":"晴のち曇","height":31}}],"location":{"city":"東京","area":"関東","prefecture":"東京都"},"publicTime":"2016-11-28T17:00:00+0900","copyright":{"provider":[{"link":"http://tenki.jp/","name":"日本気象協会"}],"link":"http://weather.livedoor.com/","title":"(C) LINE Corporation","image":{"width":118,"link":"http://weather.livedoor.com/","url":"http://weather.livedoor.com/img/cmn/livedoor.gif","title":"livedoor 天気情報","height":26}},"title":"東京都 東京 の天気","description":{"text":" 日本付近は、高気圧に覆われ、冬型の気圧配置となっています。\n\n【関東甲信地方】\n 関東甲信地方は、晴れまたは曇りとなっています。\n\n 28日は、高気圧に覆われ、おおむね晴れますが、長野県北部や関東地方\n北部の山沿いでは雨や雪の降る所があるでしょう。また、伊豆諸島や関東南\n部沿岸部では湿った空気の影響で雲が広がるでしょう。\n\n 29日は、引き続き、高気圧に覆われ、おおむね晴れますが、長野県北部\nや関東地方北部の山沿いでは雪の降る所がある見込みです。また、伊豆諸島\nでは湿った空気の影響で雲が広がるでしょう。\n\n 関東近海では、29日にかけて波が高いでしょう。船舶は高波に注意して\nください。\n\n【東京地方】\n 28日は、曇りでしょう。\n 29日は、晴れ朝晩曇りとなる見込みです。","publicTime":"2016-11-28T20:30:00+0900"}}';
        $comparison = json_decode($comparison, true);

        $this->assertArrayHasKey('pinpointLocations', $response);
        $this->assertArrayHasKey('prefecture', $response['location']);
        $this->assertArrayHasKey('dateLabel', $response['forecasts'][0]);
        $this->assertArrayHasKey('telop', $response['forecasts'][0]);
        $this->assertArrayHasKey('min', $response['forecasts'][0]['temperature']);
        $this->assertArrayHasKey('max', $response['forecasts'][0]['temperature']);

    }

    /**
     *
     */
    public function testCreateWeatherInfo()
    {
        $comparison = '{"pinpointLocations":[{"link":"http://weather.livedoor.com/area/forecast/1310100","name":"千代田区"},{"link":"http://weather.livedoor.com/area/forecast/1310200","name":"中央区"},{"link":"http://weather.livedoor.com/area/forecast/1310300","name":"港区"},{"link":"http://weather.livedoor.com/area/forecast/1310400","name":"新宿区"},{"link":"http://weather.livedoor.com/area/forecast/1310500","name":"文京区"},{"link":"http://weather.livedoor.com/area/forecast/1310600","name":"台東区"},{"link":"http://weather.livedoor.com/area/forecast/1310700","name":"墨田区"},{"link":"http://weather.livedoor.com/area/forecast/1310800","name":"江東区"},{"link":"http://weather.livedoor.com/area/forecast/1310900","name":"品川区"},{"link":"http://weather.livedoor.com/area/forecast/1311000","name":"目黒区"},{"link":"http://weather.livedoor.com/area/forecast/1311100","name":"大田区"},{"link":"http://weather.livedoor.com/area/forecast/1311200","name":"世田谷区"},{"link":"http://weather.livedoor.com/area/forecast/1311300","name":"渋谷区"},{"link":"http://weather.livedoor.com/area/forecast/1311400","name":"中野区"},{"link":"http://weather.livedoor.com/area/forecast/1311500","name":"杉並区"},{"link":"http://weather.livedoor.com/area/forecast/1311600","name":"豊島区"},{"link":"http://weather.livedoor.com/area/forecast/1311700","name":"北区"},{"link":"http://weather.livedoor.com/area/forecast/1311800","name":"荒川区"},{"link":"http://weather.livedoor.com/area/forecast/1311900","name":"板橋区"},{"link":"http://weather.livedoor.com/area/forecast/1312000","name":"練馬区"},{"link":"http://weather.livedoor.com/area/forecast/1312100","name":"足立区"},{"link":"http://weather.livedoor.com/area/forecast/1312200","name":"葛飾区"},{"link":"http://weather.livedoor.com/area/forecast/1312300","name":"江戸川区"},{"link":"http://weather.livedoor.com/area/forecast/1320100","name":"八王子市"},{"link":"http://weather.livedoor.com/area/forecast/1320200","name":"立川市"},{"link":"http://weather.livedoor.com/area/forecast/1320300","name":"武蔵野市"},{"link":"http://weather.livedoor.com/area/forecast/1320400","name":"三鷹市"},{"link":"http://weather.livedoor.com/area/forecast/1320500","name":"青梅市"},{"link":"http://weather.livedoor.com/area/forecast/1320600","name":"府中市"},{"link":"http://weather.livedoor.com/area/forecast/1320700","name":"昭島市"},{"link":"http://weather.livedoor.com/area/forecast/1320800","name":"調布市"},{"link":"http://weather.livedoor.com/area/forecast/1320900","name":"町田市"},{"link":"http://weather.livedoor.com/area/forecast/1321000","name":"小金井市"},{"link":"http://weather.livedoor.com/area/forecast/1321100","name":"小平市"},{"link":"http://weather.livedoor.com/area/forecast/1321200","name":"日野市"},{"link":"http://weather.livedoor.com/area/forecast/1321300","name":"東村山市"},{"link":"http://weather.livedoor.com/area/forecast/1321400","name":"国分寺市"},{"link":"http://weather.livedoor.com/area/forecast/1321500","name":"国立市"},{"link":"http://weather.livedoor.com/area/forecast/1321800","name":"福生市"},{"link":"http://weather.livedoor.com/area/forecast/1321900","name":"狛江市"},{"link":"http://weather.livedoor.com/area/forecast/1322000","name":"東大和市"},{"link":"http://weather.livedoor.com/area/forecast/1322100","name":"清瀬市"},{"link":"http://weather.livedoor.com/area/forecast/1322200","name":"東久留米市"},{"link":"http://weather.livedoor.com/area/forecast/1322300","name":"武蔵村山市"},{"link":"http://weather.livedoor.com/area/forecast/1322400","name":"多摩市"},{"link":"http://weather.livedoor.com/area/forecast/1322500","name":"稲城市"},{"link":"http://weather.livedoor.com/area/forecast/1322700","name":"羽村市"},{"link":"http://weather.livedoor.com/area/forecast/1322800","name":"あきる野市"},{"link":"http://weather.livedoor.com/area/forecast/1322900","name":"西東京市"},{"link":"http://weather.livedoor.com/area/forecast/1330300","name":"瑞穂町"},{"link":"http://weather.livedoor.com/area/forecast/1330500","name":"日の出町"},{"link":"http://weather.livedoor.com/area/forecast/1330700","name":"檜原村"},{"link":"http://weather.livedoor.com/area/forecast/1330800","name":"奥多摩町"}],"link":"http://weather.livedoor.com/area/forecast/130010","forecasts":[{"dateLabel":"今日","telop":"晴のち曇","date":"2016-11-28","temperature":{"min":null,"max":null},"image":{"width":50,"url":"http://weather.livedoor.com/img/icon/5.gif","title":"晴のち曇","height":31}},{"dateLabel":"明日","telop":"晴時々曇","date":"2016-11-29","temperature":{"min":{"celsius":"6","fahrenheit":"42.8"},"max":{"celsius":"14","fahrenheit":"57.2"}},"image":{"width":50,"url":"http://weather.livedoor.com/img/icon/2.gif","title":"晴時々曇","height":31}},{"dateLabel":"明後日","telop":"晴のち曇","date":"2016-11-30","temperature":{"min":null,"max":null},"image":{"width":50,"url":"http://weather.livedoor.com/img/icon/5.gif","title":"晴のち曇","height":31}}],"location":{"city":"東京","area":"関東","prefecture":"東京都"},"publicTime":"2016-11-28T17:00:00+0900","copyright":{"provider":[{"link":"http://tenki.jp/","name":"日本気象協会"}],"link":"http://weather.livedoor.com/","title":"(C) LINE Corporation","image":{"width":118,"link":"http://weather.livedoor.com/","url":"http://weather.livedoor.com/img/cmn/livedoor.gif","title":"livedoor 天気情報","height":26}},"title":"東京都 東京 の天気","description":{"text":" 日本付近は、高気圧に覆われ、冬型の気圧配置となっています。\n\n【関東甲信地方】\n 関東甲信地方は、晴れまたは曇りとなっています。\n\n 28日は、高気圧に覆われ、おおむね晴れますが、長野県北部や関東地方\n北部の山沿いでは雨や雪の降る所があるでしょう。また、伊豆諸島や関東南\n部沿岸部では湿った空気の影響で雲が広がるでしょう。\n\n 29日は、引き続き、高気圧に覆われ、おおむね晴れますが、長野県北部\nや関東地方北部の山沿いでは雪の降る所がある見込みです。また、伊豆諸島\nでは湿った空気の影響で雲が広がるでしょう。\n\n 関東近海では、29日にかけて波が高いでしょう。船舶は高波に注意して\nください。\n\n【東京地方】\n 28日は、曇りでしょう。\n 29日は、晴れ朝晩曇りとなる見込みです。","publicTime":"2016-11-28T20:30:00+0900"}}';
        $comparison = json_decode($comparison, true);

        $response = $this->service->createWeatherInfo($comparison);

        $this->assertArrayHasKey('pref', $response);
        $this->assertArrayHasKey('label', $response);
        $this->assertArrayHasKey('telop', $response);
        $this->assertArrayHasKey('temp_min', $response);
        $this->assertArrayHasKey('temp_max', $response);
    }
}
