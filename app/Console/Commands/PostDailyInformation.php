<?php

namespace App\Console\Commands;

use Log;
use Util;
use App\Services\CybozuLiveService;

use Illuminate\Console\Command;

class PostDailyInformation extends Command
{

    // php artisan postdailyinformation

    private $cybozu;

//    const SEP_GROUP_NAME = '(株)エス・イー・プロジェクト';
//    const SEP_TOPIC_NAME = '気になるワードをメモるトピ';
    const SEP_GROUP_NAME = '検証用グループ';
    const SEP_TOPIC_NAME = '検証用トピック';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postdailyinformation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '毎日のトピックスをサイボウズLIVEに投稿します';


    /**
     * PostDailyInformation constructor.
     *
     * @param CybozuLiveService $cybozu
     */
    public function __construct(CybozuLiveService $cybozu)
    {
        $this->cybozu = $cybozu;

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Util::generateLogMessage('START');

        // 記事の投稿
        $user = [
          'x_auth_username' => env('CYBOZULIVE_USER_NAME_DEV'),
          'x_auth_password' => env('CYBOZULIVE_PASSWORD_DEV'),
        ];

        $this->cybozu->setUser($user);
        $this->cybozu->setGroupName(self::SEP_GROUP_NAME);
        $this->cybozu->setTopicName(self::SEP_TOPIC_NAME);

        $this->cybozu->PostDailyInformation();

        Util::generateLogMessage('END');
    }
}
