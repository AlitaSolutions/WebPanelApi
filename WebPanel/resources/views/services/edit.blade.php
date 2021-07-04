@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="sixteen wide column">
                    <h4 class="ui dividing header">Edit Service</h4>
                    {!! Form::open(['action' => ['ServiceController@update',$record->id] , 'method' => 'PUT']) !!}
                    <div class="form ui">
                        <input type="hidden" name="server_id" value="{{$record->id}}" />
                        <div class="fields">
                            <div class="field">
                                <label>Name</label>
                                <input name="name" type="text" value="{{$record->name}}" class="input" />

                            </div>
                            <div class="field">
                                <label>Enable</label>
                                <input type="checkbox" name="enabled"  @if($record->enabled) checked @endif class="ui checkbox" />

                            </div>
                            <div class="field">
                                <label>Recommended</label>
                                <input type="checkbox" name="recommended" @if($record->recommended) checked @endif class="ui checkbox" />

                            </div>
                            <div class="field">
                                <label>Groups</label>

                                <select name="groups[]" multiple="" class="ui dropdown">
                                    @foreach(\App\Group::all() as $group)
                                        @php($found = false)
                                        @foreach($record->groups as $g)
                                            @if($g->group_id == $group->id)
                                                @php($found = true)
                                            @endif
                                        @endforeach

                                        <option @if ($found) selected @endif value="{{$group->id}}">{{$group->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <label>Order : </label>
                                <input required type="number" placeholder="0" name="index" value="{{$record->index}}">
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
