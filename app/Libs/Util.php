<?php
namespace app\Libs;

use Log;
use Http;
use DateTime;
use DateTimeZone;

class Util
{

    /**
     * Util constructor.
     *
     */
    public function __construct()
    {

    }
    
    /**
     * @param      $message
     * @param null $class
     * @param null $function
     *
     * @return bool
     */
    public function generateLogMessage($message, $class = null, $function = null)
    {
        $trace = debug_backtrace($limit = 2);

        if (is_null($class)) {
            $class = $trace[2]['class'];
        }

        if (is_null($function)) {
            $function = $trace[2]['function'];
        }

        Log::info(sprintf("%s.%s() --- %s", $class, $function, $message));

        return true;

    }


    /**
     * @param $string_date
     *
     * @return null|string
     */
    public function convertUtcToJst($string_date)
    {
        if (is_null($string_date))
        {
            return null;
        }
        
        $t = new DateTime($string_date);
        $t->setTimeZone(new DateTimeZone('Asia/Tokyo'));
        return $t->format('Y-m-d H:i:s');
    }

    /**
     * @param $message
     *
     * @return bool
     */
    public function postSlack($message)
    {
        $url      = 'https://hooks.slack.com/services/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';
        $params   = [
          'channel'    => "#laravel_logs",
          'username'   => "laravel works.",
          'text'       => $message,
          'icon_emoji' => ":ghost:",
        ];
        $response = Http::postJson($url, $params);
        
        return true;
    }
}