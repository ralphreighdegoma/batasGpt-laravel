<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Tag;

class TagSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //remove all tags use model
        Tag::truncate();

        $tags = [
            'Full Time',
            'Locum Tenens',
            'PRN',
            'W-2',
            '1099',
            'Private Practice',
            'National Private Practice',
            'Hospital',
            'Academic',
            'Partnership',
            'Nocturnist',
            'Cardiac',
            'OB',
            'General',
            'Peds',
            'Sign-on Bonus',
            'Loan Repayment',
            'Flexible Schedule',
            'No State Income Tax',
            'Leadership',
            'Medical Director',
            'Chief',
            'Regional Anesthesiologist',
            'Night Float',
            'PTO',
            'High Compensation',
            'Per Diem',
            'Hourly Rate',
            'Guaranteed Hours',
        ];

        foreach ($tags as $tag) {
            Tag::create(['name' => $tag]);
        }

    }
}
