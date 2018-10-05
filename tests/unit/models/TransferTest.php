<?php
namespace models;

use app\models\User;
use app\fixtures\UserFixture;

class TransferTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
        $this->tester->haveFixtures([
            'users' => [
                'class' => UserFixture::className(),
                'dataFile' => codecept_data_dir() . 'users.php'
            ]
        ]);
    }

    protected function _after()
    {
    }

    // tests
//    public function testSomeFeature()
//    {
//
//    }

    public function testCreateTransfer()
    {
        $user1 = User::findOne(['username' => 'name1']);
        expect_that($user1 !==  null);
        $user2 = User::findOne(['username' => 'name2']);
        expect_that($user2 !==  null);

        $sum = 526.34;
        $balance1 = $user1->balance - $sum;
        $balance2 = $user2->balance + $sum;
        $user1->balance = $balance1;
        expect_that($user1->save());
        $user2->balance = $balance2;
        expect_that($user2->save());

        $user1update = User::findOne(['username' => 'name1', 'balance' => $balance1]);
        expect_that($user1update !==  null);
        $user2update = User::findOne(['username' => 'name2', 'balance' => $balance2]);
        expect_that($user2update !==  null);
    }
}