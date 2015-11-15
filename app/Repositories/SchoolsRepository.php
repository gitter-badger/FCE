<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 10/29/2015
 * Time: 8:34 PM
 */

namespace Fce\Repositories;

use Fce\Models\School;
use Fce\Transformers\SchoolTransformer;
use Illuminate\Pagination\Paginator;

class SchoolsRepository extends AbstractRepository implements ISchoolsRepository
{
    protected $school_model;

    public function __construct(School $school)
    {
        $this->school_model = $school;
    }

    public function getSchools($data)
    {
        try {
            Paginator::currentPageResolver(
                function () use ($data) {
                    return $data['offset'];
                }
            );

            if (is_null($data['query'])) {
                $schools = $this->school_model->orderBy($data['sort'], $data['order'])
                    ->paginate($data['limit']);
            } else {
                $schools = $this->school_model->where('school', 'like', '%'.$data['query'].'%')
                    ->orderBy($data['sort'], $data['order'])
                    ->paginate($data['limit']);
            }

            if ($schools->isEmpty()) {
                return null;
            } else {
                $schools = self::setPaginationLinks($schools, $data);
                return self::transform($schools, new SchoolTransformer());
            }

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getSchoolById($school_id)
    {
        try {
            $school = $this->school_model->where('id', $school_id)->first();
            if (is_null($school)) {
                return false;
            } else {
                return self::transform($school, new SchoolTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createSchool($data)
    {
        try {
            $school = $this->school_model;
            $school->school = $data['school'];
            $school->description = $data['description'];
            $school->save();

            return self::transform($school, new SchoolTransformer());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateSchoolById($data)
    {
        try {
            $school = $this->school_model->where('id', $data['school_id'])->first();
            if (is_null($school)) {
                return false;
            } else {
                $school->school = (isset($data['school']) ? $data['school'] : $school->school);
                $school->description = (isset($data['description']) ? $data['description'] : $school->description);
                $school->save();
                
                return self::transform($school, new SchoolTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
