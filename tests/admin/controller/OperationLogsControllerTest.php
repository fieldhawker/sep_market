<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OperationLogsControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function testExample()
    {
        $this->assertTrue(true);
    }
    /**
     * 画面表示
     *
     * @return void
     */
//    public function testViewPage()
//    {
//        // 未ログインはログイン画面にリダイレクト
//        $this->visit('/admin/')->see('Admin Login');
//        
//        // ログインしていたら管理画面を表示
//        $this->withoutMiddleware();
//        $this->visit('/admin/ope')->see('操作ログ一覧');
//
//    }


}
