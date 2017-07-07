@extends('layouts.default.default')
@section('user')
<div id="page-title">
    <h3>User List</h3>
</div><!-- #page-title -->

<div id="page-content">

    <div class="row">
        <div class="example-box">
            <div class="example-code">

                <table class="table table-condensed">
                    <thead>
                        <tr>
                            <th class="text-center">S/L</th>
                            <th class="text-center">User Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">User Role</th>
                            <th class="text-center">House</th>
                            <th class="text-center">Edit User</th>
                            <th class="text-center">Delete User</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $x = 1; ?>
                        @foreach($users as $user)
                        	<tr>
                            <td class="text-center">{{$x++}}</td>
                            <td class="font-bold text-center">{{ucfirst($user->user_name) }}</td>
                            <td class="text-center">{{$user->mail}}</td>
                            <td class="font-bold text-center">{{$user->role}}</td>
                            <td class="text-center">
                            	@foreach($houses as $house)
                            		@if($house->house_id == $user->house_id)
                            			{{$house->house_name}}
                            		@endif
                            	@endforeach
                            </td> 
                             
                            <td class="text-center">
                                {{Form::open(['route'=>['users.edit',$user->id],'method'=>'get'])}}
                                	<input type="submit" value="edit" class="btn bg-green">
                                {{Form::close()}}
                            </td> 

                            
                            <td class="text-center">  
                                {{Form::open(['route'=>['users.destroy',$user->id],'method'=>'delete','onsubmit'=>'return confirm("Do you really want to Delete the User '.$user->user_name.'?");'])}}
                                	<input type="submit" value="delete" class="btn bg-red">
                                {{Form::close()}}
                            </td>                          
                        </tr>
                        @endforeach

                    </tbody>
                </table>

            </div>
            
        </div>
    </div>
</div>
@stop