<?php
namespace App\Services;

use Log;
use Util;
use Http;
use Config;
//use App\Services\LiveDoorService;
use App\Services\RedmineService;
use Intervention\Image\Facades\Image as Image;

/**
 * Class CybozuLiveService
 */
class CybozuLiveService
{

    private $cybozu;
    private $user;
    private $livedoor;
    private $redmine;

    private $access_token_info;
    private $group_id;
    private $topic_id;
    private $article;
    private $message;
    private $gwschedule;
    private $weather_message;
    private $meigen_message;
    private $shuzo_message;


    private $group_name;
    private $topic_name;

    private $xauth_access_token_url = 'https://api.cybozulive.com/oauth/token';
    private $x_auth_mode            = 'client_auth';
    private $get_group_id_url       = "https://api.cybozulive.com/api/group/V2";
    private $get_topic_id_url       = "https://api.cybozulive.com/api/board/V2";
    private $post_comment_url       = "https://api.cybozulive.com/api/comment/V2";
    private $get_comment_url        = "https://api.cybozulive.com/api/comment/V2";
    private $get_gwschedule_url     = "https://api.cybozulive.com/api/gwSchedule/V2";
    private $get_rss_url            = "http://b.hatena.ne.jp/hotentry/it.rss?of=1&";
    private $user_agent             = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5)';

    const ARTICLE_NUM             = 10;
    const COLD_CASE               = 20;
    const HOT_CASE                = 30;
    const BORDER                  = '-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-';
    const TWITTER_TOKEN           = '28455674-wtA4jZ7vVx1pRfj52u4uEiwQfv1fiS8SxQ76R3lZc';
    const TWITTER_TOKEN_SECRET    = 'mBfyqak8p3Q7P1MHyEPekrsnKd7Ugp88AmhViIvoo3vzh';
    const TWITTER_CONSUMER_KEY    = 'owFvNVL5dnxxE4QLWWvo4nrIE';
    const TWITTER_CONSUMER_SECRET = 'riWGclpWKqHU91bOghs9WngEwYYt0s1rXabMPACzgG004v4c7L';
    const TWITTER_SEARCH_URL      = 'https://api.twitter.com/1.1/search/tweets.json';


    /**
     * CybozuLiveService constructor.
     *
     * @param \App\Services\LiveDoorService|null $livedoor
     * @param \App\Services\RedmineService|null  $redmine
     */
    public function __construct(LiveDoorService $livedoor = null, RedmineService $redmine = null)
    {

        Util::generateLogMessage('START');

        $this->cybozu = [
          'consumer_key'    => env('CYBOZULIVE_CONSUMER_KEY'),
          'consumer_secret' => env('CYBOZULIVE_CONSUMER_SECRET'),
        ];

        $this->livedoor = $livedoor ?: new LiveDoorService();
        $this->redmine  = $redmine ?: new RedmineService();

        Util::generateLogMessage('END');
    }

    /**
     * AI同士の会話をサイボウズLIVEに投稿する
     *
     * @return bool
     */
    public function postArtificialIntelligenceTalk()
    {

        Util::generateLogMessage('START');

        try {

            // アクセストークンを取得
            $this->requestAccessToken();

            // グループIDを取得
            $this->requestGroupId();

            // トピックIDを取得
            $this->requestTopicId();

            // POSTする文字列を生成
            $this->createMessageByAI();

            $this->postComment();

        } catch (HTTP_OAuth_Exception $hoe) {

            Log::info('HTTP_OAuth_Exception', ['hoe' => $hoe]);
            exit;

        } catch (\HTTP_Request2_Exception $hr2e) {

            Log::info('HTTP_Request2_Exception', ['hr2e' => $hr2e]);
            exit;

        } catch (\Exception $e) {

            Log::info('Exception', ['e' => $e]);
            exit;

        }

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * デイリー情報をサイボウズに投稿する
     *
     * @return bool
     */
    public function postDailyInformation()
    {

        Util::generateLogMessage('START');

        try {

            // アクセストークンを取得
            $this->requestAccessToken();

            // グループIDを取得
            $this->requestGroupId();

            // トピックIDを取得
            $this->requestTopicId();

            // サイボウズのスケジュールを取得
            $this->setGwScheduleData();

            // 天気を取得
            $this->requestWeatherTweet();
//            $this->livedoor->setWeatherData();

            // 経営の名言を取得
            $this->requestMeigenTweet();

            // 修造の名言を取得
            $this->getShuzoWord();

            // 昨日以降に更新されたチケットを取得
            $this->redmine->setTicketData();

            // POSTする文字列を生成
            $this->createMessageForDaily();

            // 最古のコメントを削除する

            // デイリー情報をサイボウズに投稿
//            $image_path = public_path('images/chara/07_sakura.jpg');
//            $image_name = 's.jpg';
//            $this->postCommentWithImage($image_path, $image_name);
            $this->postComment();

        } catch (HTTP_OAuth_Exception $hoe) {

            Log::info('HTTP_OAuth_Exception', ['hoe' => $hoe]);
            exit;

        } catch (\HTTP_Request2_Exception $hr2e) {

            Log::info('HTTP_Request2_Exception', ['hr2e' => $hr2e]);
            exit;

        } catch (\Exception $e) {

            Log::info('Exception', ['e' => $e]);
            exit;

        }

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * @return bool
     */
    public function postCgbMessage()
    {

        Util::generateLogMessage('START');

        try {

            // アクセストークンを取得
            $this->requestAccessToken();

            // グループIDを取得
            $this->requestGroupId();

            // トピックIDを取得
            $this->requestTopicId();

            // POSTする文字列を生成
            $topic_id        = $this->getTopicId();
            $comment_message = $this->getMessage();

            // 投稿用XMLの生成
            $xmlString = $this->getXmlString($topic_id, $comment_message);

            $this->setMessage($xmlString);

            $this->postComment();

        } catch (HTTP_OAuth_Exception $hoe) {

            Log::info('HTTP_OAuth_Exception', ['hoe' => $hoe]);
            exit;

        } catch (\HTTP_Request2_Exception $hr2e) {

            Log::info('HTTP_Request2_Exception', ['hr2e' => $hr2e]);
            exit;

        } catch (\Exception $e) {

            Log::info('Exception', ['e' => $e]);
            exit;

        }

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * 興味深い記事をサイボウズLIVEに投稿する
     *
     * @return bool
     */
    public function postInterestingArticle()
    {

        Util::generateLogMessage('START');

        try {

            // アクセストークンを取得
            $this->requestAccessToken();

            // グループIDを取得
            $this->requestGroupId();

            // トピックIDを取得
            $this->requestTopicId();

            // 興味深い記事を取得
            $this->getInterestingArticle();

            // POSTする文字列を生成
            $this->createMessageForInteresting();

            // 興味深い記事をサイボウズに投稿
            $this->postComment();

        } catch (HTTP_OAuth_Exception $hoe) {

            Log::info('HTTP_OAuth_Exception', ['hoe' => $hoe]);
            exit;

        } catch (\HTTP_Request2_Exception $hr2e) {

            Log::info('HTTP_Request2_Exception', ['hr2e' => $hr2e]);
            exit;

        } catch (\Exception $e) {

            Log::info('Exception', ['e' => $e]);
            exit;

        }

        Util::generateLogMessage('END');

        return true;

    }


    /**
     * アクセストークンを取得する
     *
     * @return bool
     * @throws \Exception
     * @throws \HTTP_OAuth_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function requestAccessToken()
    {

        Util::generateLogMessage('START');

        $consumer_key           = $this->cybozu["consumer_key"];
        $consumer_secret        = $this->cybozu["consumer_secret"];
        $xauth_access_token_url = $this->xauth_access_token_url;
        $user                   = $this->getUser();

        $params = array(
          'x_auth_username' => $user["x_auth_username"],
          'x_auth_password' => $user["x_auth_password"],
          'x_auth_mode'     => $this->x_auth_mode,
        );

        //-----------------------------
        // アクセストークンの取得
        //-----------------------------

        $request = new \HTTP_Request2();
        $request->setConfig('ssl_verify_peer', false);

        $consumerRequest = new \HTTP_OAuth_Consumer_Request();
        $consumerRequest->accept($request);

        $oauth = new \HTTP_OAuth_Consumer($consumer_key, $consumer_secret);
        $oauth->accept($consumerRequest);

        // リクエスト送信
        $response = $oauth->sendRequest($xauth_access_token_url, $params, \HTTP_Request2::METHOD_POST);

        // HTTPステータスチェック
        if ($response->getStatus() !== 200) {
            throw new \Exception($response->getBody(), $response->getStatus());
        }

        // 解析
        parse_str($response->getBody(), $access_token_info);

        Log::info("取得したアクセストークン", $access_token_info);

        $this->setAccessTokenInfo($access_token_info);

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * グループIDを取得する
     *
     * @return bool
     * @throws \Exception
     * @throws \HTTP_OAuth_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function requestGroupId()
    {

        Util::generateLogMessage('START');

        // アクセストークンを取得
        $access_token_info = $this->getAccessTokenInfo();

        // 投稿したいグループ名
        $group_name = $this->group_name;

        $consumer_key    = $this->cybozu["consumer_key"];
        $consumer_secret = $this->cybozu["consumer_secret"];

        $request = new \HTTP_Request2();
        $request->setConfig('ssl_verify_peer', false);

        $consumerRequest = new \HTTP_OAuth_Consumer_Request();
        $consumerRequest->accept($request);

        $oauth = new \HTTP_OAuth_Consumer($consumer_key,
          $consumer_secret,
          $access_token_info["oauth_token"],
          $access_token_info["oauth_token_secret"]);
        $oauth->accept($consumerRequest);

        // グループの取得
        $req = $oauth->sendRequest($this->get_group_id_url, array(), 'GET');

        // HTTPステータスチェック
        if ($req->getStatus() !== 200) {
            throw new \Exception($req->getBody(), $req->getStatus());
        }

        // 解析
        $list = simplexml_load_string($req->getBody());
        foreach ($list->entry as $entry) {

            // 指定したグループ名のみ
            if ($entry->title == $group_name) {

                // "GROUP,1:1"の形式で取得される
                $group = explode(",", $entry->id);
                break;
            }
        }

        // "1:1"の部分のみ使う
        $group_id = $group[1];

        Log::info("取得したグループのID", ["group_id" => $group_id]);

        $this->setGroupId($group_id);

        Util::generateLogMessage('END');

        return true;
    }

    /**
     * 掲示板のIDを取得する
     *
     * @return bool
     * @throws \Exception
     * @throws \HTTP_OAuth_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function requestTopicId()
    {
        Util::generateLogMessage('START');

        // アクセストークンを取得
        $access_token_info = $this->getAccessTokenInfo();

        // グループIDを取得
        $group_id = $this->getGroupId();

        $consumer_key    = $this->cybozu["consumer_key"];
        $consumer_secret = $this->cybozu["consumer_secret"];

        // 投稿したい掲示板のトピック名
        $topic_name = $this->topic_name;

        $request = new \HTTP_Request2();
        $request->setConfig('ssl_verify_peer', false);

        $consumerRequest = new \HTTP_OAuth_Consumer_Request();
        $consumerRequest->accept($request);

        $oauth = new \HTTP_OAuth_Consumer($consumer_key,
          $consumer_secret,
          $access_token_info["oauth_token"],
          $access_token_info["oauth_token_secret"]);
        $oauth->accept($consumerRequest);

        // 掲示板の取得
        $req = $oauth->sendRequest($this->get_topic_id_url, array('group' => $group_id), 'GET');

        // HTTPステータスチェック
        if ($req->getStatus() !== 200) {
            throw new \Exception($req->getBody(), $req->getStatus());
        }

        // 解析
        $list = simplexml_load_string($req->getBody());
        foreach ($list->entry as $entry) {

            // "GROUP,1:1,BOARD,1:1"の形式で取得される
            if ($entry->title == $topic_name) {
                $topic_id = $entry->id;
                break;
            }

        }

        $topic_id = (string)$topic_id;

        Log::info("取得した掲示板のID", ["topic_id" => $topic_id]);

        $this->setTopicId($topic_id);

        Util::generateLogMessage('END');

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function requestWeatherTweet()
    {
        Util::generateLogMessage('START');

        $border = self::BORDER;

        // 検索条件
        $params = [
          'q'     => '@Yahoo_weather,#東京の天気',
          'count' => '1',
          'lang'  => 'ja'
        ];

        $res = $this->requestSearchTweet($params);

        $res     = json_decode($res, true);
        $text    = str_replace('RT @Yahoo_weather: ', '', $res['statuses'][0]['text']);
        $message = '☆今日の天気です。' . PHP_EOL . PHP_EOL . $text . PHP_EOL . PHP_EOL . $border;

        $this->setWeatherMessage($message);

        // 生成した文字列をslackに生成
        Util::postSlack($message);

        Util::generateLogMessage('END');

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function requestMeigenTweet()
    {
        Util::generateLogMessage('START');

        $border = self::BORDER;

        // 検索条件
        $params = [
          'q'     => '@keieisyameigen_',
          'count' => '1',
          'lang'  => 'ja'
        ];

        $res = $this->requestSearchTweet($params);

        $res     = json_decode($res, true);
        $text    = str_replace('RT @keieisyameigen_: ', '', $res['statuses'][0]['text']);
        $message = '☆今日の格言です。' . PHP_EOL . PHP_EOL . $text . PHP_EOL . PHP_EOL . $border;

        $this->setMeigenMessage($message);

        // 生成した文字列をslackに生成
        Util::postSlack($message);

        Util::generateLogMessage('END');

        return true;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function getShuzoWord()
    {
        Util::generateLogMessage('START');

        $border = self::BORDER;

        $messages = Config::get('const.shuzo');
        $text     = $messages[date('j')];

        $message = '☆今日の修造です。' . PHP_EOL . PHP_EOL . $text . PHP_EOL . PHP_EOL . $border;

        $this->setShuzoMessage($message);

        // 生成した文字列をslackに生成
        Util::postSlack($message);

        Util::generateLogMessage('END');

        return true;
    }

    /**
     * @param $params
     *
     * @return mixed
     * @throws \Exception
     * @throws \HTTP_OAuth_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function requestSearchTweet($params)
    {
        Util::generateLogMessage('START');

        $access_token_info                       = [];
        $access_token_info["oauth_token"]        = self::TWITTER_TOKEN;
        $access_token_info["oauth_token_secret"] = self::TWITTER_TOKEN_SECRET;

        $consumer_key    = self::TWITTER_CONSUMER_KEY;
        $consumer_secret = self::TWITTER_CONSUMER_SECRET;
        $get_tweet_url   = self::TWITTER_SEARCH_URL;

        $request = new \HTTP_Request2();
        $request->setConfig('ssl_verify_peer', false);

        $consumerRequest = new \HTTP_OAuth_Consumer_Request();
        $consumerRequest->accept($request);

        $oauth = new \HTTP_OAuth_Consumer(
          $consumer_key,
          $consumer_secret,
          $access_token_info["oauth_token"],
          $access_token_info["oauth_token_secret"]);
        $oauth->accept($consumerRequest);

        // ツイートの取得
        $req = $oauth->sendRequest($get_tweet_url, $params, 'GET');

        // HTTPステータスチェック
        if ($req->getStatus() !== 200) {
            throw new \Exception($req->getBody(), $req->getStatus());
        }

        $res = $req->getBody();

        Log::info("取得したtweet情報", ["response" => $res]);

        Util::generateLogMessage('END');

        return $res;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function setGwScheduleData()
    {
        Util::generateLogMessage('START');

        // グループスケジュールを取得
        $response_gwschedule = $this->requestGwScheduleInfo();

        // 必要な項目を抽出
        $gwschedule_info = $this->createGwscheduleInfo($response_gwschedule);

        // アクセサに設定
        $this->setGwscheduleInfo($gwschedule_info);

        Util::generateLogMessage('END');

        return true;
    }

    /**
     * サイボウズに投稿する
     *
     * @return bool
     * @throws \Exception
     * @throws \HTTP_OAuth_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function postComment()
    {

        Util::generateLogMessage('START');

        // アクセストークンを取得
        $access_token_info = $this->getAccessTokenInfo();
        $xmlString         = $this->getMessage();
        $consumer_key      = $this->cybozu["consumer_key"];
        $consumer_secret   = $this->cybozu["consumer_secret"];

        $request = new \HTTP_Request2();
        $request->setConfig('ssl_verify_peer', false);
        $request->setHeader('Content-Type: application/atom+xml; charset=utf-8');

        $consumerRequest = new \HTTP_OAuth_Consumer_Request();
        $consumerRequest->accept($request);
        $consumerRequest->setBody($xmlString);

        $oauth = new \HTTP_OAuth_Consumer($consumer_key,
          $consumer_secret,
          $access_token_info["oauth_token"],
          $access_token_info["oauth_token_secret"]);
        $oauth->accept($consumerRequest);

        // 投稿
        $req = $oauth->sendRequest($this->post_comment_url, array(), \HTTP_Request2::METHOD_POST);

        // HTTPステータスチェック
        if ($req->getStatus() !== 200) {
            throw new \Exception($req->getBody(), $req->getStatus());
        }

        Util::generateLogMessage('END');

        return true;
    }


    /**
     * サイボウズに投稿する
     *
     * @param null $image_path
     * @param null $image_name
     *
     * @return bool
     * @throws \Exception
     * @throws \HTTP_OAuth_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function postCommentWithImage($image_path = null, $image_name = null)
    {

        Util::generateLogMessage('START');

        if (empty($image_path) || empty($image_name)) {

            Log::info('画像が指定されていません。');

            return false;

        }

        // アクセストークンを取得
        $access_token_info = $this->getAccessTokenInfo();
        $xmlString         = $this->getMessage();
        $consumer_key      = $this->cybozu["consumer_key"];
        $consumer_secret   = $this->cybozu["consumer_secret"];

        $finfo             = finfo_open(FILEINFO_MIME_TYPE);
        $file_content_type = finfo_file($finfo, $image_path);
        finfo_close($finfo);

        $request = new \HTTP_Request2();
        $request->setConfig('ssl_verify_peer', false);
        $request->setHeader('Content-Type: multipart/form-data; charset=utf-8');

        $consumerRequest = new \HTTP_OAuth_Consumer_Request();
        $consumerRequest->accept($request);
        $consumerRequest->setBody($xmlString);

        $oauth = new \HTTP_OAuth_Consumer($consumer_key,
          $consumer_secret,
          $access_token_info["oauth_token"],
          $access_token_info["oauth_token_secret"]);
        $oauth->accept($consumerRequest);

        $request->addPostParameter("default", $xmlString);
        $request->addUpload("file0", $image_path, $image_name, $file_content_type);

        // 投稿
        $req = $oauth->sendRequest($this->post_comment_url, array(), \HTTP_Request2::METHOD_POST);

        // HTTPステータスチェック
        if ($req->getStatus() !== 200) {
            throw new \Exception($req->getBody(), $req->getStatus());
        }

        Util::generateLogMessage('END');

        return true;
    }

    /**
     * 注目の記事を取得し、任意の記事を抽出する
     *
     * @return bool
     * @throws \Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function getInterestingArticle()
    {
        Util::generateLogMessage('START');

        $rss = $this->get_rss_url . time();

        $request = new \HTTP_Request2();
        $request->setConfig('ssl_verify_peer', false);
        $request->setMethod(\HTTP_Request2::METHOD_GET);
        $request->setHeader(array(
          'Referer'    => $rss,
          'User-Agent' => $this->user_agent,
          'Connection' => 'close'
        ));

        $response = $request->setUrl($rss)->send();

        // HTTPステータスチェック
        if ($response->getStatus() !== 200) {
            throw new \Exception($response->getBody(), $response->getStatus());
        }

        $str      = $response->getBody();
        $xml      = simplexml_load_string($str);
        $json     = json_encode($xml);
        $articles = json_decode($json, true);

        $num = mt_rand(0, self::ARTICLE_NUM);

        $article["title"] = $articles["item"][$num]["title"];
        $article["link"]  = $articles["item"][$num]["link"];

        Log::info("取得した記事", $article);

        $this->setArticle($article);

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * @internal param mixed $message
     */
    public function createMessageByAI($date = null)
    {
        // 日付型チェック
        if (is_null($date) || $date !== date("Y-m-d", strtotime($date))) {
            $date = date('Y-m-d');
        }

        // 掲示板のIDを取得
        $topic_id = $this->getTopicId();

        // 挨拶文を取得
        $comment_message = $this->getAiMessage() . PHP_EOL;

        // 投稿用XMLの生成
        $xmlString = $this->getXmlString($topic_id, $comment_message);

        $this->setMessage($xmlString);
    }

    /**
     * @internal param mixed $message
     */
    public function createMessageForDaily($date = null)
    {
        // 日付型チェック
        if (is_null($date) || $date !== date("Y-m-d", strtotime($date))) {
            $date = date('Y-m-d');
        }

        // 掲示板のIDを取得
        $topic_id = $this->getTopicId();

        // 挨拶文を取得
//        $comment_message = $this->getGreetingMessage() . PHP_EOL;
        $comment_message = '';

        // 天気のメッセージを取得
        $weather_message = $this->getWeatherMessage();
        if ($weather_message) {
            $comment_message .= sprintf('%s%s', $weather_message, PHP_EOL);
        }
        // 経営者の格言のメッセージを取得
        $meigen_message = $this->getMeigenMessage();
        if ($meigen_message) {
            $comment_message .= sprintf('%s%s', $meigen_message, PHP_EOL);
        }
        // 修造の格言のメッセージを取得
        $shuzo_message = $this->getShuzoMessage();
        if ($shuzo_message) {
            $comment_message .= sprintf('%s%s', $shuzo_message, PHP_EOL);
        }

        // チケットのメッセージを取得
        $ticket_message = $this->createTicketMessage();
        if ($ticket_message) {
            $comment_message .= sprintf('%s%s', $ticket_message, PHP_EOL);
        }

        // スケジュールのメッセージを取得
//        $gwschedule_message = $this->createGwScheduleMessage();
//        if ($gwschedule_message) {
//            $comment_message .= sprintf('%s%s', $gwschedule_message, PHP_EOL);
//        }

        // 週報のメッセージを取得
        $weekly_report_message = $this->createWeeklyReportMessage($date);
        if ($weekly_report_message) {
            $comment_message .= sprintf('%s%s', $weekly_report_message, PHP_EOL);
        }

        // 月末のメッセージを取得
        $end_month_message = $this->createEndMonthMessage($date);
        if ($end_month_message) {
            $comment_message .= sprintf('%s%s', $end_month_message, PHP_EOL);
        }

        // 投稿用XMLの生成
        $xmlString = $this->getXmlString($topic_id, $comment_message);

        $this->setMessage($xmlString);
    }


    /**
     * @return bool
     */
    public function createMessageForInteresting()
    {
        // 掲示板のIDを取得
        $topic_id = $this->getTopicId();

        // 投稿する記事を取得
        $article = $this->getArticle();

        // 記事を元にメッセージを作成
        $comment_message = nl2br($article["title"] . PHP_EOL . $article["link"]);

        // 投稿用XMLの生成
        $xmlString = $this->getXmlString($topic_id, $comment_message);

        $this->setMessage($xmlString);

        return true;
    }


    /**
     * @param null $num
     *
     * @return string
     */
    public function getAiMessage($num = null)
    {
        Util::generateLogMessage('START');

        // Googleトレンドから急上昇ワードを取得

        $url = 'http://www.google.co.jp/trends/hottrends/atom/hourly?pn=p4';

        $xml      = simplexml_load_file($url);
        $xml      = str_replace(["&amp;", "&"], ["&", "&amp;"], $xml->entry->content);
        $xml      = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $json     = json_encode($xml);
        $keywords = json_decode($json, true);

        if ($keywords) {

            $num = (is_null($num)) ? mt_rand(0, (count($keywords['li']) - 1) / 2) : $num;

            $keyword = $keywords['li'][$num]['span']['a'];

            Log::info("取得した急上昇ワード", ['keyword' => $keyword]);

            // 急上昇ワードを人工知能に質問してみる

            $ahead           = !(date('j') % 2 == 0);
            $search_word     = $keyword . 'のことどう思う？';
            $answer          = '';
            $answer_messages = [];

            for ($i = 0; $i < 30; $i++) {

                if ($ahead) {

                    // docomo 

                    $key = "785a6f616d6c5049536b63384f514f78443236302f7058384c4b34436559476a71725942507254634e4c38";

                    $url = 'https://api.apigw.smt.docomo.ne.jp/dialogue/v1/dialogue';
                    $url = $url . "?APIKEY=" . $key;

                    $params = [
                      'utt'            => $search_word,
                      "context"        => "",
                      "nickname"       => "さくら",
                      "nickname_y"     => "サクラ",
                      "sex"            => "女",
                      "bloodtype"      => "O",
                      "birthdateY"     => "1995",
                      "birthdateM"     => "8",
                      "birthdateD"     => "20",
                      "age"            => "18",
                      "constellations" => "双子座",
                      "place"          => "東京",
                      "mode"           => "dialog"
                    ];

                    $response = Http::postJson($url, $params);
                    $response = json_decode($response, true);

                    $search_word = $response['utt'];
                    $answer      = 'D「' . $response['utt'] . '」';

                    Log::info("取得した人工知能からの回答", ['answer' => $answer]);

                } else {

                    $url      = 'https://chatbot-api.userlocal.jp/api/chat';
                    $params   = [
                      'query'  => [
                        'key'     => '3249ad8e8790d2b8b4c4',
                        'message' => $search_word,
                      ],
                      'verify' => false
                    ];
                    $response = Http::get($url, $params);
                    $response = json_decode($response, true);

                    $search_word = $response['result'];
                    $answer      = 'U「' . $response['result'] . '」';

                    Log::info("取得した人工知能からの回答", ['answer' => $answer]);

                }

                $ahead             = !$ahead;
                $answer_messages[] = $answer;

            }

            $message = 'お題：' . $keyword . ' ' . PHP_EOL . PHP_EOL . implode(PHP_EOL, $answer_messages);
//            $message .= PHP_EOL . 'https://c1.staticflickr.com/1/474/31575570823_06be332231.jpg';
            $message .= PHP_EOL;

        } else {

            // 急上昇ワードが取得できなかったら固定メッセージ

            $messages = Config::get('nini.hello_message');
            $num      = (is_null($num)) ? mt_rand(0, count($messages) - 1) : $num;
            $message  = preg_replace("/ /", "", $messages[$num]) . PHP_EOL . '[file:1]';

        }

        Log::info("生成した文字列", ['message' => $message]);

        // 生成した文字列をslackに生成
        Util::postSlack($message);

        Util::generateLogMessage('END');

        return $message;
    }

    /**
     * @param null $num
     *
     * @return string
     */
    public function getGreetingMessage($num = null)
    {
//        Util::generateLogMessage('START');
//
//        // Googleトレンドから急上昇ワードを取得
//
//        $url = 'http://www.google.co.jp/trends/hottrends/atom/hourly?pn=p4';
//
//        $xml      = simplexml_load_file($url);
//        $xml      = str_replace(["&amp;", "&"], ["&", "&amp;"], $xml->entry->content);
//        $xml      = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
//        $json     = json_encode($xml);
//        $keywords = json_decode($json, true);
//
//        if ($keywords) {
//
//            $num = (is_null($num)) ? mt_rand(0, (count($keywords['li']) - 1) / 2) : $num;
//
//            $keyword = $keywords['li'][$num]['span']['a'];
//
//            Log::info("取得した急上昇ワード", ['keyword' => $keyword]);
//
//            // 急上昇ワードを人工知能に質問してみる
//            
//            $ahead = !(date('j')%2==0);
//            $search_word = $keyword . 'のことどう思う？';
//            $answer = '';
//            $answer_messages = [];
//
//            for ($i = 0; $i < 5; $i++) {
//
//                if ($ahead) {
//
//                    // docomo 
//
//                    $key = "785a6f616d6c5049536b63384f514f78443236302f7058384c4b34436559476a71725942507254634e4c38";
//
//                    $url = 'https://api.apigw.smt.docomo.ne.jp/dialogue/v1/dialogue';
//                    $url = $url . "?APIKEY=" . $key;
//
//                    $params = [
//                      'utt'            => $search_word,
//                      "context"        => "",
//                      "nickname"       => "さくら",
//                      "nickname_y"     => "サクラ",
//                      "sex"            => "女",
//                      "bloodtype"      => "O",
//                      "birthdateY"     => "1995",
//                      "birthdateM"     => "8",
//                      "birthdateD"     => "20",
//                      "age"            => "18",
//                      "constellations" => "双子座",
//                      "place"          => "東京",
//                      "mode"           => "dialog"
//                    ];
//
//                    $response = Http::postJson($url, $params);
//                    $response = json_decode($response, true);
//                    
//                    $search_word = $response['utt'];
//                    $answer = 'D「' . $response['utt'] . '」';
//
//                    Log::info("取得した人工知能からの回答", ['answer' => $answer]);
//
//                } else {
//
//                    $url      = 'https://chatbot-api.userlocal.jp/api/chat';
//                    $params   = [
//                      'query'  => [
//                        'key'     => '3249ad8e8790d2b8b4c4',
//                        'message' => $search_word,
//                      ],
//                      'verify' => false
//                    ];
//                    $response = Http::get($url, $params);
//                    $response = json_decode($response, true);
//                    
//                    $search_word = $response['result'];
//                    $answer = 'U「' . $response['result'] . '」';
//
//                    Log::info("取得した人工知能からの回答", ['answer' => $answer]);
//
//                }
//                
//                $ahead = !$ahead;
//                $answer_messages[] = $answer;
//                
//            }
//
//
//            $message = '「今日の急上昇ワードは' . $keyword . 'だって」' . PHP_EOL . implode (PHP_EOL, $answer_messages);
////            $message .= PHP_EOL . 'https://c1.staticflickr.com/1/474/31575570823_06be332231.jpg';
//            $message .= PHP_EOL;
//
//        } else {
//
//            // 急上昇ワードが取得できなかったら固定メッセージ
//
//            $messages = Config::get('nini.hello_message');
//            $num      = (is_null($num)) ? mt_rand(0, count($messages) - 1) : $num;
//            $message  = preg_replace("/ /", "", $messages[$num]) . PHP_EOL . '[file:1]';
//
//        }
//
//        Log::info("生成した文字列", ['message' => $message]);
//        
//        // 生成した文字列をslackに生成
//        Util::postSlack($message);
//
//        Util::generateLogMessage('END');
//
//        return $message;
    }

    /**
     * @param $topic_id
     * @param $comment_message
     *
     * @return string
     */
    public function getXmlString($topic_id, $comment_message)
    {

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

        return $xmlString;

    }


//    /**
//     * @return string
//     */
//    public function createWeatherMessage()
//    {
//        // 天気情報を元にメッセージを作成
//        $weather_info = $this->livedoor->getWeatherInfo();
//
//        if (!is_array($weather_info)) {
//            return '';
//        }
//
//        $border          = self::BORDER;
//        $weather_message = '';
//        $temp_message    = '';
//        $other_message   = '';
//
//        $weather_message = sprintf('☆%sの%sの天気は「%s」です。',
//          $weather_info['label'], $weather_info['pref'], $weather_info['telop']);
//        $weather_message = ($weather_message) ? $weather_message . PHP_EOL : '';
//
//        $min = $weather_info['temp_min'];
//        $max = $weather_info['temp_max'];
//
//        $temp_message = $this->createTempMessage($max, $min);
//        $temp_message = ($temp_message) ? $temp_message . PHP_EOL : '';
//
//        $temp_other_message = $this->createTempOtherMessage($max, $min);
//        $temp_other_message = ($temp_other_message) ? $temp_other_message . PHP_EOL : '';
//
//        $weather_message = $weather_message . $temp_message . $temp_other_message;
//        $weather_message = ($weather_message) ? $weather_message . PHP_EOL . $border : '';
//
//        return $weather_message;
//    }


    /**
     * @param $max
     * @param $min
     *
     * @return string
     */
    public function createTempOtherMessage($max, $min, $num = null)
    {
        $message = '';

        if (!empty($max) && is_numeric($max) && $max <= self::COLD_CASE) {

            $messages = Config::get('nini.cold_message');
            $num      = (is_null($num)) ? mt_rand(0, count($messages) - 1) : $num;
            $message  = sprintf('%s%s%s', PHP_EOL, $messages[$num], PHP_EOL);

        } else {
            if (!empty($max) && is_numeric($max) && self::HOT_CASE <= $max) {

                $messages = Config::get('nini.hot_message');
                $num      = (is_null($num)) ? mt_rand(0, count($messages) - 1) : $num;
                $message  = sprintf('%s%s%s', PHP_EOL, $messages[$num], PHP_EOL);

            }
        }

        return $message;
    }


    /**
     * @param $max
     * @param $min
     *
     * @return string
     */
    public function createTempMessage($max, $min)
    {
        $temp_message = '';

        if (!empty($min)) {
            $temp_message .= sprintf('最低気温は%s度 ', $min);
        }
        if (!empty($max)) {
            $temp_message .= sprintf('最高気温は%s度 ', $max);
        }
        if (!empty($min) || !empty($max)) {
            $temp_message .= sprintf('になります。');
        }

        return $temp_message;
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function createWeeklyReportMessage($date)
    {

        $weekly_report_message = '';

        // 提出日判定
        $week    = date('w', strtotime($date));
        $present = ($week <= 1 || 5 <= $week);

        if ($present) {

            $border = self::BORDER;

            $weekly_report_message = <<< EOM
☆週報の提出日が近づいています。

提出はこちらからお願いします。
https://se-project.co.jp/cgi-bin/weeklyreport/index.cgi

$border
EOM;

        }

        return $weekly_report_message;
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function createEndMonthMessage($date)
    {
        $eom_message = '';

        // 月初からの日数
        $diff_days      = (strtotime($date) - strtotime(date('Y-m-01'))) / (60 * 60 * 24);
        $start_of_month = ($diff_days <= 5);

        // 月末までの日数
        $diff_days    = (strtotime(date('Y-m-t')) - strtotime($date)) / (60 * 60 * 24);
        $end_of_month = ($diff_days <= 5);

        // 月初 or 月末
        if ($start_of_month || $end_of_month) {

            $year   = date('Y', strtotime(date('Y-m-1') . '+1 month'));
            $month  = date('m', strtotime(date('Y-m-1') . '+1 month'));
            $border = self::BORDER;

            $eom_message = <<< EOM
☆月末が近づいています。忘れずに勤務表を提出してください。

【諸注意】勤務表のファイル名は 
XXXX勤務表(SEP用)_氏名$year$month.xls
XXXX：社員番号下桁
の形式でアップしてください。

$border
EOM;

        }

        return $eom_message;
    }


    /**
     * @return string
     */
    public function createTicketMessage()
    {
        $ticket_info = $this->redmine->getTicketInfo();
        $border      = self::BORDER;

        if (count($ticket_info) <= 0) {
            return '';
        }

        $ticket_message = sprintf('☆昨日更新されたチケットはこちらです。%s%s', PHP_EOL, PHP_EOL);

        foreach ($ticket_info as $ticket) {
            $ticket_message .= sprintf('#%s %s - %s%s', $ticket['id'], $ticket['project'], $ticket['subject'], PHP_EOL);
        }

        $ticket_message = $ticket_message . PHP_EOL . $border;

        // 生成した文字列をslackに生成
        Util::postSlack($ticket_message);

        return $ticket_message;
    }

    /**
     * @return string
     */
    public function createGwScheduleMessage()
    {
        $gwschedule_info = $this->getGwScheduleInfo();
        $border          = self::BORDER;

        if (count($gwschedule_info) <= 0) {
            return '';
        }

        $schedule_message = sprintf('☆今後一ヶ月のスケジュールです。%s%s', PHP_EOL, PHP_EOL);

        foreach ($gwschedule_info as $schedule) {

//            if ($schedule['endTime'])
//            {
//                $period = sprintf('[%s - %s]', $schedule['startTime'], $schedule['endTime']);
//            }
//            else
//            {
//                $period = sprintf('[%s]', $schedule['startTime']);
//            }
            $period = date('m月d日', strtotime($schedule['startTime']));

            $schedule_message .= sprintf('%s %s%s', $period, $schedule['title'], PHP_EOL);
        }

        $schedule_message = $schedule_message . PHP_EOL . $border;

        // 生成した文字列をslackに生成
        Util::postSlack($schedule_message);

        return $schedule_message;
    }

    /**
     * @return mixed
     * @throws \Exception
     * @throws \HTTP_OAuth_Exception
     * @throws \HTTP_Request2_LogicException
     */
    public function requestGwScheduleInfo()
    {
        Util::generateLogMessage('START');

        // アクセストークンを取得
        $access_token_info = $this->getAccessTokenInfo();

        // グループIDを取得
        $group_id = $this->getGroupId();

        $consumer_key    = $this->cybozu["consumer_key"];
        $consumer_secret = $this->cybozu["consumer_secret"];

        $request = new \HTTP_Request2();
        $request->setConfig('ssl_verify_peer', false);

        $consumerRequest = new \HTTP_OAuth_Consumer_Request();
        $consumerRequest->accept($request);

        $oauth = new \HTTP_OAuth_Consumer($consumer_key,
          $consumer_secret,
          $access_token_info["oauth_token"],
          $access_token_info["oauth_token_secret"]);
        $oauth->accept($consumerRequest);

        // 掲示板の取得
        $res = $oauth->sendRequest($this->get_gwschedule_url, array('group' => $group_id), 'GET');

        // HTTPステータスチェック
        if ($res->getStatus() !== 200) {
            throw new \Exception($res->getBody(), $res->getStatus());
        }

        // 解析
        $xml        = preg_replace("/<(.+?):(.+?)>/", "<$1_$2>", $res->getBody());
        $xml        = preg_replace("/_\/\//", "://", $xml);
        $xml        = simplexml_load_string($xml);
        $json       = json_encode($xml);
        $gwschedule = json_decode($json, true);

        Log::info("取得したスケジュール情報", $gwschedule);

        Util::generateLogMessage('END');

        return $gwschedule;
    }

    /**
     * @param $schedule_info
     *
     * @return array
     */
    public function createGwscheduleInfo($schedule_info)
    {

        Util::generateLogMessage('START');

        $result = array();
        $count  = $schedule_info['cbl_totalCount'];

        if ($count == 0) {
            return null;
        }

        if ($count > 1) {
            $schedules = $schedule_info['entry'];
        } else {
            $schedules[0] = $schedule_info['entry'];
        }

        foreach ($schedules as $key => $schedule) {

            // 繰り返し予定の場合は整形が必要
            if (isset($schedule['cblSch_recurrence'])) {

                $when  = $schedule['cblSch_recurrence']['cbl_when'];
                $first = $when['0'];
                $end   = end($when);

                $startTime = (isset($first['@attributes']['startTime']))
                  ? $first['@attributes']['startTime'] : null;
                $endTime   = (isset($end['@attributes']['endTime']))
                  ? $end['@attributes']['endTime'] : null;

                $startTime = Util::convertUtcToJst($startTime);
                $endTime   = Util::convertUtcToJst($endTime);

            } else {

                $startTime = (isset($schedule['cbl_when']['@attributes']['startTime']))
                  ? $schedule['cbl_when']['@attributes']['startTime'] : null;
                $endTime   = (isset($schedule['cbl_when']['@attributes']['endTime']))
                  ? $schedule['cbl_when']['@attributes']['endTime'] : null;

            }

            $result[$key]['id']        = (isset($schedule['id'])) ? $schedule['id'] : null;
            $result[$key]['title']     = (isset($schedule['title'])) ? $schedule['title'] : null;
            $result[$key]['summary']   = (isset($schedule['summary'])) ? $schedule['summary'] : null;
            $result[$key]['startTime'] = $startTime;
            $result[$key]['endTime']   = $endTime;

            $result[$key]['id']        = ($result[$key]['id']) ? $result[$key]['id'] : null;
            $result[$key]['title']     = ($result[$key]['title']) ? $result[$key]['title'] : null;
            $result[$key]['summary']   = ($result[$key]['summary']) ? $result[$key]['summary'] : null;
            $result[$key]['startTime'] = ($result[$key]['startTime']) ? $result[$key]['startTime'] : null;
            $result[$key]['endTime']   = ($result[$key]['endTime']) ? $result[$key]['endTime'] : null;

        }

        Log::info("出力に使用するスケジュール情報", $result);

        Util::generateLogMessage('END');

        return $result;

    }

    /**
     * @return array
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param array $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getGroupName()
    {
        return $this->group_name;
    }

    /**
     * @param string $group_name
     */
    public function setGroupName($group_name)
    {
        $this->group_name = $group_name;
    }

    /**
     * @return string
     */
    public function getTopicName()
    {
        return $this->topic_name;
    }

    /**
     * @param string $topic_name
     */
    public function setTopicName($topic_name)
    {
        $this->topic_name = $topic_name;
    }

    /**
     * @return mixed
     */
    public function getAccessTokenInfo()
    {
        return $this->access_token_info;
    }

    /**
     * @param $access_token_info
     */
    public function setAccessTokenInfo($access_token_info)
    {
        $this->access_token_info = $access_token_info;
    }

    /**
     * @return mixed
     */
    public function getGroupId()
    {
        return $this->group_id;
    }

    /**
     * @param $group_id
     */
    public function setGroupId($group_id)
    {
        $this->group_id = $group_id;
    }

    /**
     * @return mixed
     */
    public function getTopicId()
    {
        return $this->topic_id;
    }

    /**
     * @param $topic_id
     */
    public function setTopicId($topic_id)
    {
        $this->topic_id = $topic_id;
    }

    /**
     * @return mixed
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * @param $article
     */
    public function setArticle($article)
    {
        $this->article = $article;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @return mixed
     */
    public function getGwScheduleInfo()
    {
        return $this->gwschedule;
    }

    /**
     * @param $gwschedule
     */
    public function setGwScheduleInfo($gwschedule)
    {
        $this->gwschedule = $gwschedule;
    }

    /**
     * @return mixed
     */
    public function getWeatherMessage()
    {
        return $this->weather_message;
    }

    /**
     * @param mixed $message
     */
    public function setWeatherMessage($message)
    {
        $this->weather_message = $message;
    }

    /**
     * @return mixed
     */
    public function getMeigenMessage()
    {
        return $this->meigen_message;
    }

    /**
     * @param mixed $message
     */
    public function setMeigenMessage($message)
    {
        $this->meigen_message = $message;
    }

    /**
     * @return mixed
     */
    public function getShuzoMessage()
    {
        return $this->shuzo_message;
    }

    /**
     * @param mixed $message
     */
    public function setShuzoMessage($message)
    {
        $this->shuzo_message = $message;
    }

}

