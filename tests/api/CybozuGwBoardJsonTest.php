<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class CybozuGwBoardJsonTest extends TestCase
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
     * JSON形式でのサイボウズへの連携 正常系
     *
     */
    public function testSuccessStoreRequest()
    {

//        "{'access_key': '3r8DMbQPT5gUPT3HVyh5HmTnEtbH1rWh', 'group_name': '検証用グループ', 'topic_name': '検証用トピック', 'text': '自動テストによる投稿 - CybozuGwBoardJsonTest.testSuccessStoreRequest'}",

        $response = $this->json(
          "POST",
          "/api/cgbj/",
          [
            "value1" => [
              'access_key' => "3r8DMbQPT5gUPT3HVyh5HmTnEtbH6rWh",
              'group_name' => "検証用グループ",
              'topic_name' => "検証用トピック",
              'text'       => "自動テストによる投稿 - CybozuGwBoardJsonTest.testSuccessStoreRequest",
            ]
          ],
          array(),
          array('CONTENT_TYPE' => 'application/json'),
          array()
        );

        $response->assertResponseOk();

    }

    /**
     * JSON形式でのサイボウズへの連携 異常系
     *
     */
    public function testFailureStoreRequest()
    {

        $response = $this->json(
          "POST",
          "/api/cgbj/",
          [
            "value1" => [
              'access_key' => "1r8DMbQPT5gUPT3HVyh5HmTnEtbH6rWh",
              'group_name' => "検証用グループ",
              'topic_name' => "検証用トピック",
              'text'       => "自動テストによる投稿 - CybozuGwBoardJsonTest.testSuccessStoreRequest",
            ]
          ],
          array(),
          array('CONTENT_TYPE' => 'application/json'),
          array()
        );

        $response->assertResponseStatus("404");

        $response = $this->json(
          "POST",
          "/api/cgbj/",
          [
            "value1" => [
              'access_key' => "3r8DMbQPT5gUPT3HVyh5HmTnEtbH6rWh",
              'topic_name' => "検証用トピック",
              'text'       => "自動テストによる投稿 - CybozuGwBoardJsonTest.testSuccessStoreRequest",
            ]
          ],
          array(),
          array('CONTENT_TYPE' => 'application/json'),
          array()
        );

        $response->assertResponseStatus("404");

        $response = $this->json(
          "POST",
          "/api/cgbj/",
          [
            "value1" => [
              'access_key' => "3r8DMbQPT5gUPT3HVyh5HmTnEtbH6rWh",
              'group_name' => "検証用グループ",
              'text'       => "自動テストによる投稿 - CybozuGwBoardJsonTest.testSuccessStoreRequest",
            ]
          ],
          array(),
          array('CONTENT_TYPE' => 'application/json'),
          array()
        );

        $response->assertResponseStatus("404");

        $response = $this->json(
          "POST",
          "/api/cgbj/",
          [
            "value1" => [
              'access_key' => "3r8DMbQPT5gUPT3HVyh5HmTnEtbH6rWh",
              'group_name' => "検証用グループ",
              'topic_name' => "検証用トピック",
            ]
          ],
          array(),
          array('CONTENT_TYPE' => 'application/json'),
          array()
        );

        $response->assertResponseStatus("404");

    }

}
