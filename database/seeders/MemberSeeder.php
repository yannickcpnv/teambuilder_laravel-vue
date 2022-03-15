<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;

class MemberSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        Member::factory(100)->create();
    }
}