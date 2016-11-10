<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExclusivesControllerTest extends TestCase
{
    use DatabaseMigrations;
    
//    /**
//     * 画面表示
//     *
//     * @return void
//     */
//    public function testViewPage()
//    {
//        // 未ログインはログイン画面にリダイレクト
//        $this->visit('/admin/')->see('Admin Login');
//
//        // ログインしていたら管理画面を表示
//        $this->withoutMiddleware();
//        $this->visit('/admin/exc')->see('排他制御一覧');
//
//    }


}
