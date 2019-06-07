<?php

namespace App\Services;

use \Carbon\Carbon;
use App\Repositories\UserRepositoryInterface;

class UserService extends AppService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        parent::__construct($userRepository);

        $this->userRepository = $userRepository;
    }

    public function paginateList($params)
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

    public function getUserByEmail(string $email)
    {
        return $this->userRepository->getUserByEmail($email);
    }
}
