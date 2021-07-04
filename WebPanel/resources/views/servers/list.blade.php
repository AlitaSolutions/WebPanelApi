@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="field">
                <a href="{{url()->current()}}/add" class="ui positive basic button">Add Server</a>
            </div>
            <div class="row">
                <table class="ui celled table">
                    <thead>
                    <tr>
                        <th>Server ID</th>
                        <th>Tags</th>
                        <th>Groups</th>
                        @foreach($service->properties()->get() as $property)
                        <th>{{$property->name}}</th>
                        @endforeach
                        <th>Order</th>
                        <th>Action</th>
                    </tr></thead>
                    <tbody>
                    @foreach($service->servers()->get() as $s)
                        <tr>
                            <td data-label="id">{{$s->id}}</td>
                            <td>
                                @foreach($s->tags as $t)
                                    {{$t->tag->name}} |
                                @endforeach
                            </td>
                            <td>
                                @foreach($s->groups as $group)
                                    {{$group->name}} |
                                @endforeach
                            </td>
                            @foreach($s->properties as $property)
                                @if($property->property->type == 2)
                                    @if($property->value == '1')
                                        <td>True</td>
                                    @else
                                        <td>False</td>
                                    @endif
                                @else
                                    <td>{{$property['value']}}</td>
                                @endif
                            @endforeach
                            <td>{{$s->index}}</td>
                            <td data-label="name"><a href="{{action('ServerController@edit',$s['id'])}}">Edit</a>&nbsp;|&nbsp;<a data-id="{{$s->id}}" class="del" href="#">Delete</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="DeleteModal" class="ui mini modal">
        <div class="content">
            <p>Are you sure that you want to delete this server?</p>
        </div>
        <div class="actions">
            <div class="ui negative button">
                <i class="remove icon"></i>
                No
            </div>
            <div class="ui positive right labeled icon button">
                <i class="checkmark icon"></i>
                Yes
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(()=>{
            $(".del").click((e)=>{
                $('#DeleteModal').modal({
                    closable  : false,
                    onDeny    : function(){

                    },
                    onApprove : function() {
                        let id = $(e.target).data('id');
                        deleteRecord(id);
                    }
                }).modal('show');
                e.preventDefault();
            });
        });
        function deleteRecord(id){
            $.ajax({
                url: "{{action("ServerController@index")}}/" + id,
                type: "DELETE",
                data: {
                    "id":id
                },
                success : function(){
                    location.reload();
                },
                error : function () {
                    alert('Unable to delete record');
                }
            });
        }
    </script>
@stop
