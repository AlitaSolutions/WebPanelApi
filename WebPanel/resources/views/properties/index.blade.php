@extends('master')

@section('content')
    <div class="ui vertical stripe segment">
        <div class="ui middle aligned stackable grid container">
            <div class="row">
                <div class="sixteen wide column">
                    <h4 class="ui dividing header">Add New Property</h4>
                    {!! Form::open(['url' => action([\App\Http\Controllers\PropertyController::class,'store']),
                    'autocomplete'=>'off']) !!}
                    <div class="form ui">
                        <div class="fields">
                            <div class="field">
                                <label>Property Name : </label>
                                <input required type="text" placeholder="Name" name="name">
                            </div>
                            <div class="field">
                                <label>Default : </label>
                                <input type="text" placeholder="Default" name="default">

                            </div>
                            <div class="field">
                                <label>Service Name : </label>
                                <div class="ui selection dropdown">
                                    <input type="hidden" name="service_id">
                                    <i class="dropdown icon"></i>
                                    <div class="default text">Service Name</div>
                                    <div class="menu">
                                        @foreach(\App\Models\Service::all() as $service)
                                            <div class="item" data-value="{{$service->id}}">{{$service->name}} - {{$service->platform->name}}</div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label>Data Type : </label>
                                <div class="ui selection dropdown">
                                    <input type="hidden" name="type" value="0">
                                    <i class="dropdown icon"></i>
                                    <div data-value="0" class="default text">Text</div>
                                    <div class="menu">
                                        <div class="item" data-value="0">Text</div>
                                        <div class="item" data-value="1">Number</div>
                                        <div class="item" data-value="2">Boolean</div>
                                    </div>
                                </div>
                            </div>
                            <div class="field">
                                <label>Action : </label>

                                <input type="submit" value="Save" class="ui primary button"/>


                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <div class="row">
                <table class="ui celled table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Service</th>
                        <th>Platform</th>
                        <th>Data Type</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach(\App\Models\Property::all() as $property)
                        <tr>
                            <td data-label="id">{{$property['id']}}</td>
                            <td>{{$property['name']}}</td>
                            <td>{{$property->service->name}}</td>
                            <td>{{$property->service->platform->name}}</td>
                            @if($property->type == 0)
                                <td>Text</td>
                            @elseif($property->type == 1)
                                <td>Number</td>
                            @elseif($property->type == 2)
                                <td>Boolean</td>
                            @endif
                            <td><a data-id="{{$property['id']}}" data-name="{{$property['name']}}" class="edit"
                                   href="#">Edit</a>&nbsp;|&nbsp;<a data-id="{{$property['id']}}" class="del" href="#">Delete</a>
                            </td>
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
                    <input type="text" placeholder="Property Name" name="value" id="edit">
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
        $(document).ready(() => {
            $('.ui.dropdown')
                .dropdown();
            $(".del").click((e) => {
                $('#DeleteModal').modal({
                    closable: false,
                    onDeny: function () {

                    },
                    onApprove: function () {
                        let id = $(e.target).data('id');
                        deleteRecord(id);
                    }
                }).modal('show');
                e.preventDefault();
            });
            $(".edit").click((e) => {
                let name = $(e.target).data('name');
                $("#edit").val(name);
                $('#EditModal').modal({
                    closable: false,
                    onDeny: function () {

                    },
                    onApprove: function () {
                        let id = $(e.target).data('id');
                        editRecord(id, $("#edit").val());
                    }
                }).modal('show');
                e.preventDefault();
            });
        });

        function deleteRecord(id) {
            $.ajax({
                url: "{{action([\App\Http\Controllers\PropertyController::class,"index"])}}/" + id,
                type: "DELETE",
                data: {
                    "id": id
                },
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert('Unable to delete record');
                }
            });
        }

        function editRecord(id, name) {
            $.ajax({
                url: "{{action([\App\Http\Controllers\PropertyController::class,"index"])}}/" + id,
                type: "PUT",
                data: {
                    "id": id,
                    "name": name
                },
                success: function () {
                    location.reload();
                },
                error: function () {
                    alert('Unable to edit record');
                }
            });
        }
    </script>
@stop
