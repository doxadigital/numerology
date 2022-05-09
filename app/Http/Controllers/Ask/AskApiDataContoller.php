<?php

namespace App\Http\Controllers\Ask;

use App\Http\Controllers\Controller;
use App\Models\CoachingCategory;
use App\Models\CoachingNumber;
use App\Models\CoachingRoom;
use App\Models\CoachingSelect;
use Illuminate\Http\Request;

class AskApiDataContoller extends Controller
{
    public function getDataCategoriesCoaching(){
        $data = CoachingCategory::all();
        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    public function createRoom(Request $request){
        $req = CoachingRoom::create($request->all());

        return response()->json([
            'success' => true,
            'messages' => "Data created successfully!"
        ]);
    }

    public function getCodeRoom($room_code){
        $data = CoachingRoom::where('room_code', $room_code)->first();
        if($data){
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else{
            return response()->json([
                'success' => false,
                'messages' => "room tidak ditemukan atau kode sudah kadaluarsa!"
            ]);
        }
    }

    public function getDataNumberCard(){
        $data = CoachingNumber::all();
        if($data){
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else{
            return response()->json([
                'success' => false,
                'messages' => "data tidak ditemukan!"
            ]);
        }
    }

    public function getDataCoachingSelect(){
        $data = CoachingSelect::all();
        if($data){
            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } else{
            return response()->json([
                'success' => false,
                'messages' => "data tidak ditemukan!"
            ]);
        }
    }
}