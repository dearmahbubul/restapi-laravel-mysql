<?php
/**
 * Created by Mahbubul Alam
 * User: Happy App
 * Date: 7/3/21
 * Time: 8:03 PM
 */

namespace App\Repositories;

use App\Contracts\Repositories\CompanyRepository;
use App\Models\Company;

class CompanyRepositoryEloquent extends BaseRepository implements CompanyRepository
{
    public function __construct(Company $model)
    {
        parent::__construct($model);
    }

}
