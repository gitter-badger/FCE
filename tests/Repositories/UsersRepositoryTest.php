<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 11/23/2015
 * Time: 6:17 PM
 */

namespace tests\Repositories;

use Fce\Models\Section;
use Fce\Models\Semester;
use Fce\Models\User;
use Fce\Repositories\UsersRepository;

class UsersRepositoryTest extends \TestCase
{
    protected $user_model;
    protected $section_model;
    protected $user_repository;

    public function setUp()
    {
        parent::setUp();
        $this->user_model = new User();
        $this->section_model = new Section();
        $this->user_repository = new UsersRepository($this->user_model, $this->section_model);
    }

    public function testGetUsers()
    {
        factory(User::class)->create();
        $params = ['query' => null, 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->user_repository->getUsers($params);
        $this->assertNotEmpty($result['data']);
    }

    public function testGetUsersQuery()
    {
        $users = factory(User::class)->create();
        $users = $users->toArray();
        $params = [
            'query' => $users['name'],
            'order' => 'ASC',
            'sort' => 'created_at',
            'limit' => 5, 'offset' => 1
        ];

        $result = $this->user_repository->getUsers($params);
        $this->assertNotEmpty($result['data']);
    }

    public function testGetUsersNull()
    {
        $params = ['query' => 'x*(', 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->user_repository->getUsers($params);
        $this->assertNull($result['data']);
    }

    public function testGetUsersErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->user_model = $this->getMock(User::class, ['where']);
        $this->user_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->user_repository = new UsersRepository($this->user_model, $this->section_model);
        $this->user_repository->getUsers([]);
    }

    public function testGetUserById()
    {
        $user = factory(User::class)->create();
        $user = $user->toArray();

        $result = $this->user_repository->getUserById($user['id']);
        $this->assertEquals($user['id'], $result['data']['id']);
    }

    public function testGetUserByIdFalse()
    {
        $result = $this->user_repository->getUserById('fake_id');
        $this->assertFalse($result);
    }

    public function testGetUserByIdErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->user_model = $this->getMock(User::class, ['where']);
        $this->user_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->user_repository = new UsersRepository($this->user_model, $this->section_model);
        $this->user_repository->getUserById('fake_id');
    }

    public function testGetUserBySectionId()
    {
        factory(Semester::class)->create();
        $user = factory(User::class, 2)->create();
        $section = factory(Section::class)->create();

        $section = $section->toArray();

        $result = $this->user_repository->getUserBySectionId($section['id']);
        $this->assertEquals($section['id'], $result['data'][0]['section_id']);
    }

    public function testGetUserBySectionIdFalse()
    {
        $result = $this->user_repository->getUserBySectionId('fake_id');
        $this->assertFalse($result);
    }

    public function testGetUserBySectionIdErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->user_model = $this->getMock(User::class, ['wherePivot']);
        $this->user_model->expects($this->any())->method('wherePivot')->willThrowException(new \Exception);

        $this->user_repository = new UsersRepository($this->user_model, $this->section_model);
        $this->user_repository->getUserBySectionId('fake_id');
    }
}