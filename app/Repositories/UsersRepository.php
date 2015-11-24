<?php
/**
 * Created by PhpStorm.
 * User: Cheezzy Tenorz
 * Date: 10/29/2015
 * Time: 7:51 PM
 */

namespace Fce\Repositories;

use Fce\Models\Section;
use Fce\Models\User;
use Fce\Transformers\UserTransformer;
use Illuminate\Pagination\Paginator;

class UsersRepository extends AbstractRepository implements IUsersRepository
{
    protected $user_model;
    protected $section_model;

    public function __construct(User $user, Section $section)
    {
        $this->user_model = $user;
        $this->section_model = $section;
    }

    public function getUsers($data)
    {
        try {
            Paginator::currentPageResolver(
                function () use ($data) {
                    return $data['offset'];
                }
            );

            if (is_null($data['query'])) {
                $users = $this->user_model->orderBy($data['sort'], $data['order'])
                    ->paginate($data['limit']);
            } else {
                $users = $this->user_model->where('name', 'like', '%'.$data['query'].'%')
                    ->orderBy($data['sort'], $data['order'])
                    ->paginate($data['limit']);
            }

            if ($users->isEmpty()) {
                return null;
            } else {
                $users = self::setPaginationLinks($users, $data);
                return self::transform($users, new UserTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getUserById($user_id)
    {
        try {
            $user = $this->user_model->where('id', $user_id)->first();
            if (is_null($user)) {
                return false;
            } else {
                return self::transform($user, new UserTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function getUserBySectionId($section_id)
    {
        try {
            $users = $this->section_model->users()->wherePivot('section_id', $section_id)->get();
            if ($users->isEmpty()) {
                return false;
            } else {
                return self::transform($users, new UserTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function createUser($data)
    {
        try {
            $user = $this->user_model;
            $user->name = $data['name'];
            $user->email = $data['email'];
            $user->password = $data['password'];
            $user->save();

            return self::transform($user, new UserTransformer());
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteUserById($user_id)
    {
        try {
            $user = $this->user_model->where('id', $user_id)->first();
            if (is_null($user)) {
                return false;
            } else {
                $user->delete();
                return self::transform($user, new UserTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function updateUser($data)
    {
        try {
            $user = $this->user_model->where('id', $data['user_id'])->first();
            if (is_null($user)) {
                return false;
            } else {
                $user->name = isset($data['name']) ? $data['name'] : $user->name;
                $user->email = isset($data['email']) ? $data['email'] : $user->email;
                $user->password = isset($data['password']) ? $data['password'] : $user->password;
                $user->save();

                return self::transform($user, new UserTransformer());
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
