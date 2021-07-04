<?php

namespace App\Http\Controllers;

use App\Service;
use App\ServiceGroup;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('services.index');
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
        $request->validate(['name'=>'required|unique:services']);
        $service = Service::create($request->all());
        $groups = $request->groups;
        if($groups != null ) {
            foreach ($groups as $group){
                $sg = new ServiceGroup();
                $sg->service_id = $service->id;
                $sg->group_id = $group;
                $sg->save();
            }
        }
        return response()->redirectToAction("ServiceController@index");
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
        $service = Service::findOrFail($id);
        return view('services.edit')->with(['record' => $service]);
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
        $name = $request->get('name');
        $service = Service::findOrFail($id);
        $service->name = $name;
        $service->index = $request->get('index');
        $service->enabled = $request->get('enabled') == 'on';
        $service->recommended = $request->get('recommended') == 'on';
        $r = $service->save();
        ServiceGroup::where('service_id',$service->id)->delete();
        if($request->groups != null ){
            foreach ($request->groups as $group){
                $sg = new ServiceGroup();
                $sg->service_id = $service->id ;
                $sg->group_id = $group;
                $sg->save();
            }
        }
        return redirect()->action('ServiceController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Service::destroy($id);

        return response(null,Response::HTTP_OK);
    }
}
