@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="sixteen wide column">
                    <h4 class="ui dividing header">Add New Platform</h4>
                    {!! Form::open(['url' => action([\App\Http\Controllers\SettingsController::class,'store'])]) !!}
                    <div class="form ui">
                        <div class="inline field">
                            <label>Key : </label>
                            <input required type="text" placeholder="Key" name="key">
                            <label>Value : </label>
                            <input required type="text" placeholder="Value" name="value">
                            <button class="ui primary button">
                                Save
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="row">
                <table class="ui celled table">
                    <thead>
                    <tr><th>ID</th>
                        <th>Key</th>
                        <th>Value</th>
                        <th>Action</th>
                    </tr></thead>
                    <tbody>
                    @foreach(\App\Models\Setting::all() as $setting)
                        <tr>
                            <td data-label="id">{{$setting['id']}}</td>
                            <td>{{$setting['key']}}</td>
                            <td>{{$setting['value']}}</td>
                            <td><a data-id="{{$setting['id']}}" data-name="{{$setting['value']}}" class="edit" href="#">Edit</a>&nbsp;|&nbsp;<a data-id="{{$setting['id']}}" class="del" href="#">Delete</a></td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
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
    <div id="EditModal" class="ui small modal">
        <div class="ui icon header">
            <i class="edit icon"></i>
            Edit Platform
        </div>
        <div class="content">
            <div class="form ui">
                <div class="inline field">
                    <label>Value : </label>
                    <input type="text" placeholder="Value" name="value" id="edit">
                </div>
            </div>
        </div>
        <div class="actions">
            <div class="ui negative button">
                <i class="remove icon"></i>
                Cancel
            </div>
            <div class="ui green ok button">
                <i class="checkmark icon"></i>
                Edit
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        $(document).ready(()=>{
            $('.ui.dropdown')
                .dropdown();
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
            $(".edit").click((e)=>{
                let name = $(e.target).data('name');
                $("#edit").val(name);
                $('#EditModal').modal({
                    closable  : false,
                    onDeny    : function(){

                    },
                    onApprove : function() {
                        let id = $(e.target).data('id');
                        editRecord(id,$("#edit").val());
                    }
                }).modal('show');
                e.preventDefault();
            });
        });
        function deleteRecord(id){
            $.ajax({
                url: "{{action([\App\Http\Controllers\SettingsController::class,"index"])}}/" + id,
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
        function editRecord(id,name){
            $.ajax({
                url: "{{action([\App\Http\Controllers\SettingsController::class,"index"])}}/" + id,
                type: "PUT",
                data: {
                    "id":id,
                    "value":name
                },
                success : function(){
                    location.reload();
                },
                error : function () {
                    alert('Unable to edit record');
                }
            });
        }
    </script>
@stop
