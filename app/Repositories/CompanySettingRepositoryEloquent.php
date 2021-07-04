<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/3/21
 * Time: 8:03 PM
 */

namespace App\Repositories;

use App\Contracts\Repositories\CompanySettingRepository;
use App\Models\CompanySetting;

class CompanySettingRepositoryEloquent extends BaseRepository implements CompanySettingRepository
{
    public function __construct(CompanySetting $model)
    {
        parent::__construct($model);
    }

}
