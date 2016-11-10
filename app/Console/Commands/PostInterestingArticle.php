<?php

namespace App\Console\Commands;

use Log;
use Util;
use App\Services\CybozuLiveService;

use Illuminate\Console\Command;

class PostInterestingArticle extends Command
{

    private $cl;

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
    public function __construct(CybozuLiveService $cl)
    {
        $this->cl = $cl;

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
        $this->cl->postInterestingArticle();

        Util::generateLogMessage('END');
    }
}
