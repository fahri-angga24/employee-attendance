<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;


class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    public function employeeAttendance(Request $request)
    {

        $user = auth()->user();

        $this->validate($request, [
                'image' => 'mimes:jpeg,jpg,png,gif|required|max:10000'
        ]);

        if($request->hasFile('image')){
                $filename = $request->image->getClientOriginalName();
                $request->image->storeAs('images',$filename,'public');
        }

        $getIp = geoip()->getClientIP();
        
        $getLocation = geoip()->getLocation('103.136.57.153');

        $createAttendance = Attendance::create([
                'user_id' => $user->id,
                'timestamp_attendance' => Carbon::now()->format('Y-m-d H:i:s'),
                'image' => $filename,
                'location' => $getLocation['city'].",".$getLocation['country'],
                'latitude' => $getLocation['lat'],
                'longitude' => $getLocation['lon']
        ]);

        if($createAttendance){
                $response = [
                    'success' => true,
                    'message' => 'Successfully save attendance',
                ];
            } else{
                $response = [
                    'success' => false,
                    'message' => 'Failed to save attendance',
                ];
            }
    
        return response()->json($response);
    }
}
