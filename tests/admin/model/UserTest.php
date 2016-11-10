<?php

use App\Models\User;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{

    use WithoutMiddleware;

    public function setUp(){
        
        parent::setUp();

//        //artisanコマンドの実行
//        Artisan::call('migrate:refresh');
////        Artisan::call('db:seed');
        
    }
    
//    /**
//     *
//     */
//    public function testRegisterGetIdName()
//    {
//
//        $this->withoutMiddleware();
//
////        Factory::create('User', ['age' => 20]);
////        Factory::create('User', ['age' => 30]);
//
//        $faker = Faker\Factory::create('ja_JP');
//
//        // 正常
//
//        $input["name"]     = 'テスト';
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertTrue(is_numeric($id));
//
//        // name is null
//
//        $input["name"]     = '';
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // name undefined
//
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // name length 1
//
//        $input["name"]     = $this->makeRandStr(1);
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // name length 2
//
//        $input["name"]     = $this->makeRandStr(2);
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertTrue(is_numeric($id));
//
//        // name length 3
//
//        $input["name"]     = $this->makeRandStr(3);
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertTrue(is_numeric($id));
//
//        // name length 256
//
//        $input["name"]     = $this->makeRandStr(256);
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertTrue(is_numeric($id));
//
//        // name length 257
//
//        $input["name"]     = $this->makeRandStr(257);
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//    }
//
//
//    /**
//     *
//     */
//    public function testRegisterGetIdKana()
//    {
//
//        $this->withoutMiddleware();
//
//        $faker = Faker\Factory::create('ja_JP');
//
//        // 正常
//
//        $input["name"]     = 'テスト';
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertTrue(is_numeric($id));
//
//        // kana is null
//
//        $input["kana"]     = '';
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // kana undefined
//
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // kana length 1
//
//        $input["kana"]     = $this->makeRandStr(1);
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // kana length 2
//
//        $input["kana"]     = $this->makeRandStr(2);
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertTrue(is_numeric($id));
//
//        // kana length 3
//
//        $input["kana"]     = $this->makeRandStr(3);
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertTrue(is_numeric($id));
//
//        // kana length 256
//
//        $input["kana"]     = $this->makeRandStr(256);
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//        
////        echo $input["kana"];
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertTrue(is_numeric($id));
//
//        // kana length 257
//
//        $input["kana"]     = $this->makeRandStr(257);
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//    }
//
//    public function testRegisterGetIdMail()
//    {
//
//        $this->withoutMiddleware();
//
//        $faker = Faker\Factory::create('ja_JP');
//
//        // email is null
//
//        $input["kana"]     = 'テスト';
//        $input["name"]     = 'テスト';
//        $input["email"]    = '';
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // email undefined
//
//        $input["kana"]     = 'テスト';
//        $input["name"]     = 'テスト';
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // email is not mail
//
//        $input["kana"]     = 'テスト';
//        $input["name"]     = 'テスト';
//        $input["email"]    = 'aaaaaaaa';
//        $input["password"] = 'password';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//    }
//
//
//    public function testRegisterGetIdPass()
//    {
//
//        $this->withoutMiddleware();
//
//        $faker = Faker\Factory::create('ja_JP');
//
//        // password length 5
//
//        $input["kana"]     = 'テスト';
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'passw';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // password  is null
//
//        $input["kana"]     = 'テスト';
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = '';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//        // password length 5
//
//        $input["kana"]     = 'テスト';
//        $input["name"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = 'passw';
//
//        $id = (new User)->registerGetId($input);
//
//        $this->assertFalse($id);
//
//    }
//
//    public function testupdateUsers()
//    {
//
//        $this->withoutMiddleware();
//
//        $faker = Faker\Factory::create('ja_JP');
//
//        $input["name"]     = 'テスト';
//        $input["kana"]     = 'テスト';
//        $input["email"]    = $faker->unique()->email;
//        $input["password"] = "password";
//        
//        $id = (new User)->registerGetId($input);
//
//        // 正常
//
//        $input          = null;
//        $input["name"]  = 'テスト2';
//        $input["kana"]  = 'テスト2';
//        $input["email"] = $faker->unique()->email;
//
//        $result = (new User)->updateUsers($input, $id);
//
//        $this->assertTrue($result);
//
//    }

    /**
     * A basic functional test example.
     *
     * @return void
     */
//    public function testBasicExample()
//    {
//        $this->visit('/')
//             ->see('Laravel 5');
//    }
}
