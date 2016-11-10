<?php

use App\Models\OperationLogs;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class OperationLogsTest extends TestCase
{

    use WithoutMiddleware;


    private $validateTrues = [ 
      [
        "screen_number" => '100',
        "target_id"     => '100',
        "executed_at"   => '2015-12-30 00:59:06'
      ],
      [
        "screen_number" => "1",
        "target_id"     => "100",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "9999",
        "target_id"     => "100",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "1",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "99999999999",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "100",
        "executed_at"   => "2000-01-01 00:00:00"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "100",
        "executed_at"   => "2037-12-31 23:59:59"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "100",
        "executed_at"   => "20160701010203"
      ],
    ];


    private $validateFalses = [
      [
        "screen_number" => "",
        "target_id"     => "100",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "a",
        "target_id"     => "100",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "10000",
        "target_id"     => "100",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "a",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "100000000000",
        "executed_at"   => "2016-07-01 01:02:03"
      ],
      [
        "screen_number" => "100",
        "target_id"     => "100",
        "executed_at"   => ""
      ],
      [
        "screen_number" => "100",
        "target_id"     => "100",
        "executed_at"   => "aaaaaaa"
      ],
    ];

    public function setUp()
    {

        parent::setUp();

//        //artisanコマンドの実行
//        Artisan::call('migrate:refresh');

    }

//
//    /**
//     *
//     */
//    public function testValidateScreenNumber()
//    {
//
//        // 正常
//
//        foreach ($this->validateTrues as $input) {
//
//            $result = (new OperationLogs)->validate($input);
//
//            $this->assertTrue($result);
//
//        }
//
//        // 失敗
//        
//        foreach ($this->validateFalses as $input) {
//
//            $result = (new OperationLogs)->validate($input);
//
//            $this->assertFalse($result);
//
//        }
//
//    }
//    /**
//     *
//     */
////    public function testRegisterGetId(){
////
////        $this->withoutMiddleware();
////
////        Factory::create('User', ['age' => 20]);
////        Factory::create('User', ['age' => 30]);
////
////        $faker = Faker\Factory::create('ja_JP');
////        
////        $input["name"]     = 'テスト';
////        $input["kana"]     = 'テスト';
////        $input["email"]    = $faker->unique()->email;
////        $input["password"] = 'password';
////
////        $data = [
////          'screen_number' => 110,
////          'target_id'     => 10,
////          'operator'      => 20,
////          'comment'       => json_encode($input, JSON_UNESCAPED_UNICODE),
////        ];
////
////        $oldest = (new User)->registerGetId($data);
////
////        $this->assertEquals(30, $oldest->age);
////
////        $this->get('/api/users')
////          ->seeJson([
////            'email' => 'dev@se-project.co.jp',
////          ]);
////
////    }


    /**
     * A basic functional test example.
     *
     * @return void
     */
    public function testBasicExample()
    {
        $this->assertTrue(true);
    }
}
