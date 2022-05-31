@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="sixteen wide column">
                    <h4 class="ui dividing header">Add New Tag</h4>
                    {!! Form::open(['url' => action([\App\Http\Controllers\TagsController::class,'store']),
                    'autocomplete'=>'off']) !!}
                    <div class="form ui">
                        <div class="fields">
                            <div class="field">
                                <label>Tag Name : </label>
                                <input required type="text" placeholder="Name" name="name">
                            </div>
                            <div class="field">
                                <label>Action : </label>

                                <input type="submit" value="Save" class="ui primary button" />


                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="row">
                <table class="ui celled table">
                    <thead>
                    <tr><th>ID</th>
                        <th>Name</th>
                        <th>Total Servers</th>
                        <th>Action</th>
                    </tr></thead>
                    <tbody>
                    @foreach(\App\Models\Tag::all() as $tag)
                        <tr>
                            <td data-label="id">{{$tag['id']}}</td>
                            <td>{{$tag['name']}}</td>
                            <td>{{count($tag->tags)}}</td>
                            <td><a data-id="{{$tag['id']}}" data-name="{{$tag['name']}}" class="edit" href="#">Edit</a>&nbsp;|&nbsp;<a data-id="{{$tag['id']}}" class="del" href="#">Delete</a></td>
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
                    <input type="text" placeholder="Tag Name" name="value" id="edit">
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
                url: "{{action([\App\Http\Controllers\TagsController::class,"index"])}}/" + id,
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
                url:"{{action([\App\Http\Controllers\TagsController::class,"index"])}}/" + id,
                type: "PUT",
                data: {
                    "id":id,
                    "name":name
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
