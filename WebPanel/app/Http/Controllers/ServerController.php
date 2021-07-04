<?php

namespace App\Http\Controllers;

use App\Group;
use App\Property;
use App\Server;
use App\ServerGroup;
use App\ServerTag;
use App\Service;
use App\Setting;
use App\Value;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('servers.index');
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $tags = $request->get('tags');
        $groups = $request->get('groups');
        $server = new Server();
        $server->service_id = $request->get('service_id');
        $server->index = $request->get('index');
        $server->save();
        $property_values = $request->get('property_value');
        foreach ($property_values as $k => $v){
            $value = new Value();
            $value->server_id = $server->id;
            $value->property_id = $k;
            $value->value = $v;
            $value->save();
            if($value->property->type == 2 ) {
                if ($value->value == 'on')
                    $value->value = 1;
                else
                    $value->value = 0;
                $value->save();
            }
        }
        if($tags != null ){
            foreach ($tags as $tag){
                $st = new ServerTag();
                $st->server_id = $server->id ;
                $st->tag_id = $tag;
                $st->save();
            }
        }
        if($groups != null ) {
            foreach ($groups as $group){
                $sg = new ServerGroup();
                $sg->server_id = $server->id;
                $sg->group_id = $group;
                $sg->save();
            }
        }
        return response()->redirectToAction('ServerController@ServiceServers',$request->get('service_id'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $record = Server::findOrFail($id);
        $allTags = \App\Tag::all();
        $tags = [];
        foreach ($allTags as $tag){
            $tags[] = $tag->id;
        }
        return view('servers.edit')->with([
            'record' => $record,
            "tags" => $allTags
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $server = Server::findOrFail($id);
        $server->index = $request->get('index');
        $property_values = $request->get('property_value');
        foreach ($server->properties as $value){
            $value->value = $property_values[$value->property->name];
            if($value->property->type ==2 ) {
                if ($value->value == 'on')
                    $value->value = 1;
                else
                    $value->value = 0;
            }
            $value->save();
        }
        ServerTag::where('server_id',$id)->delete();
        $tags = $request->get('tags');
        if($tags != null ){
            foreach ($tags as $tag){
                $st = new ServerTag();
                $st->server_id = $server->id ;
                $st->tag_id = $tag;
                $st->save();
            }
        }


        ServerGroup::where('server_id',$id)->delete();
        $groups = $request->get('groups');
        if($groups != null ){
            foreach ($groups as $group){
                $sg = new ServerGroup();
                $sg->server_id = $server->id ;
                $sg->group_id = $group;
                $sg->save();
            }
        }

        $server->save();
        return response()->redirectToAction('ServerController@ServiceServers',$server->service->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $p = Server::findOrFail($id);
        Server::destroy($p->id);
        return response(null,Response::HTTP_OK);
    }

    public function ServiceServers($sid){
        $service = Service::findOrFail($sid);
        return view('servers.list')->with('service',$service);
    }
    public function AddServerForService($sid){
        $service = Service::findOrFail($sid);
        return view('servers.add')->with('service',$service);
    }
}
