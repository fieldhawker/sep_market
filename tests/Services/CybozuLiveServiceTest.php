<?php

use App\Services\CybozuLiveService;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CybozuLiveServiceTest extends TestCase
{

    private $service;

    /**
     * @internal param CybozuLiveService $cybozu
     */
    function setUp()
    {

        parent::setUp();

        $this->service = New CybozuLiveService();

    }

    /**
     *  アクセストークンの取得に成功しているか
     *
     * @return void
     */
    public function testRequestAccessToken()
    {
        $this->service->requestAccessToken();

        $token = $this->service->getAccessTokenInfo();

        $this->assertGreaterThan(0, mb_strlen($token["oauth_token"]));
        $this->assertGreaterThan(0, mb_strlen($token["oauth_token_secret"]));
    }

}
