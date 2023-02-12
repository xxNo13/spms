<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Funct;
use App\Models\Office;
use App\Models\SubFunct;
use App\Models\AccountType;
use App\Models\StandardValue;
use App\Models\ScoreEquivalent;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        AccountType::factory()->create([
            'account_type' => 'Designated Faculty'
        ]);

        AccountType::factory()->create([
            'account_type' => 'Not Designated Faculty'
        ]);

        AccountType::factory()->create([
            'account_type' => 'Staff'
        ]);

        Office::factory()->create([
            'office_name' => 'Human Resource Management Office',
            'office_abbr' => 'HRMO',
            'building' => 'Administration Bldg.'
        ]);

        Office::factory()->create([
            'office_name' => 'Planning Management Office',
            'office_abbr' => 'PMO',
            'building' => 'Administration Bldg.'
        ]);

        Office::factory()->create([
            'office_name' => 'Belongs to Planning',
            'office_abbr' => 'BtP',
            'building' => 'Administration Bldg.',
            'parent_id' => 2
        ]);

        Office::factory()->create([
            'office_name' => 'Belongs to Planning 2',
            'office_abbr' => 'BtP',
            'building' => 'Administration Bldg.',
            'parent_id' => 2
        ]);

        Office::factory()->create([
            'office_name' => 'Belongs to HR',
            'office_abbr' => 'BtHR',
            'building' => 'Administration Bldg.',
            'parent_id' => 1
        ]);

        Funct::factory()->create([
            'funct' => 'Core Function'
        ]);

        Funct::factory()->create([
            'funct' => 'Strategic Function'
        ]);

        Funct::factory()->create([
            'funct' => 'Support Function'
        ]);

        SubFunct::factory()->create([
            'sub_funct' => 'Institutional Obligations',
            'funct_id' => 3
        ]);

        SubFunct::factory()->create([
            'sub_funct' => 'Intervening Functions',
            'funct_id' => 3
        ]);
        
        StandardValue::factory()->create([
            'efficiency' => '100%
90-99%
80-89%
70-79%
Below 70%',
            'quality' => 'with no revision
with slight revision
with revision',
            'timeliness' => 'before deadline
on time
after deadline'
        ]);

        ScoreEquivalent::factory(1)->create();

        $this->call([
            UserSeeder::class
        ]);

        $users = User::factory(10)->create();

        foreach ($users as $user) {
            $user->offices()->sync(rand(3,5));
        }
    }
}