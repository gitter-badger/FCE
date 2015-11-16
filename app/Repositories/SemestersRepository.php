<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 10/29/2015
 * Time: 8:41 PM
 */

namespace Fce\Repositories;

use Fce\Models\Section;
use Fce\Models\Semester;
use Fce\Transformers\SemesterTransformer;
use Illuminate\Pagination\Paginator;

class SemestersRepository extends AbstractRepository implements ISemestersRepository
{
    protected $semester_model;

    public function __construct(Semester $semester)
    {
        $this->semester_model = $semester;
    }

    public function getSemesters($data)
    {
        try {
            Paginator::currentPageResolver(
                function () use ($data) {
                    return $data['offset'];
                }
            );

            if (is_null($data['query'])) {
                $semesters = $this->semester_model->orderBy($data['sort'], $data['order'])
                    ->paginate($data['limit']);
            } else {
                $semesters = $this->semester_model->where('semester', 'like', '%'.$data['query'].'%')
                    ->orderBy($data['sort'], $data['order'])
                    ->paginate($data['limit']);
            }

            if ($semesters->isEmpty()) {
                return null;
            } else {
                $semesters = self::setPaginationLinks($semesters, $data);
                return self::transform($semesters, new SemesterTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function setCurrentSemester($semester_id)
    {
        try {
            $semester = $this->semester_model->where('id', $semester_id)->first();
            if (is_null($semester)) {
                return false;
            } else {
                $semester->current_semester = true;
                $semester->save();
                return self::transform($semester, new SemesterTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCurrentSemester()
    {
        try {
            $semester = $this->semester_model->where('current_semester', true)->first();
            if (is_null($semester)) {
                return false;
            } else {
                return self::transform($semester, new SemesterTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createSemester($data)
    {
        try {
            $semester = $this->semester_model;
            $semester->semester = $data['semester'];
            $semester->current_semester = $data['current_semester'];
            $semester->save();

            return self::transform($semester, new SemesterTransformer());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
