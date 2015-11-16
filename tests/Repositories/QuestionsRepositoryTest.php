<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 11/16/2015
 * Time: 9:39 PM
 */

namespace tests\Repositories;

use Fce\Models\Question;
use Fce\Models\QuestionSet;
use Fce\Repositories\QuestionsRepository;

class QuestionsRepositoryTest extends \TestCase
{
    protected $question_set_model;
    protected $question_model;
    protected $question_repository;

    public function setUp()
    {
        parent::setUp();
        $this->question_set_model = new QuestionSet();
        $this->question_model = new Question();
        $this->question_repository = new QuestionsRepository($this->question_set_model, $this->question_model);
    }

    public function testGetQuestionsByQuestionSet()
    {
        $params = ['query' => null, 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->question_repository->getQuestionsByQuestionSet($params);
        $this->assertNotEmpty($result['data']);
    }

    public function testGetQuestionsByQuestionSetQuery()
    {
        $params = [
            'query' => 'm',
            'order' => 'ASC',
            'sort' => 'created_at',
            'limit' => 5, 'offset' => 1
        ];

        $result = $this->question_repository->getQuestionsByQuestionSet($params);
        $this->assertNotEmpty($result['data']);
    }

    public function testGetQuestionsByQuestionSetNull()
    {
        $params = ['query' => 'x*(', 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->question_repository->getQuestionsByQuestionSet($params);
        $this->assertNull($result['data']);
    }

    public function testGetQuestionsByQuestionSetErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->question_set_model = $this->getMock(QuestionSet::class, ['where']);
        $this->question_set_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->question_repository = new QuestionsRepository($this->question_set_model, $this->question_model);
        $this->question_repository->getQuestionsByQuestionSet([]);
    }

    public function testGetQuestionsByQuestionSetId()
    {
        $result = $this->question_repository->getQuestionsByQuestionSetId(1);
        $this->assertEquals(1, $result['data']['id']);
    }

    public function testGetQuestionsByQuestionSetIdFalse()
    {
        $result = $this->question_repository->getQuestionsByQuestionSetId('fake_id');
        $this->assertFalse($result);
    }

    public function testGetQuestionsByQuestionSetIdErrorException()
    {
        $this->setExpectedException('\Exception');

        $this->question_set_model = $this->getMock(QuestionSet::class, ['where']);
        $this->question_set_model->expects($this->any())->method('where')->willThrowException(new \Exception);

        $this->question_repository = new QuestionsRepository($this->question_set_model, $this->question_model);
        $this->question_repository->getQuestionsByQuestionSetId('fake_id');
    }
}