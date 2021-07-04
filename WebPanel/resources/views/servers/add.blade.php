@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="sixteen wide column">
                    <h4 class="ui dividing header">Add New Server</h4>
                    {!! Form::open(['action' => 'ServerController@store']) !!}
                    <div class="form ui">
                        <input type="hidden" name="service_id" value="{{$service->id}}" />
                        <div class="fields">
                            @foreach($service->properties()->get() as $property)
                            <div class="field">
                                <label>{{$property->name}}</label>
                                <input type="hidden" required name="property_id[]" value="{{$property->id}}" />
                                @if($property->type == 2)
                                    <input type="hidden" name="property_value[{{$property->id}}]" value="0" />
                                @endif
                                <input @if($property->type == 0) type="text" @elseif($property->type == 1) type="number" @else type="checkbox" class="ui checkbox" @endif name="property_value[{{$property->id}}]"  placeholder="{{$property->name}}">
                            </div>
                            @endforeach
                            <div class="field">
                                <label>Tags</label>

                                <select name="tags[]" multiple="" class="ui dropdown">
                                    @foreach(\App\Tag::all() as $tag)
                                        <option value="{{$tag->id}}">{{$tag->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Groups</label>

                                <select name="groups[]" multiple="" class="ui dropdown">
                                    @foreach(\App\Group::all() as $group)
                                        <option value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <div class="field">
                                    <label>Order : </label>
                                    <input required type="number" placeholder="0" name="index" value="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input class="ui submit button primary" type="submit" value="Save" />
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop
@section ('scripts')
    <script type="text/javascript">
        $(document).ready(()=>{
            $('.dropdown').dropdown();
        });
    </script>
@stop
