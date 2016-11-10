<?php
namespace App\Services;

use Log;
use Util;

/**
 * Class CybozuLiveService
 */
class CybozuLiveService
{

    private $cybozu;
    private $user;

    private $access_token_info;
    private $group_id;
    private $topic_id;
    private $article;

    private $xauth_access_token_url = 'https://api.cybozulive.com/oauth/token';
    private $x_auth_mode            = 'client_auth';

    private $group_name         = '自分用グループ';    // 投稿の対象とするグループ名
    private $get_group_id_url   = "https://api.cybozulive.com/api/group/V2";
    private $topic_name         = 'メモするトピ';
    private $get_topic_id_url   = "https://api.cybozulive.com/api/board/V2";
    private $post_comment_url   = "https://api.cybozulive.com/api/board/V2";
    private $get_rss_url        = "http://b.hatena.ne.jp/hotentry/it.rss?of=1&";
    private $user_agent         = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5)';
    private $max_article_target = 10;

    /**
     * CybozuLiveService constructor.
     *
     */
    public function __construct()
    {

        Util::generateLogMessage('START');

        $this->cybozu = [
          'consumer_key'    => env('CYBOZULIVE_CONSUMER_KEY'),
          'consumer_secret' => env('CYBOZULIVE_CONSUMER_SECRET'),
        ];

        $this->user = [
          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
        ];

        Util::generateLogMessage('END');
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
     * 興味深い記事をサイボウズLIVEに投稿する
     *
     * @return bool
     */
    public function postInterestingArticle()
    {

        $this->requestAccessToken();

        $this->requestGroupId();

        $this->requestTopicId();

        $this->getInterestingArticle();

        $this->postComment();

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

        $params = array(
          'x_auth_username' => $this->user["x_auth_username"],
          'x_auth_password' => $this->user["x_auth_password"],
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

    public function postComment()
    {

        Util::generateLogMessage('START');

        // アクセストークンを取得
        $access_token_info = $this->getAccessTokenInfo();

        // グループIDを取得
        $group_id = $this->getGroupId();

        // 掲示板のIDを取得
        $topic_id = $this->getTopicId();

        // 投稿する記事を取得
        $article = $this->getArticle();

        $consumer_key    = $this->cybozu["consumer_key"];
        $consumer_secret = $this->cybozu["consumer_secret"];

        try {

            $comment_message = nl2br($article["title"] . PHP_EOL . $article["link"]);

            $xmlString = <<< EOM
<?xml version="1.0" encoding="UTF-8"?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <id>$topic_id</id>
  <entry>
    <summary type="text">$comment_message</summary>
  </entry>
</feed>
EOM;

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

        $agent    = stream_context_create(array('http' => array('user_agent' => $this->user_agent))); //chrome
        $str      = file_get_contents($rss, false, $agent);
        $xml      = simplexml_load_string($str);
        $json     = json_encode($xml);
        $articles = json_decode($json, true);

        $num = mt_rand(0, $this->max_article_target);

        $article["title"] = $articles["item"][$num]["title"];
        $article["link"]  = $articles["item"][$num]["link"];

        Log::info("取得した記事", $article);

        $this->setArticle($article);

        Util::generateLogMessage('END');

        return true;

    }

}