<?php

namespace App\Http\Controllers\Porfessional;

use App\Http\Controllers\Controller;
use App\Models\Salon;
use App\Models\SalonScheduleTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Validator;

class ManageSchedulController extends Controller
{
    public function salonScheduleTime(){
        $salonScheduleTime = SalonScheduleTime::where('salon_id', auth()->user()->salon->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate($request->per_page ?? 10);;

        if($salonScheduleTime->isEmpty()) {
            return response()->json(['message'=> 'No salon schedule time found']);
        }

    $salonScheduleTime->getCollection()->transform(function($scheduleTime) {
        $schedule = json_decode($scheduleTime->schedule);
        $scheduleTime->schedule = $schedule;
        return $scheduleTime;
    });
        
        return response()->json(['message'=> 'Success','salonScheduleTime' => $salonScheduleTime]);
    }

    public function storeSchedule(Request $request){
        $validated = Validator::make($request->all(), [
            'schedule' => 'required|json',
            'capacity' => 'required|integer',
        ]);
        if($validated->fails()){
            return response()->json(['message'=> 'Validation failed', 'errors' => $validated->errors()]);
        }
        $scheduleTime = new SalonScheduleTime();
        $scheduleTime->schedule = $request->schedule;
        $scheduleTime->salon_id = auth()->user()->salon->id;
        $scheduleTime->capacity = $request->capacity;
        $scheduleTime->save();

        return response()->json(['message'=> 'Schedule time added successfully']);
}

    public function updateSchedule(Request $request, $id){ 

        try {
            $updateSchedul = SalonScheduleTime::findOrfail($id);
            // dd($request->all());
            if($updateSchedul){
                $validated = Validator::make($request->all(), [
                    'schedule' => 'required|json',
                    'capacity' => 'required|integer',
                ]);
    
                if($validated->fails()){
                    return response()->json(['message'=> 'Validation failed', 'errors' => $validated->errors()]);
                }
                
                $updateSchedul->schedule = $request->schedule;
                $updateSchedul->capacity = $request->capacity;
                $updateSchedul->save();
    
                return response()->json(['message'=> 'Schedule time updated successfully', 'schedule' => $updateSchedul]);
    
                }
            
        } catch (\Exception $e) {
            return response()->json(['message'=> 'Something went wrong']);
        }
    }
 
    public function deleteSchedule($id){
        
        try {
            $deleteSchedule = SalonScheduleTime::findOrfail($id);
            $deleteSchedule->delete();
            return response()->json(['message'=> 'Schedule time deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['message'=> 'Something went wrong']);
        }
    }
}

