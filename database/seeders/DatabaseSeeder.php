<?php

namespace Database\Seeders;

use App\Models\Pmt;
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

        Funct::factory()->create([
            'funct' => 'Core Function'
        ]);

        Funct::factory()->create([
            'funct' => 'Strategic Function'
        ]);

        Funct::factory()->create([
            'funct' => 'Support Function'
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
        
        Pmt::factory()->create([
            'position' => 'College Vice President'
        ]);

        Pmt::factory()->create([
            'position' => 'Director of Finance'
        ]);

        Pmt::factory()->create([
            'position' => 'Director of Planning'
        ]);

        Pmt::factory()->create([
            'position' => 'Director of Human Resource'
        ]);

        Pmt::factory()->create([
            'position' => 'Head of Evaluation Comitee'
        ]);

        Pmt::factory()->create([
            'position' => 'Representative of Faculty'
        ]);

        Pmt::factory()->create([
            'position' => 'Representative of Staff'
        ]);

        $this->call([
            UserSeeder::class,
            OfficeSeeder::class,
        ]);

        $offices = Office::all();

        User::all()->each(function ($user) use ($offices) { 
            $user->offices()->attach(
                $offices->random(rand(1, 2))->pluck('id')->toArray()
            ); 
        });

        $account_types = AccountType::all();

        User::all()->each(function ($user) use ($account_types) { 
            $user->account_types()->attach(
                $account_types->random(1)->pluck('id')->toArray()
            ); 
        });

    }
}
