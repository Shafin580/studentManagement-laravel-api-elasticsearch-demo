<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoutineRequest;
use App\Http\Requests\UpdateRoutineRequest;
use App\Models\Routine;
use Illuminate\Http\Request;

class RoutineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Http\Requests\StoreRoutineRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validation = $request->validate([
            'classId' => 'required|exists:class_rooms,id',
            'courseId' => 'required|exists:courses,id',
            'weekDayId' => 'required|exists:week_days,id',
            'classTime' => 'required'
        ]);

        if($validation){
            $classId = $request->input('classId');
            $courseId = $request->input('courseId');
            $weekDayId = $request->input('weekDayId');
            $classTime = explode("-", $request->input('classTime'));

            Routine::create([
                'classId' => $classId,
                'courseId' => $courseId,
                'weekDayId' => $weekDayId,
                'classBeginTime' => $classTime[0],
                'classEndTime' => $classTime[1],
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
     * @param  \App\Models\Routine  $routine
     * @return \Illuminate\Http\Response
     */
    public function show($routine)
    {
        $routine = Routine::with('classRoom', 'course', 'weekDay')->where('classId', $routine)->get();
        //dd($routine);
        return $routine;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Routine  $routine
     * @return \Illuminate\Http\Response
     */
    public function edit(Routine $routine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRoutineRequest  $request
     * @param  \App\Models\Routine  $routine
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRoutineRequest $request, Routine $routine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Routine  $routine
     * @return \Illuminate\Http\Response
     */
    public function destroy($routine)
    {
        if(Routine::where('id', $routine)->delete()){
            return response()->json([
                'message' => "Data Deleted Sucessfully!"
            ]);
        }else{
            return response()->json([
                'message' => "Data Deletion Failed!"
            ]);      
        }
    }
}
