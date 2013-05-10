<?php

namespace Douche\Storage\File;

use Douche\Entity\User;

class UserRepositoryTest extends \PHPUnit_Framework_TestCase
{
    /** @test */
    public function findOnEmptyRepoShouldThrowUserNotFound()
    {
        $repo = new UserRepository(__DIR__.'/Fixtures/non_existent.json');
        $user = $repo->find('missing');

        $this->assertNull($user);
    }

    /** @test */
    public function findShouldThrowOnMissingUser()
    {
        $repo = new UserRepository(__DIR__.'/Fixtures/users.json');
        $user = $repo->find('missing');

        $this->assertNull($user);
    }

    /** @test */
    public function findShouldReturnExistingUser()
    {
        $repo = new UserRepository(__DIR__.'/Fixtures/users.json');
        $user = $repo->find('igorw');

        $expectedUser = new User('igorw', 'Igor Wiedler', 'igor@wiedler.ch', 'FOOBAR');
        $this->assertEquals($expectedUser, $user);
    }

    /** @test */
    public function findOneByEmailShouldThrowOnMissingUser()
    {
        $repo = new UserRepository(__DIR__.'/Fixtures/users.json');
        $user = $repo->findOneByEmail('john.doe@example.com');

        $this->assertNull($user);
    }

    /** @test */
    public function findOneByEmailShouldReturnExistingUser()
    {
        $repo = new UserRepository(__DIR__.'/Fixtures/users.json');
        $user = $repo->findOneByEmail('igor@wiedler.ch');

        $expectedUser = new User('igorw', 'Igor Wiedler', 'igor@wiedler.ch', 'FOOBAR');
        $this->assertEquals($expectedUser, $user);
    }

    /** @test */
    public function addShouldAddUserInMemory()
    {
        $repo = new UserRepository(__DIR__.'/Fixtures/users.json');
        $john = new User('johndoe', 'John Doe', 'john.doe@example.com', 'BARFOO');
        $repo->add($john);

        $this->assertEquals($john, $repo->find('johndoe'));
    }

    /** @test */
    public function saveShouldStoreChangesInFile()
    {
        $file = tempnam(sys_get_temp_dir(), 'doucheswag_test_');

        $repo = new UserRepository($file);
        $john = new User('johndoe', 'John Doe', 'john.doe@example.com', 'BARFOO');
        $repo->add($john);
        $repo->save();

        $repo = new UserRepository($file);
        $this->assertEquals($john, $repo->find('johndoe'));

        unlink($file);
    }
}
