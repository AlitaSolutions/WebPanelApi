@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <table class="ui celled table">
                    <thead>
                    <tr><th>Service ID</th>
                        <th>Service Name</th>
                        <th>Platform Name</th>
                        <th>Servers</th>
                        <th>Action</th>
                    </tr></thead>
                    <tbody>
                    @foreach(\App\Models\Service::all() as $service)
                        <tr>
                            <td data-label="id">{{$service['id']}}</td>
                            <td data-label="name">{{$service['name']}}</td>
                            <td data-label="name">{{$service->platform->name}}</td>
                            <td data-label="name"><a href="{{action([\App\Http\Controllers\ServerController::class,'ServiceServers'],$service['id'])}}">{{$service->servers()->get()->count()}}</a></td>
                            <td data-label="name"><a href="{{action([\App\Http\Controllers\ServerController::class,'AddServerForService'],$service['id'])}}">Add Server</a>&nbsp;|&nbsp;<a href="{{action([\App\Http\Controllers\ServerController::class,'ServiceServers'],$service['id'])}}">List</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop
