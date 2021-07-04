<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Model::unguard();

        if (App::environment() !== 'local') {
            die("In production environment you can not seed the database");
        }

         $truncates = [
            'companies',
            'users'
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // Truncate all tables, except migrations
        foreach ($truncates as $table) {
            if ($table !== 'migrations')
                DB::table($table)->truncate();
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');


        $company = Company::create([
            'name' => "OrangeToolz"
        ]);
        User::create([
            'name' => 'Mahbubul Alam',
            'company_id' => $company->id,
            'is_parent' => PARENT_USER,
            'email' => 'mahbubul@orangetoolz.com',
            'password' => bcrypt('password'),
            'phone_number' => '01906043504',
            'email_verified_at' => now()
        ]);

        Model::reguard(); // Enable mass assignment
    }
}
