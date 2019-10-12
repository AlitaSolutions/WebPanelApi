<?php

namespace App\Http\Controllers;

use App\Property;
use App\Service;
use App\Value;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('properties.index');
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
        $property = Property::create($request->all());
        $service = Service::findOrFail($request->get('service_id'));
        foreach ($service->servers()->get() as $server){
            $v = new Value();
            $v->server_id = $server->id;
            $v->property_id = $property->id ;
            $v->value = $request->get('default') ;
            $v->save();
        }
        return response()->redirectToAction('PropertyController@index');
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
        //
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
        $property = Property::findOrFail($id);
        $property->name = $request->get('name');
        if($property->save())
            return response(null,Response::HTTP_OK);
        else
            return response(null,Response::HTTP_NOT_ACCEPTABLE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $p = Property::findOrFail($id);
        Property::destroy($p->id);
        return response(null,Response::HTTP_OK);
    }
}
