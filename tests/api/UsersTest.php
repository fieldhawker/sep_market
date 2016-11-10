<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UsersTest extends TestCase
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

//    /**
//     * 
//     */
//    public function testIndex(){
//
//        $this->withoutMiddleware();
//        
//        $this->get('/api/users')
//          ->seeJson([
//            'email' => 'dev@se-project.co.jp',
//          ]);
//        
//    }
        
}
