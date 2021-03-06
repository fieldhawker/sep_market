<?php

namespace App\Console\Commands;

use Log;
use Util;
use App\Services\CybozuLiveService;

use Illuminate\Console\Command;

class PostInterestingArticle extends Command
{

    // php artisan postinterestingarticle

    private $cybozu;

    const SEP_GROUP_NAME = '(株)エス・イー・プロジェクト';
    const SEP_TOPIC_NAME = '気になるワードをメモるトピ';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'postinterestingarticle';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '興味深い記事を1件取得し、サイボウズLIVEに投稿します';


    /**
     * PostInterestingArticle constructor.
     *
     * @param CybozuLiveService $cl
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
          'x_auth_username' => env('CYBOZULIVE_USER_NAME'),
          'x_auth_password' => env('CYBOZULIVE_PASSWORD'),
        ];

        $this->cybozu->setUser($user);
        $this->cybozu->setGroupName(self::SEP_GROUP_NAME);
        $this->cybozu->setTopicName(self::SEP_TOPIC_NAME);

        $this->cybozu->postInterestingArticle();

        Util::generateLogMessage('END');
    }
}
