<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 11/10/2015
 * Time: 7:17 PM
 */

namespace tests\Repositories;

use Fce\Models\Evaluation;
use Fce\Models\Key;
use Fce\Models\Section;
use Fce\Repositories\EvaluationsRepository;

class EvaluationsRepositoryTest extends \TestCase
{
    protected $evaluation_model;
    protected $section_model;
    protected $key_model;
    protected $evaluation_repository;

    public function setUp()
    {
        parent::setUp();
        $this->evaluation_model = new Evaluation();
        $this->section_model = new Section();
        $this->key_model = new Key();
        $this->evaluation_repository = new EvaluationsRepository(
            $this->evaluation_model,
            $this->section_model,
            $this->key_model
        );
    }

    public function testGetEvaluationSections()
    {
        $params = ['query' => 'fake_query', 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->evaluation_repository->getEvaluationSections($params);
        $this->assertEmpty($result);
    }

    public function testGetEvaluationSectionsNull()
    {
        $params = ['query' => null, 'order' => 'ASC', 'sort' => 'created_at', 'limit' => 5, 'offset' => 1];

        $result = $this->evaluation_repository->getEvaluationSections($params);
        $this->assertNotEmpty($result);
    }
}
