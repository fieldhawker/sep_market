<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        // Commands\Inspire::class,
        \App\Console\Commands\PostInterestingArticle::class,    // サイボウズLIVEへの興味深い記事の投稿
        \App\Console\Commands\PostDailyInformation::class,      // 毎日のトピックスをサイボウズLIVEに投稿します
        \App\Console\Commands\postArtificialIntelligenceTalk::class,      // AI同士の会話をサイボウズLIVEに投稿します
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')
        //          ->hourly();

//        $schedule->command('postinterestingarticle')
//          ->dailyAt('0:25');
        
    }
}
