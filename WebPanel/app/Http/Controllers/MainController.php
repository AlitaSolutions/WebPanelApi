<?php

namespace App\Http\Controllers;

use App\Device;
use App\Platform;
use App\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use stdClass;

class MainController extends Controller
{

    function index(){
        return view('main');
    }
    function android( Request $request){
        $client = new \GuzzleHttp\Client();
        $expiresAt = \Carbon\Carbon::now()->addSecond(env("CACHE_DURATION",0));


        $username = $request->get('username',null);
        $password = $request->get('password',null);

        $device_id = $request->get('device_id',null);
        $output = array('Services' => [],'Settings' => null,'User' => ["Status" => "Wrong"]) ;
        $AUTH = env('AUTH_SERVER');
        if($username != null && $password != null){
            if(Cache::get("$username:$password",null) != null ){
                //return Cache::get("$username:$password");
            }
            $AUTH  = sprintf ($AUTH,$username,$password);
            $response = $client->request('GET', $AUTH);

            $user = $response->getBody();
            $output['User'] =json_decode($user,true );
            if($device_id == null || $device_id == ""){
                $output['User']['Status'] = 'Wrong';
            }else{
                if($output['User']['Status'] == "OK" || $output['User']['Status'] == "FirstUse"){
                    $device_exists = Device::where([
                        ['username',$username],
                        ['device_id',$device_id]
                    ])->count();
                    if($device_exists == 0 ){
                        Device::create([
                                'username' => $username,
                                'device_id' => $device_id]
                        );
                    }
                }
                /*                if (Device::where('username',$username)->count() > 2){
                                    $output['User']['Status'] = 'Locked';
                                }*/
            }
        }
        $platform = Platform::where('name','Android')->firstOrFail();
        $services = $platform->services()->where('is_enabled',true)->orderBy('index')->get();
        foreach ($services as $service){
            $row = $service->toArray();
            $row['servers'] = array();

            foreach ($service->servers()->orderBy('index')->get() as $server){
                $output_server = [];
                if($server->properties == null)
                    continue;
                foreach ($server->properties as $p){
                    $output_server[$p->property->name] = $p->value;
                }
                if(isset($output_server['Enabled'])){
                    if(!$output_server['Enabled']){
                        continue;
                    }
                }
                $server_tags = $server->tags()->first();
                $tag = "#";
                if($server_tags != null) {
                    $t = $server_tags->tag()->first();
                    if($t != null )
                        $tag = $t->name;

                }
                $row['servers']/*[$tag]*/[] =$output_server;
            }
            $output['Services'][] = $row;
        }
        foreach (Setting::all() as $setting){
            $output['Settings'][$setting->key] = $setting->value;
        }

        $iv = openssl_random_pseudo_bytes(16, $secure);
        $key = env('APP_KEY_PRIV');
        $data = $iv . openssl_encrypt(json_encode($output), 'AES-128-CBC',$key , OPENSSL_RAW_DATA, $iv) . $key;
        if($output['User']['Status'] == "OK" || $output['User']['Status'] == "FirstUse"){
            //Cache::put("$username:$password",base64_encode($data),$expiresAt);
        }
        if($request->get('Developer',null) == 'DebugMode')
            return $output;
        else
            return base64_encode($data);

    }
    function logout( Request $request){
        $client = new \GuzzleHttp\Client();
        $username = $request->get('username',null);
        $password = $request->get('password',null);
        $device_id = $request->get('device_id',null);
        $AUTH = env('AUTH_SERVER');
        if($username != null && $password != null){
            $AUTH  = sprintf ($AUTH,$username,$password);
            $response = $client->request('GET', $AUTH);

            $user = $response->getBody();
            $output['User'] =json_decode($user,true );
            if($device_id == null || $device_id == ""){
                return response('',403);
            }else{
                if($output['User']['Status'] == "OK" || $output['User']['Status'] == "FirstUse"){
                    Device::where([
                        ['username',$username],
                        ['device_id',$device_id]
                    ])->delete();
                    return response('',200);
                }
            }
        }
    }
}
