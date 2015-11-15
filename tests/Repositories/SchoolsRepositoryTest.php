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
}