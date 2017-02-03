<?php

namespace App\Console\Commands;

use Log;
use Util;
use App\Services\CybozuLiveService;

use Illuminate\Console\Command;

class PostArtificialIntelligenceTalk extends Command
{

    // php artisan postArtificialIntelligenceTalk

    private $cybozu;

    const SEP_GROUP_NAME = '(株)エス・イー・プロジェクト';
    const SEP_TOPIC_NAME = 'よもやま話が続くトピ';
//    const SEP_GROUP_NAME = '検証用グループ';
//    const SEP_TOPIC_NAME = '検証用トピック';
//    const SEP_GROUP_NAME = '自分用グループ';
//    const SEP_TOPIC_NAME = 'メモするトピ';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postArtificialIntelligenceTalk';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'AI同士の会話をサイボウズLIVEに投稿します';


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

//        $user = [
//          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
//          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
//        ];

        $this->cybozu->setUser($user);
        $this->cybozu->setGroupName(self::SEP_GROUP_NAME);
        $this->cybozu->setTopicName(self::SEP_TOPIC_NAME);

        $this->cybozu->postArtificialIntelligenceTalk();

        Util::generateLogMessage('END');
    }
}
