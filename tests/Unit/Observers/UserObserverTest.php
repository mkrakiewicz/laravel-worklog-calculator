<?php

namespace tests\Unit\Observers;


use App\Models\User;
use App\Observers\UserObserver;
use Illuminate\Support\Facades\Hash;
use Mockery;
use tests\TestCase;

/**
 * Class UserObserverTest
 * @package Tests\Unit\Observers
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class UserObserverTest extends \PHPUnit_Framework_TestCase
{
    private $password = 'something';
    private $hashedPassword = 'hashed';

    /**
     * @runTestsInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testCreate()
    {
        $user = $this->getUserMock($this->hashedPassword,$this->hashedPassword);
        $observer = new UserObserver($this->getBcryptHasherMock(true, $this->hashedPassword));
        $observer->creating($user);
        $this->assertEquals($this->hashedPassword, $user->password);
    }
    /**
     * @runTestsInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testUpdateNew()
    {
        $user = $this->getUserMock($this->hashedPassword,$this->hashedPassword);
        $observer = new UserObserver($this->getBcryptHasherMock(true, $this->hashedPassword));
        $observer->updating($user);
        $this->assertEquals($this->hashedPassword, $user->password);
    }
    /**
     * @runTestsInSeparateProcesses
     * @preserveGlobalState disabled
     */
    public function testUpdateOld()
    {
        $user = $this->getUserMock($this->password,$this->hashedPassword);
        $observer = new UserObserver($this->getBcryptHasherMock(false, $this->hashedPassword));
        $observer->updating($user);
        $this->assertEquals($this->password, $user->password);
    }
    /**
     * @runTestsInSeparateProcesses
     * @preserveGlobalState disabled
     */
    private function getUserMock($returnPassword,$setPassword)
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('setAttribute')->with('password',$setPassword);
        $user->shouldReceive('getAttribute')->andReturn($returnPassword);
        return $user;
    }

    private function getBcryptHasherMock($isNewPassword, $hashedValue)
    {
        $mock = $this->getMockBuilder('Illuminate\Hashing\BcryptHasher')->disableOriginalConstructor()->getMock();
        $mock->expects($this->once())->method('check')->willReturn($isNewPassword);
        $mock->expects($this->exactly($isNewPassword ? 2 : 1))->method('make')->willReturn($hashedValue);
        return $mock;
    }
}
