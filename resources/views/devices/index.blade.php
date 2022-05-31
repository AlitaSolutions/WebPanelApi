@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="sixteen wide column">
                    <h4 class="ui dividing header">Username</h4>
                    {!! Form::open(['url' => action([\App\Http\Controllers\DeviceController::class,'search'])]) !!}
                    <div class="form ui">
                        <div class="inline fields">
                            <div class="field">
                                <label>Username : </label>
                                <input required type="text" placeholder="XXXXX" name="username">
                            </div>
                            <div class="field">
                                <button class="ui primary button">
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        @if(isset($result))
            @if(count($result) >0)
                <div class="row">
                    <table class="ui celled table">
                        <thead>
                        <tr>
                            <th>Device ID</th>
                            <th>Device Name</th>
                            <th>Action</th>
                        </tr></thead>
                        <tbody>
                        @foreach($result as $device)
                            <tr>
                                <td data-label="id">{{$device->device_id}}</td>
                                <td data-label="caption">{{$device->device_name}}</td>
                                <td data-label="name"><a data-id="{{$device->id}}" class="del" href="#">Delete</a></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @else
                <div class="ui info message">
                    <div class="header">
                        No Result
                    </div>
                    <ul class="list">
                        <li>User not logged in or not exists</li>
                        <li>Try another username.</li>
                    </ul>
                </div>
            @endif
        @endif
    </div>
    <div id="DeleteModal" class="ui mini modal">
        <div class="content">
            <p>Are you sure that you want to delete this record?</p>
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
            $('.ui.dropdown')
                .dropdown()
            ;
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
                url: "{{action([\App\Http\Controllers\DeviceController::class,"index"])}}/" + id,
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
