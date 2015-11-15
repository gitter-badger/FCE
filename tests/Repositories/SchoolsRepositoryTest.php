<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 11/15/2015
 * Time: 11:40 AM
 */

namespace tests\Repositories;

use Fce\Models\School;
use Fce\Repositories\SchoolsRepository;

class SchoolsRepositoryTest extends \TestCase
{
    protected $school_model;
    protected $school_repository;

    public function setUp()
    {
        parent::setUp();
        $this->school_model = new School();
        $this->school_repository = new SchoolsRepository($this->school_model);
    }

    public function testGetSchools()
    {
        $params = ['query' => null, 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->school_repository->getSchools($params);
        $this->assertNotEmpty($result['data']);
    }

    public function testGetSchoolsQuery()
    {
        $params = ['query' => 's', 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->school_repository->getSchools($params);
        $this->assertNotEmpty($result['data']);
    }

    public function testGetSchoolsNull()
    {
        $params = ['query' => 'x*(', 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->school_repository->getSchools($params);
        $this->assertNull($result['data']);
    }

    public function testGetSchoolsErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->school_model = $this->getMock(School::class, ['where']);
        $this->school_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->school_repository = new SchoolsRepository($this->school_model);
        $this->school_repository->getSchools([]);
    }

    public function testGetSchoolById()
    {
        $result = $this->school_repository->getSchoolById(1);
        $this->assertNotEmpty($result['data']);
        $this->assertEquals(1, $result['data']['id']);
    }

    public function testGetSchoolByIdFalse()
    {
        $result = $this->school_repository->getSchoolById('fake_id');
        $this->assertFalse($result);
    }

    public function testGetSchoolByIdFalseErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->school_model = $this->getMock(School::class, ['where']);
        $this->school_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->school_repository = new SchoolsRepository($this->school_model);
        $this->school_repository->getSchoolById('fake_id');
    }

    public function testCreateSchool()
    {
        $data = ['school' => 'LAW', 'description' => 'School of Law'];
        $result = $this->school_repository->createSchool($data);
        $this->assertNotEmpty($result['data']);
        $this->assertEquals('LAW', $result['data']['school']);
    }

    public function testCreateSchoolErrorException()
    {
        $this->setExpectedException('\Exception');

        $data = ['school' => 'LAW', 'description' => 'School of Law'];

        $this->school_model = $this->getMock(School::class, ['save']);
        $this->school_model->expects($this->any())->method('save')->willThrowException(new \Exception);

        $this->school_repository = new SchoolsRepository($this->school_model);
        $this->school_repository->createSchool($data);
    }

    public function testUpdateSchoolById()
    {
        $data = ['school_id' => 1, 'school' => 'LAW'];
        $result = $this->school_repository->updateSchoolById($data);
        $this->assertNotEmpty($result['data']);
        $this->assertEquals('LAW', $result['data']['school']);
    }

    public function testUpdateSchoolByIdFalse()
    {
        $data = ['school_id' => 'fake_id', 'school' => 'LAW'];

        $result = $this->school_repository->updateSchoolById($data);
        $this->assertFalse($result);
    }

    public function testUpdateSchoolByIdErrorException()
    {
        $this->setExpectedException('\Exception');

        $data = ['school_id' => 1, 'school' => 'LAW'];

        $this->school_model = $this->getMock(School::class, ['where']);
        $this->school_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->school_repository = new SchoolsRepository($this->school_model);
        $this->school_repository->updateSchoolById($data);
    }
}