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

//        $user = [
//          'x_auth_username' => env('CYBOZULIVE_USER_NAME_DEV'),
//          'x_auth_password' => env('CYBOZULIVE_PASSWORD_DEV'),
//        ];
//        $this->service->setUser($user);
//
//        $this->service->requestAccessToken();
//
//        $token = $this->service->getAccessTokenInfo();
//
//        $this->assertGreaterThan(0, mb_strlen($token["oauth_token"]));
//        $this->assertGreaterThan(0, mb_strlen($token["oauth_token_secret"]));
//
//        /// 
//
//        $user = [
//          'x_auth_username' => 'aaaaaaaaaaaaaaaaaaa',
//          'x_auth_password' => 'aaaaaaaaaaaaaaaaaaa',
//        ];
//        $this->service->setUser($user);
//
//        try {
//            $this->service->requestAccessToken();
//            $this->fail('例外発生なし');
//        } catch (Exception $e) {
//            // エラーコードでの比較
//            $this->assertEquals(401, $e->getCode());
//        };

    }


    /**
     * 興味深い記事の投稿に成功するか
     *
     */
    public function testPostInterestingArticle()
    {
        $user = [
          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
          //          'x_auth_username' => env('CYBOZULIVE_USER_NAME_DEV'),
          //          'x_auth_password' => env('CYBOZULIVE_PASSWORD_DEV'),
        ];

        $this->service->setUser($user);
//        $this->service->setGroupName('検証用グループ');
//        $this->service->setTopicName('検証用トピック');
        $this->service->setGroupName('自分用グループ');
        $this->service->setTopicName('メモするトピ');

        $result = $this->service->postInterestingArticle();

        $this->assertTrue($result);

        $token = $this->service->getAccessTokenInfo();
        $this->assertGreaterThan(0, mb_strlen($token["oauth_token"]));
        $this->assertGreaterThan(0, mb_strlen($token["oauth_token_secret"]));

        $group_id = $this->service->getGroupId();
        $this->assertGreaterThan(0, mb_strlen($group_id));

        $topic_id = $this->service->getTopicId();
        $this->assertGreaterThan(0, mb_strlen($topic_id));

        $article = $this->service->getArticle();
        $this->assertGreaterThan(0, mb_strlen($article["title"]));
        $this->assertGreaterThan(0, mb_strlen($article["link"]));

        $message         = $this->service->getMessage();
        $comment_message = nl2br($article["title"] . PHP_EOL . $article["link"]);

        $xmlString = <<< EOM
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom"
      xmlns:cbl="http://schemas.cybozulive.com/common/2010">
  <cbl:operation type="insert"/>
  <id>$topic_id</id>
  <entry>
    <summary type="text">$comment_message</summary>
  </entry>
</feed>
EOM;

        $this->assertEquals($message, $xmlString);

    }

    /**
     * デイリー情報の投稿に成功するか
     *
     */
    public function testPostArtificialIntelligenceTalk()
    {
//        $user = [
//          'x_auth_username' => env('CYBOZULIVE_USER_NAME_DEV'),
//          'x_auth_password' => env('CYBOZULIVE_PASSWORD_DEV'),
//        ];

        $user = [
          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
        ];
        $this->service->setUser($user);

//        $this->service->setGroupName('検証用グループ');
//        $this->service->setTopicName('検証用トピック');
        $this->service->setGroupName('自分用グループ');
        $this->service->setTopicName('メモするトピ');

        $result = $this->service->postArtificialIntelligenceTalk();

        Log::info('user : ', $user);
        Log::info('postArtificialIntelligenceTalk : ', ['result' => $result]);

        $this->assertTrue($result);

    }
    
    /**
     * デイリー情報の投稿に成功するか
     *
     */
    public function testPostDailyInformation()
    {
//        $user = [
//          'x_auth_username' => env('CYBOZULIVE_USER_NAME_DEV'),
//          'x_auth_password' => env('CYBOZULIVE_PASSWORD_DEV'),
//        ];

        $user = [
          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
        ];
        $this->service->setUser($user);

//        $this->service->setGroupName('検証用グループ');
//        $this->service->setTopicName('検証用トピック');
        $this->service->setGroupName('自分用グループ');
        $this->service->setTopicName('メモするトピ');

        $result = $this->service->postDailyInformation();

        Log::info('user : ', $user);
        Log::info('postDailyInformation : ', ['result' => $result]);

        $this->assertTrue($result);

    }

    /**
     * 外部からの投稿に成功するか
     *
     */
    public function testPostCgbMessage()
    {

//        $user = [
//          'x_auth_username' => env('CYBOZULIVE_USER_NAME_DEV'),
//          'x_auth_password' => env('CYBOZULIVE_PASSWORD_DEV'),
//        ];
        $user = [
          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
        ];

        $this->service->setUser($user);
        $this->service->setGroupName('自分用グループ');
        $this->service->setTopicName('メモするトピ');
        $this->service->setMessage('検証用投稿');

        $result = $this->service->postCgbMessage();

        Log::info('user : ', $user);
        Log::info('postCgbMessage : ', ['result' => $result]);

        $this->assertTrue($result);

    }

    /**
     * 挨拶文の取得
     *
     */
    public function testGetAiMessage()
    {
//        $message = $this->service->getAiMessage();
//
//        $this->assertGreaterThan(0, mb_strlen($message));

    }
    
    public function testRequestWeatherTweet()
    {
        
        $this->service->requestWeatherTweet();
        
    }
}