@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="ui statistics">
                    <div class="statistic">
                        <div class="label">
                            <a href="{{action([\App\Http\Controllers\PlatformController::class, 'index'])}}">Platforms</a>
                        </div>
                        <div class="value">
                            {{\App\Models\Platform::all()->count()}}
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="label">
                            <a href="{{action([\App\Http\Controllers\ServiceController::class,'index'])}}"> Services</a>
                        </div>
                        <div class="value">
                            {{\App\Models\Service::all()->count()}}
                        </div>
                    </div>
                    @foreach(\App\Models\Service::all() as $service)
                    <div class="statistic">
                        <div class="label">
                            <a href="{{action([\App\Http\Controllers\ServerController::class,'ServiceServers'],$service->id)}}">{{$service->name}}</a>
                        </div>
                        <div class="value">
                            {{$service->servers()->get()->count()}}
                        </div>
                    </div>
                    @endforeach
                    <div class="statistic">
                        <div class="label">
                            <a href="{{action([\App\Http\Controllers\SettingsController::class,'index'])}}">Settings</a>
                        </div>
                        <div class="value">
                            {{\App\Models\Setting::all()->count()}}
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="label">
                            Total Servers
                        </div>
                        <div class="value">
                            {{\App\Models\Server::all()->count()}}
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="label">
                            Total Properties
                        </div>
                        <div class="value">
                            {{\App\Models\Property::all()->count()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        $(document).ready(function(){
            $('.menu .item')
                .tab()
            ;

        });
    </script>
@stop
