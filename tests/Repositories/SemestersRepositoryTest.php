<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 11/16/2015
 * Time: 7:59 PM
 */

namespace tests\Repositories;

use Fce\Models\Semester;
use Fce\Repositories\SemestersRepository;

class SemestersRepositoryTest extends \TestCase
{
    protected $semester_model;
    protected $semester_repository;

    public function setUp()
    {
        parent::setUp();
        $this->semester_model = new Semester();
        $this->semester_repository = new SemestersRepository($this->semester_model);
    }

    public function testGetSemesters()
    {
        factory(Semester::class)->create();
        $params = ['query' => null, 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->semester_repository->getSemesters($params);
        $this->assertNotEmpty($result['data']);
    }

    public function testGetSemestersQuery()
    {
        $semester = factory(Semester::class)->create();
        $semester = $semester->toArray();
        $params = [
            'query' => $semester['semester'],
            'order' => 'ASC',
            'sort' => 'created_at',
            'limit' => 5, 'offset' => 1
        ];

        $result = $this->semester_repository->getSemesters($params);
        $this->assertNotEmpty($result['data']);
    }

    public function testGetSemestersNull()
    {
        $params = ['query' => 'x*(', 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->semester_repository->getSemesters($params);
        $this->assertNull($result['data']);
    }

    public function testGetSemestersErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->semester_model = $this->getMock(Semester::class, ['where']);
        $this->semester_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->semester_repository = new SemestersRepository($this->semester_model);
        $this->semester_repository->getSemesters([]);
    }

    public function testSetCurrentSemester()
    {
        $semester = factory(Semester::class)->create();
        $semester = $semester->toArray();

        $result = $this->semester_repository->setCurrentSemester($semester['id']);
        $this->assertTrue($result['data']['current_semester']);
    }

    public function testSetCurrentSemesterFalse()
    {
        $result = $this->semester_repository->setCurrentSemester('fake_id');
        $this->assertFalse($result);
    }

    public function testSetCurrentSemesterFalseErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->semester_model = $this->getMock(Semester::class, ['where']);
        $this->semester_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->semester_repository = new SemestersRepository($this->semester_model);
        $this->semester_repository->setCurrentSemester('fake_id');
    }

    public function testGetCurrentSemester()
    {
        $semester = factory(Semester::class)->create();
        $semester = $semester->toArray();

        $result = $this->semester_repository->setCurrentSemester($semester['id']);
        $this->assertTrue($result['data']['current_semester']);

        $result = $this->semester_repository->getCurrentSemester();
        $this->assertTrue($result['data']['current_semester']);
    }

    public function testGetCurrentSemesterFalse()
    {
        factory(Semester::class)->create();

        $result = $this->semester_repository->getCurrentSemester();
        $this->assertFalse($result);
    }

    public function testGetCurrentSemesterErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->semester_model = $this->getMock(Semester::class, ['where']);
        $this->semester_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->semester_repository = new SemestersRepository($this->semester_model);
        $this->semester_repository->getCurrentSemester();
    }

    public function testCreateSemester()
    {
        $data = ["semester" => "Fall 2015", "current_semester" => false];

        $result = $this->semester_repository->createSemester($data);
        $this->assertNotEmpty($result['data']);
        $this->assertEquals($data['semester'], $result['data']['semester']);
    }

    public function testCreateSemesterErrorException()
    {
        $this->setExpectedException('\Exception');

        $data = ["semester" => "Fall 2015", "current_semester" => false];

        $this->semester_model = $this->getMock(Semester::class, ['save']);
        $this->semester_model->expects($this->any())->method('save')->willThrowException(new \Exception);

        $this->semester_repository = new SemestersRepository($this->semester_model);
        $this->semester_repository->createSemester($data);
    }
}
