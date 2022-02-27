@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="eight wide column">
                    <h4 class="ui dividing header">Add New Platform</h4>
                    {!! Form::open(['url' => action([\App\Http\Controllers\PasswordGroupController::class,'store'])]) !!}
                    <div class="form ui">
                        <div class="inline fields">
                            <div class="field">
                                <label>Group Name : </label>
                                <input required type="text" placeholder="Group Name" name="name">
                            </div>
                            <div class="field">
                                <label>Username : </label>
                                <input required type="text" placeholder="Username" name="username">
                            </div>
                            <div class="field">
                                <label>Password : </label>
                                <input required type="text" placeholder="Password" name="password">
                            </div>
                            <div class="field">
                                <button class="ui primary button">
                                    Save
                                </button>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="row">
                <table class="ui celled table">
                    <thead>
                    <tr><th>Group ID</th>
                        <th>Group Name</th>
                        <th>Action</th>
                    </tr></thead>
                    <tbody>
                    @foreach(\App\Models\PasswordGroup::all() as $group)
                    <tr>
                        <td data-label="id">{{$group->id}}</td>
                        <td data-label="name">{{$group->name}}</td>
                        <td data-label="action"><a data-id="{{$group->id}}" data-username="{{$group->username}}" data-password="{{$group->password}}" data-name="{{$group->name}}" class="edit" href="#">Edit</a>&nbsp;|&nbsp;<a data-id="{{$group->id}}" class="del" href="#">Delete</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div id="DeleteModal" class="ui mini modal">
        <div class="content">
            <p>Are you sure that you want to delete this group?</p>
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
            Edit Group
        </div>
        <div class="content">
            <div class="form ui">
                <div class="field">
                    <label>Group Name : </label>
                    <input required type="text" placeholder="Group Name" id="name">
                </div>
                <div class="field">
                    <label>Username : </label>
                    <input required type="text" placeholder="Username" id="username">
                </div>
                <div class="field">
                    <label>Password : </label>
                    <input required type="text" placeholder="Password" id="password">
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
                let username = $(e.target).data('username');
                let password = $(e.target).data('password');
                $("#username").val(username);
                $("#password").val(password);
                $("#name").val(name);
                $('#EditModal').modal({
                    closable  : false,
                    onDeny    : function(){

                    },
                    onApprove : function() {
                        let id = $(e.target).data('id');
                        editRecord(id,$("#name").val(),$("#username").val(),$("#password").val());
                    }
                }).modal('show');
                e.preventDefault();
            });
        });
        function deleteRecord(id){
            $.ajax({
                url: "{{action([\App\Http\Controllers\PasswordGroupController::class,"index"])}}/" + id,
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
        function editRecord(id,name,username,password){
            $.ajax({
                url: "{{action([\App\Http\Controllers\PasswordGroupController::class,"index"])}}/" + id,
                type: "PUT",
                data: {
                    "id":id,
                    "name":name,
                    "username" : username,
                    "password" : password
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
