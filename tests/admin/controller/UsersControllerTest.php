<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersControllerTest extends TestCase
{
    use DatabaseMigrations;

    /**
     *
     */
    function setUp()
    {

        parent::setUp();

    }

//    /**
//     *  リダイレクトの確認
//     *
//     * @return void
//     */
//    public function testRedirectPage()
//    {
//        // 未ログインはログイン画面にリダイレクト
//        $this->visit('/admin/')->see('Admin Login');
//        $this->visit('/admin/users/create/')->see('Admin Login');
//        $this->visit('/admin/users/999')->see('Admin Login');
//        $this->visit('/admin/users/999/edit/')->see('Admin Login');
//
//    }
//
//    /**
//     * 一覧画面表示
//     *
//     * @return void
//     */
//    public function testListPage()
//    {
//
//        // ログインしていたら一覧画面を表示
//        $admin = factory(App\Models\Admin::class)->create();
//        $this->actingAs($admin, 'admin');
//
//        $this->visit('/admin/users')
//          ->seeStatusCode(200)
//          ->see('会員一覧');
//
//    }
//
//    /**
//     *
//     */
//    public function test会員登録()
//    {
//
//        // ログインする
//        $admin = factory(App\Models\Admin::class)->create();
//        $this->actingAs($admin, 'admin');
//
//        // 会員を登録する
//        $this->visit('/admin/users/create/')
//          // 画面表示に成功しているか
//          ->see('会員登録')
//          // 初期値の確認
//          ->seeInField('name', '')
//          ->seeInField('kana', '')
//          ->seeInField('email', '')
//          ->seeInField('password', '')
//          // テストデータの入力
//          ->type('テスト', 'name')
//          ->type('テスト', 'kana')
//          ->type('takano@se-project.co.jp', 'email')
//          ->type('takanotakano', 'password')
//          ->press('登録')
//          ->seeStatusCode(200)
//          ->dontSee('エラーが発生しました!')
//          ->see('登録が完了しました。');
//
//        $this->seeInDatabase('users',['email'=>'takano@se-project.co.jp']);
//
//    }
//
//    public function test会員編集()
//    {
//
//        // ログインする
//        $admin = factory(App\Models\Admin::class)->create();
//        $this->actingAs($admin, 'admin');
//        
//        // テスト会員を生成
//        $user = factory(App\Models\User::class)->create();
//        
//        // 一意な入力情報を生成
//        $faker = Faker\Factory::create('ja_JP');
//        $email = $faker->unique()->email;
//        
//        $url = sprintf('/admin/users/%s/edit/', $user->id);
//
//        // 会員を編集する
//        $this->visit($url)
//          // 画面表示に成功しているか
//          ->see('会員編集')
//          // 初期値の確認
//          ->seeInField('name', $user->name)
//          ->seeInField('kana', $user->kana)
//          ->seeInField('email', $user->email)
//          // テストデータの入力
//          ->type('テスト', 'name')
//          ->type('テスト', 'kana')
//          ->type($email, 'email')
//          ->press('編集')
//          ->seeStatusCode(200)
//          ->dontSee('エラーが発生しました!')
//          ->see('編集が完了しました。');
//        
//
//
//    }
//
//    public function test会員削除()
//    {
//
//        // ログインする
//        $admin = factory(App\Models\Admin::class)->create();
//        $this->actingAs($admin, 'admin');
//
//        // テスト会員を生成
//        $user = factory(App\Models\User::class)->create();
//        
//        $url = sprintf('/admin/users/%s', $user->id);
//
//    }

}
