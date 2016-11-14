<?php
namespace App\Services;

use Log;
use Util;
use App\Services\LiveDoorService;

/**
 * Class CybozuLiveService
 */
class CybozuLiveService
{

    private $cybozu;
    private $user;
    private $livedoor;

    private $access_token_info;
    private $group_id;
    private $topic_id;
    private $article;
    private $message;

    private $group_name;
    private $topic_name;

    private $xauth_access_token_url = 'https://api.cybozulive.com/oauth/token';
    private $x_auth_mode            = 'client_auth';
    private $get_group_id_url       = "https://api.cybozulive.com/api/group/V2";
    private $get_topic_id_url       = "https://api.cybozulive.com/api/board/V2";
    private $post_comment_url       = "https://api.cybozulive.com/api/comment/V2";
    private $get_rss_url            = "http://b.hatena.ne.jp/hotentry/it.rss?of=1&";
    private $user_agent             = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5)';
    private $max_article_target     = 10;
//
//    const SEP_GROUP_NAME_TEST = '自分用グループ';
//    const SEP_TOPIC_NAME_TEST = 'メモするトピ';
    const COLD_CASE = 20;
    const BORDER    = '-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-∵-∴-';


    /**
     * CybozuLiveService constructor.
     */
    public function __construct()
    {

        Util::generateLogMessage('START');

        $this->cybozu = [
          'consumer_key'    => env('CYBOZULIVE_CONSUMER_KEY'),
          'consumer_secret' => env('CYBOZULIVE_CONSUMER_SECRET'),
        ];

//        $this->user = [
//          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
//          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
//        ];

        $this->livedoor = New LiveDoorService;

        Util::generateLogMessage('END');
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
     * デイリー情報をサイボウズに投稿する
     *
     * @return bool
     */
    public function postDailyInformation()
    {

        Util::generateLogMessage('START');

        // アクセストークンを取得
        $this->requestAccessToken();

        // グループIDを取得
        $this->requestGroupId();

        // トピックIDを取得
        $this->requestTopicId();

        // 天気を取得
        $this->livedoor->setWeatherData();

        // POSTする文字列を生成
        $this->createMessageForDaily();

        // デイリー情報をサイボウズに投稿
        $this->postComment();

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

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * アクセストークンを取得する
     *
     * @return bool
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

        try {
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

        } catch (\HTTP_Request2_Exception $hr2e) {
            Log::info('HTTP_Request2_Exception', ['hr2e' => $hr2e]);
            exit;
        } catch (\Exception $e) {
            Log::info('Exception', ['e' => $e]);
            exit;
        }

        Log::info("取得したアクセストークン", $access_token_info);

        $this->setAccessTokenInfo($access_token_info);

        Util::generateLogMessage('END');

        return true;

    }


    /**
     * グループIDを取得する
     *
     * @return bool
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

        try {
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

        } catch (\HTTP_Request2_Exception $hr2e) {
            Log::info('HTTP_Request2_Exception', ['hr2e' => $hr2e]);
            exit;
        } catch (\Exception $e) {
            Log::info('Exception', ['e' => $e]);
            exit;
        }

        Log::info("取得したグループのID", ["group_id" => $group_id]);

        $this->setGroupId($group_id);

        Util::generateLogMessage('END');

        return true;
    }


    /**
     * 掲示板のIDを取得する
     *
     * @return bool
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

        try {
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

        } catch (\HTTP_Request2_Exception $hr2e) {
            Log::info('HTTP_Request2_Exception', ['hr2e' => $hr2e]);
            exit;
        } catch (\Exception $e) {
            Log::info('Exception', ['e' => $e]);
            exit;
        }

        $topic_id = (string)$topic_id;

        Log::info("取得した掲示板のID", ["topic_id" => $topic_id]);

        $this->setTopicId($topic_id);

        Util::generateLogMessage('END');

        return true;
    }

    /**
     * サイボウズに投稿する
     *
     * @return bool
     */
    public function postComment()
    {

        Util::generateLogMessage('START');

        // アクセストークンを取得
        $access_token_info = $this->getAccessTokenInfo();
        $xmlString         = $this->getMessage();
        $consumer_key      = $this->cybozu["consumer_key"];
        $consumer_secret   = $this->cybozu["consumer_secret"];

        try {

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
     * 注目の記事を取得し、任意の記事を抽出する
     *
     * @return bool
     */
    public function getInterestingArticle()
    {
        Util::generateLogMessage('START');

        $rss = $this->get_rss_url . time();

        try {

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

        } catch (\HTTP_Request2_Exception $hr2e) {
            Log::info('HTTP_Request2_Exception', ['hr2e' => $hr2e]);
            exit;
        } catch (\Exception $e) {
            Log::info('Exception', ['e' => $e]);
            exit;
        }

        $num = mt_rand(0, $this->max_article_target);

        $article["title"] = $articles["item"][$num]["title"];
        $article["link"]  = $articles["item"][$num]["link"];

        Log::info("取得した記事", $article);

        $this->setArticle($article);

        Util::generateLogMessage('END');

        return true;

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
     * @param $access_token_info
     */
    public function setAccessTokenInfo($access_token_info)
    {
        $this->access_token_info = $access_token_info;
    }

    /**
     * @return mixed
     */
    public function getAccessTokenInfo()
    {
        return $this->access_token_info;
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
    public function getGroupId()
    {
        return $this->group_id;
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
    public function getTopicId()
    {
        return $this->topic_id;
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
    public function getArticle()
    {
        return $this->article;
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
     * @internal param mixed $message
     */
    public function createMessageForDaily($date = null)
    {
        // 日付型チェック
        if (is_null($date) || $date !== date("Y-m-d", strtotime($date))) {
            $date = date('Y-m-d');
        }

        // 掲示板のIDを取得
        $topic_id        = $this->getTopicId();
        $comment_message = 'おはようございます！' . PHP_EOL;

        // 天気のメッセージを取得
        $weather_message = $this->createWeatherMessage();
        if ($weather_message) {
            $comment_message .= sprintf('%s%s', $weather_message, PHP_EOL);
        }

        // 月曜のメッセージを取得
        $monday_message = $this->createMondayMessage($date);
        if ($monday_message) {
            $comment_message .= sprintf('%s%s', $monday_message, PHP_EOL);
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


    /**
     * @return string
     */
    public function createWeatherMessage()
    {
        // 天気情報を元にメッセージを作成
        $weather_info = $this->livedoor->getWeatherInfo();

        if (!is_array($weather_info)) {
            return '';
        }

        $border          = self::BORDER;
        $weather_message = '';
        $temp_message    = '';
        $other_message   = '';

        $weather_message = sprintf('☆%sの%sの天気は「%s」です。',
          $weather_info['label'], $weather_info['pref'], $weather_info['telop']);
        $weather_message = ($weather_message) ? $weather_message . PHP_EOL : '';

        $min = $weather_info['temp_min'];
        $max = $weather_info['temp_max'];

        $temp_message = $this->createTempMessage($max, $min);
        $temp_message = ($temp_message) ? $temp_message . PHP_EOL : '';

        $temp_other_message = $this->createTempOtherMessage($max, $min);
        $temp_other_message = ($temp_other_message) ? $temp_other_message . PHP_EOL : '';

        $weather_message = $weather_message . $temp_message . $temp_other_message;
        $weather_message = ($weather_message) ? $weather_message . PHP_EOL . $border : '';

        return $weather_message;
    }


    /**
     * @param $max
     * @param $min
     *
     * @return string
     */
    public function createTempOtherMessage($max, $min, $num = null)
    {
        $temp_other_message = '';

        if (!empty($max) && is_numeric($max) && $max < self::COLD_CASE) {

            $num = (is_null) ? mt_rand(0, $this->max_article_target) : $num;

            switch ($num) {
                case 1:
                case 3:
                case 8:
                    $temp_other_message = sprintf('今日は寒いですよー。');
                    break;
                case 2:
                case 5:
                case 7:
                    $temp_other_message = sprintf('風邪に注意してくださいね。');
                    break;
                default:
                    $temp_other_message = sprintf('温かい服装で出掛けてくださいね。');
                    break;
            }

            $temp_other_message = PHP_EOL . $temp_other_message . PHP_EOL;

        }

        return $temp_other_message;
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
    public function createMondayMessage($date)
    {

        $monday_message = '';

        // 曜日
        $monday = (date('w', strtotime($date)) >= '0');

        // 月曜
        if ($monday) {

            $border = self::BORDER;

            $monday_message = <<< EOM
☆月曜日です。週報の提出日になります。

提出はこちらからお願いします。
https://se-project.co.jp/cgi-bin/weeklyreport/index.cgi

$border
EOM;

        }

        return $monday_message;
    }

    /**
     * @param $date
     *
     * @return string
     */
    public function createEndMonthMessage($date)
    {
        $eom_message = '';

        // 月末までの日数
        $diff_days    = (strtotime(date('Y-m-t')) - strtotime($date)) / (60 * 60 * 24);
        $end_of_month = ($diff_days <= 5);

        // 月末
        if ($end_of_month) {

            $year   = date('Y');
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
}