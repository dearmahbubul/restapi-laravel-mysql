<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/7/21
 * Time: 8:50 PM
 */


namespace App\Services;



use App\Contracts\Repositories\UserRepository;
use App\Contracts\Services\CompanySettingContract;

class CompanySettingService implements CompanySettingContract
{
    private $userRepository;

    public function __construct
    (
        UserRepository $userRepository
    )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @param $user_id
     * @return object
     */
    public function getByUserId($user_id) : object
    {
        return $this->userRepository->find($user_id);
    }

}
