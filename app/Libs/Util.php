<?php
namespace app\Libs;

use Log;

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


}