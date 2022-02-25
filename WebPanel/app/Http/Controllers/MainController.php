<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Platform;
use App\Models\Setting;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MainController extends Controller
{

    function index(){
        return view('main');
    }
    function android( Request $request){
        $client = new Client();
        $expiresAt = Carbon::now()->addSecond(env("CACHE_DURATION",0));


        $username = $request->get('username',null);
        $password = $request->get('password',null);

        $device_id = $request->get('device_id',null);
        $output = array('Services' => [],'Settings' => ["Dummy" => true],'User' => ["Status" => "Wrong"]) ;
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
                                'device_name' => $request->get('device_name',null),
                                'device_id' => $device_id]
                        );
                    }
                    if (Device::where('username',$username)->count() > $output['User']['MultiLogin'] ){
                        $output['User']['Status'] = 'Locked';
                    }
                }

            }
        }
        $platform = Platform::where('name','Android')->firstOrFail();
        $services = $platform->services()->where('enabled',true)->orderBy('index')->get();
        if($output['User']['Status'] == "OK" || $output['User']['Status'] == "FirstUse" ){
            foreach ($services as $service){
                $output['Services'][$service->name] = [];
                $row = [];
                foreach ($service->servers()->orderBy('index')->get() as $server){
                    $output_server = [];
                    $output_server['_id'] = $server->id;
                    $output_server['groups'] = $server->groups->pluck('name')->toArray();
                    if(count($output_server['groups']) > 0 ){
                        if(!in_array($output['User']['GroupName'],$output_server['groups'])){
                            continue;
                        }
                    }
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
                    $row[$tag][] =$output_server;
                }
                $output['Services'][$service->name] = $row;
            }
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
        $client = new Client();
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
