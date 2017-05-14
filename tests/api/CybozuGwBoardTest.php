<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CybozuGwBoardTest extends TestCase
{

    use WithoutMiddleware;
    
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     *
     */
    public function testStore()
    {
//        $response = $this->call('POST', '/api/cgb/');
//        $check_result = json_decode($response->getContent(), true);
//        $this->assertEquals(['result' => true], $check_result);
    }

    /**
     * JSON形式でのサイボウズへの連携 正常系
     *
     */
    public function testSuccessStoreRequest()
    {
        $response = $this->post(
          "/api/cgb/",
          [
            'group_name' => "検証用グループ",
            'topic_name' => "検証用トピック",
            'text'       => "自動テストによる投稿 - CybozuGwBoardTest.testSuccessStoreRequest",
          ]
        );

        $response->assertResponseOk();

    }

    /**
     * JSON形式でのサイボウズへの連携 異常系
     *
     */
    public function testFailureStoreRequest()
    {

        $response = $this->post(
          "/api/cgb/",
          [
            'topic_name' => "検証用トピック",
            'text'       => "自動テストによる投稿 - CybozuGwBoardTest.testSuccessStoreRequest",
          ]
        );

        $response->assertResponseStatus("404");

        $response = $this->post(
          "/api/cgb/",
          [
            'group_name' => "検証用グループ",
            'text'       => "自動テストによる投稿 - CybozuGwBoardTest.testSuccessStoreRequest",
          ]
        );

        $response->assertResponseStatus("404");

        $response = $this->post(
          "/api/cgb/",
          [
            'group_name' => "検証用グループ",
            'topic_name' => "検証用トピック",
          ]
        );

        $response->assertResponseStatus("404");

    }
        
}
