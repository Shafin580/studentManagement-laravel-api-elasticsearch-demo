<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassCoursePivotRequest;
use App\Http\Requests\UpdateClassCoursePivotRequest;
use App\Models\ClassCoursePivot;
use Illuminate\Http\Request;

class ClassCoursePivotController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $classCourses = ClassCoursePivot::select('classId', 'courseId')->with('classRoom', 'course')->groupBy('classId', 'courseId')->get();

        $data = [];
        $courses = [];

        foreach($classCourses as $classCourse){

            if(!array_key_exists($classCourse->classRoom->name,$data)){
                $courses = [];
            }

            array_push($courses, $classCourse->course->name);

            $data[$classCourse->classRoom->name] = $courses;
        }

        dd($data);

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreClassCoursePivotRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'classId' => 'required|exists:class_rooms,id',
            'courseId' => 'required|exists:courses,id',
        ]);

        if($validation){
            $classId = $request->input('classId');
            $courseId = $request->input('courseId');

            ClassCoursePivot::create([
                'classId' => $classId,
                'courseId' => $courseId,
            ]);

            return response()->json(['message' => "Data Inserted Successfully"], 200);
        }
        else{
            return response()->json(['message' => "Failed To insert Data"], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ClassCoursePivot  $classCoursePivot
     * @return \Illuminate\Http\Response
     */
    public function show($classCoursePivot)
    {
        $classCourses = ClassCoursePivot::with('classRoom', 'course')->where('classId', $classCoursePivot)->get();

        //dd(count($classCourses));

        $data = []; 
        $courses = [];
        $tempName = "";
        $i = 1;
        foreach($classCourses as $classCourse){
            if($i == 1){
                $tempName = $classCourse->classRoom->name;
            }
            $className = $classCourse->classRoom->name;
            array_push($courses, $classCourse->course->name);
            if($tempName != $className || $i == count($classCourses)){
                $tempName = $className;
                array_push($data, [
                    'class' => $tempName,
                    'courses' => $courses,
                ]);
                $courses = [];
            }
            $i++;
        }

        return response()->json([
            'data' => $data,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClassCoursePivot  $classCoursePivot
     * @return \Illuminate\Http\Response
     */
    public function edit(ClassCoursePivot $classCoursePivot)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateClassCoursePivotRequest  $request
     * @param  \App\Models\ClassCoursePivot  $classCoursePivot
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $classCoursePivot)
    {
        if(isset($request->courseNextId)){
            $request->validate([
                'courseNextId' => 'required|exists:courses,id',
            ]);
        }else{
            return response()->json([
                "No value given"
            ]);
        }

        if(ClassCoursePivot::where('classId', $classCoursePivot)->where('courseId', $request->coursePrevId)->update(['courseId' => $request->courseNextId])){
            return response()->json([
                'message' => "Data Updated Sucessfully",
            ]);
        }else{
            return response()->json([
                'message' => "Data Update Failed",
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ClassCoursePivot  $classCoursePivot
     * @return \Illuminate\Http\Response
     */
    public function destroy(ClassCoursePivot $classCoursePivot)
    {
        //
    }
}
