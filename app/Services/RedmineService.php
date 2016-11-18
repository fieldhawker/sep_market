<?php
namespace App\Services;

use Log;
use Util;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface as GuzzleClientInterface;

/**
 * Class RedmineService
 */
class RedmineService
{

    private $ticket_info;
    private $client;

    const GET_TICKET_INFO_URL = 'https://redmine.se-project.co.jp/redmine/issues.xml';
    const REDMINE_API_KEY     = '2ebdda02980b6be55aeb76842fc89a0e624ead00';


    /**
     * RedmineService constructor.
     *
     * @param GuzzleClientInterface|null $client
     */
    public function __construct(GuzzleClientInterface $client = null)
    {

        Util::generateLogMessage('START');

        $this->client = $client ?: new Client();

        Util::generateLogMessage('END');

    }


    /**
     * チケットをアクセサに設定
     *
     * @return bool
     */
    public function setTicketData()
    {

        Util::generateLogMessage('START');

        // チケットを取得
        $response_ticket = $this->requestTicket();

        // 必要な項目を抽出
        $ticket_info = $this->createTicketInfo($response_ticket);

        // 更新日時が昨日以降のチケットを抽出
        $ticket_info = $this->getUpdatedTicket($ticket_info);

        // アクセサに設定
        $this->setTicketInfo($ticket_info);

        Util::generateLogMessage('END');

        return true;

    }

    /**
     * チケットを取得します
     *
     * @return mixed
     * @throws \Exception
     */
    public function requestTicket()
    {

        Util::generateLogMessage('START');

        $offset      = '0';
        $limit       = '100';
        $total_count = '101';
        $ticket      = array();

        while ($total_count > $offset + $limit) {
            $response    = $this->requestTicketPerPage($offset, $limit);
            $total_count = $response['@attributes']['total_count'];
            $ticket      = array_merge($ticket, $response['issue']);
            $offset      = $offset + $limit;
        }

        Log::info("取得したチケット情報", $ticket);

        Util::generateLogMessage('END');

        return $ticket;

    }

    /**
     * @return mixed
     */
    public function getTicketInfo()
    {
        return $this->ticket_info;
    }

    /**
     * @param mixed $ticket_info
     */
    public function setTicketInfo($ticket_info)
    {
        $this->ticket_info = $ticket_info;
    }

    /**
     * リクエスト結果から必要な情報のみを抽出
     *
     * @param $tickets
     *
     * @return mixed
     */
    public function createTicketInfo($tickets)
    {

        Util::generateLogMessage('START');

        $result = array();

        foreach ($tickets as $key => $ticket) {

            $project     = (isset($ticket['project']['@attributes']['name']))
              ? $ticket['project']['@attributes']['name'] : null;
            $author      = (isset($ticket['author']['@attributes']['name']))
              ? $ticket['author']['@attributes']['name'] : null;
            $assigned_to = (isset($ticket['assigned_to']['@attributes']['name']))
              ? $ticket['assigned_to']['@attributes']['name'] : null;

            $result[$key]['id']          = (isset($ticket['id'])) ? $ticket['id'] : null;
            $result[$key]['project']     = $project;
            $result[$key]['author']      = $author;
            $result[$key]['assigned_to'] = $assigned_to;
            $result[$key]['subject']     = (isset($ticket['subject'])) ? $ticket['subject'] : null;
            $result[$key]['description'] = (isset($ticket['description'])) ? $ticket['description'] : null;
            $result[$key]['start_date']  = (isset($ticket['start_date'])) ? $ticket['start_date'] : null;
            $result[$key]['due_date']    = (isset($ticket['due_date'])) ? $ticket['due_date'] : null;
            $result[$key]['done_ratio']  = (isset($ticket['done_ratio'])) ? $ticket['done_ratio'] : null;
            $result[$key]['created_on']  = (isset($ticket['created_on'])) ? $ticket['created_on'] : null;
            $result[$key]['updated_on']  = (isset($ticket['updated_on'])) ? $ticket['updated_on'] : null;

            $result[$key]['id']          = ($result[$key]['id']) ? $result[$key]['id'] : null;
            $result[$key]['project']     = ($result[$key]['project']) ? $result[$key]['project'] : null;
            $result[$key]['author']      = ($result[$key]['author']) ? $result[$key]['author'] : null;
            $result[$key]['assigned_to'] = ($result[$key]['assigned_to']) ? $result[$key]['assigned_to'] : null;
            $result[$key]['subject']     = ($result[$key]['subject']) ? $result[$key]['subject'] : null;
            $result[$key]['description'] = ($result[$key]['description']) ? $result[$key]['description'] : null;
            $result[$key]['start_date']  = ($result[$key]['start_date']) ? $result[$key]['start_date'] : null;
            $result[$key]['due_date']    = ($result[$key]['due_date']) ? $result[$key]['due_date'] : null;
            $result[$key]['done_ratio']  = ($result[$key]['done_ratio']) ? $result[$key]['done_ratio'] : null;
            $result[$key]['created_on']  = ($result[$key]['created_on']) ? $result[$key]['created_on'] : null;
            $result[$key]['updated_on']  = ($result[$key]['updated_on']) ? $result[$key]['updated_on'] : null;

        }

        Log::info("出力に使用するチケット情報", $result);

        Util::generateLogMessage('END');

        return $result;

    }

    /**
     * 指定件数分だけチケット情報を取得する
     *
     * @param string $offset
     * @param string $limit
     *
     * @return mixed
     * @throws \Exception
     */
    public function requestTicketPerPage($offset = '0', $limit = '100')
    {
        $res = $this->client->request('GET', self::GET_TICKET_INFO_URL, [
          'query'  => [
            'key'       => self::REDMINE_API_KEY,
            'status_id' => 'open',
            'offset'    => $offset,
            'limit'     => $limit,
          ],
          'verify' => false
        ]);

        if ($res->getStatusCode() !== 200) {
            throw new \Exception($res->getBody(), $res->getStatusCode());
        }

        $xml    = simplexml_load_string($res->getBody());
        $json   = json_encode($xml);
        $ticket = json_decode($json, true);

        return $ticket;
    }


    /**
     * 一日前から更新されたチケットだけに限定
     *
     * @param $ticket_info
     *
     * @return array
     */
    public function getUpdatedTicket($ticket_info)
    {
        $result    = [];
        $yesterday = gmdate('Y-m-d\TH:i:s\Z', strtotime('-1 day'));

        Log::info("チケット出力基準日時", ['yesterday' => $yesterday]);

        foreach ($ticket_info as $ticket) {

            if ($ticket['project'] == '[sepima]') {
                continue;
            }
            
            if ($ticket['updated_on'] > $yesterday) {
                $result[] = $ticket;
            }
        }

        return $result;
    }
}