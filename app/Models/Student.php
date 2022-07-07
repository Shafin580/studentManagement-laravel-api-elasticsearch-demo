<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use ElasticScoutDriverPlus\Searchable;

class Student extends Model
{
    use HasFactory, Searchable;

    protected $fillable = [
        'name',
        'classId',
    ];

    public function classRoom()
    {
        return $this->belongsTo(ClassRoom::class, 'classId', 'id');
    }

    /**
     * Get the name of the index associated with the model.
     *
     * @return string
     */
    public function searchableAs()
    {
        return 'student_index';
    }

    // public function shouldBeSearchable()
    // {
    //     return count($this->toSearchableArray()) > 0;
    // }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    // #[SearchUsingPrefix(['id', 'category_id', 'subcategory_id'])]
    // #[SearchUsingFullText(['name', 'category_name', 'subcategory_name'])]
    public function toSearchableArray()
    {
        $details = $this::with(['classRoom', 'classRoom.classCoursePivot', 'classRoom.classCoursePivot.course' => function ($q) {
            $q->latest();
        }])->where('id', $this->id)->get();

        // Customize the data array...


        foreach ($details as $detail) {
            if (isset($detail)) {
                /**
                 * Saving the original media object if available
                 */
                $studentId = $detail->id;
                $studentName = $detail->name;
                $classId = $detail->classId;
                $className = $detail->classRoom->name;
                // dd($detail->classRoom->classCoursePivot);
                if(isset($detail->classRoom->classCoursePivot)){
                    // dd($detail->classRoom->classCoursePivot);
                    $CoursesArr = [];
                    $courseArr= [];

                    foreach($detail->classRoom->classCoursePivot as $coursePivot){
                        // foreach($coursePivot->course as $course){
                            // $courseArr=[
                            //     // "className"=>$className,
                            //     "courses"=>$coursePivot->course
                            // ];
                        array_push($CoursesArr, $coursePivot->course);

                        // }
                        // $CoursesArr[]=[
                        //     "courses"=>$courseArr
                        // ];


                    }

















                }
                
                //Time stamps
                $created_at = $detail->created_at;
                $updated_at = $detail->updated_at;
                /**
                 * Lets save the sub cat if its available
                 */
                // if (isset($detail->subcategory_id)) {
                //     $subcategory_id = $detail->subcategory_id;
                //     $subcategory_name = $detail->subcategories->name;
                // }
                // if (isset($detail->mediatags)) {
                //     foreach ($detail->mediatags as $media) {
                //         $tagObject = [
                //             "id" => $media->tags->id,
                //             "name" => $media->tags->name,
                //         ];
                //         array_push($tags, $tagObject);
                //     }
                // }
            }
        }
        $results = [
            "studentId" => $studentId,
            "studentName" => $studentName,
            "classId" => $classId,
            "className" => $className,
            "CoursesArr" => $CoursesArr,
            "created_at" => $created_at,
            "updated_at" => $updated_at
        ];
        // dd($results);
        return $results;
    }

    
}
