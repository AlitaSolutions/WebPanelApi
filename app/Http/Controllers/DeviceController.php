<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DeviceController extends Controller
{
    function index(){
        return view('devices.index');
    }
    function search(Request $request){
        $username = $request->get('username');
        $result = Device::where('username',$username)->get();

        return view('devices.index')->with('result',$result);
    }
    function delete(Request $request , $id){
        Device::destroy($id);

        return response(null,Response::HTTP_OK);
    }
}
