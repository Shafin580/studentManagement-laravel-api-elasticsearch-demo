<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $students = Student::with('classRoom', 'classRoom.routine', 'classRoom.routine.course', 'classRoom.routine.weekDay')->get();
        // $data = [];
        // foreach ($students as $student) {
        //     $routines = $student->classRoom->routine;
        //     $routineData = [];
        //     foreach ($routines as $routine) {
        //         array_push($routineData, [
        //             'course' => $routine->course->name,
        //             'day' => $routine->weekDay->name,
        //             'classTime' => $routine->classBeginTime . "-" . $routine->classEndTime,
        //         ]);
        //     }
        //     array_push($data, [
        //         'id' => $student->id,
        //         'type' => 'Student',
        //         'name' => $student->name,
        //         'class' => $student->classRoom->name,
        //         'routine' => $routineData,
        //     ]);
        // }
        // return response()->json([
        //     'data' => $data,
        // ]);

        $searchQuery = [
            'bool' => [
                'must' => [
                    'match_all' => (object)[],
                ]
            ]
        ];
        $getSearchResults = Student::searchQuery($searchQuery)
            ->size(100)->raw();
        // dd($getSearchResults['hits']['hits']);
        return response()->json([
            'status_code' => 200,
            'data' => $getSearchResults['hits']['hits'],
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
     * @param  \App\Http\Requests\StoreStudentRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'name' => 'required',
            'classId' => 'required|exists:class_rooms,id',
        ]);

        if ($validation) {

            $name = $request->input('name');
            $classId = $request->input('classId');

            Student::create([
                'name' => $name,
                'classId' => $classId,
            ]);

            return response()->json(['message' => "Data Inserted Successfully"], 200);
        } else {
            return response()->json(['message' => "Failed To insert Data"], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function show($student)
    {
        // dd($student);
        // $student = Student::with('classRoom', 'classRoom.routine', 'classRoom.routine.course', 'classRoom.routine.weekDay')->where('id', $student)->first();
        // $routines = $student->classRoom->routine;
        // $routineData = [];
        // foreach ($routines as $routine) {
        //     array_push($routineData, [
        //         'course' => $routine->course->name,
        //         'day' => $routine->weekDay->name,
        //         'classTime' => $routine->classBeginTime . "-" . $routine->classEndTime,
        //     ]);
        // }
        // return response()->json([
        //     'data' => [
        //         'id' => $student->id,
        //         'type' => 'Student',
        //         'name' => $student->name,
        //         'class' => $student->classRoom->name,
        //         'routine' => $routineData,
        //     ]
        // ]);

        $searchQuery = [
            'bool' => [
                'must' => [
                    'term' => [
                        "studentId" => $student
                    ],
                ]
            ]
        ];
        $getSearchResults = Student::searchQuery($searchQuery)
            ->size(1)->raw();
        $result = $getSearchResults['hits']['hits'];
        $returnData = [];
        if (count($result) > 0) {
            // dd($result);
            foreach ($result as $res) {
                $returnData = $res['_source'];
            }
            return response()->json([
                'status_code' => 200,
                'data' => $returnData,
            ]);
        }
        return response()->json([
            'status_code' => 400,
            'message' => "No Records Found!",
        ]);
        // dd($getSearchResults['hits']['hits']);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateStudentRequest  $request
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function update($student, Request $request)
    {

        $student = Student::where('id', $student)->first();
        return $student;

        //return dd($request);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Student  $student
     * @return \Illuminate\Http\Response
     */
    public function destroy(Student $student)
    {
        //
    }

    public function test(Request $request)
    {
        //dd("Ok");
        // dd($request->classId);
        if (isset($request->classId)) {
            $students = Student::with('classRoom', 'classRoom.routine', 'classRoom.routine.course', 'classRoom.routine.weekDay')->where('classId', $request->input('classId'))->get();
            if ($request->has('studentId')) {
                $students = Student::with('classRoom', 'classRoom.routine', 'classRoom.routine.course', 'classRoom.routine.weekDay')->where([['id', $request->input('studentId')], ['classId', $request->input('classId')]])->get();
            }
        } else {
            $students = Student::with('classRoom', 'classRoom.routine', 'classRoom.routine.course', 'classRoom.routine.weekDay')->get();
        }
        // dd($students);
        $data = [];
        foreach ($students as $student) {
            $routines = $student->classRoom->routine;
            $routineData = [];
            foreach ($routines as $routine) {
                if ($request->has('weekDay')) {
                    if ($routine->weekDay->id == $request->input('weekDay')) {
                        array_push($routineData, [
                            'course' => $routine->course->name,
                            'day' => $routine->weekDay->name,
                            'classTime' => $routine->classBeginTime . "-" . $routine->classEndTime,
                        ]);
                    }
                } else {
                    array_push($routineData, [
                        'course' => $routine->course->name,
                        'day' => $routine->weekDay->name,
                        'classTime' => $routine->classBeginTime . "-" . $routine->classEndTime,
                    ]);
                }
            }
            // dd($routineData);
            $data = [
                'id' => $student->id,
                'type' => 'Student',
                'name' => $student->name,
                'class' => $student->classRoom->name,
                'routine' => $routineData,

            ];
        }
        // if($request->has('weekDay')){
        //     foreach($routineData as $loop){
        //         array_push($data, [
        //             'id' => $student->id,
        //             'type' => 'Student',
        //             'name' => $student->name,
        //             'class' => $student->classRoom->name,
        //             'routine' => $routineData,
        //         ]);
        //     }
        // }else{
        //     array_push($data, [
        //         'id' => $student->id,
        //         'type' => 'Student',
        //         'name' => $student->name,
        //         'class' => $student->classRoom->name,
        //         'routine' => $routineData,
        //     ]);
        // } 

        // dd($data);
        return response()->json([
            'data' => $data,
        ]);
    }
}
