<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses = ['Bangla', 'English', 'Math', 'Science', 'Physics', 'Chemistry', 'Biology', 'Social Studies', 'Religion', 'Art'];

        foreach($courses as $course){
            DB::table('courses')->insert([
                'name' => $course,
            ]);
        }
    }
}
