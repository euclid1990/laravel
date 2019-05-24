<?php

namespace App\Services;

use App\Repo\UserRepositoryInterface;
use DB;
use \Carbon\Carbon;

class UserService extends BaseService
{
    /**
     * @var \App\Repo\UserRepositoryInterface
     */
    protected $userRepository;

    /**
     *
     * @param \App\Repo\UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function paginateList(string $params)
    {
        $params = filter($params, ['keyword']);
        $page = isset($params['page']) ? $params['page'] : config('common.page_default');
        $keyword = isset($params['keyword']) ? $params['keyword'] : '';

        return $this->userRepository->paginateList($page, ['*'], $keyword);
    }

    public function deleteUserById(int $id)
    {
        return $this->userRepository->delete($id);
    }

    public function store(array $data)
    {
        return $this->userRepository->store($data);
    }

    public function findById(int $id)
    {
        return $this->userRepository->findById($id);
    }

    public function update(int $id, array $data)
    {
        return $this->userRepository->update($id, $data);
    }
}
