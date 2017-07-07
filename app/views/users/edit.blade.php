@extends('layouts.default.default')
@section('user')
<div id="page-title">
    <h3>Edit User Information</h3>

</div><!-- #page-title -->

<div id="page-content">
    <div class="example-box">
        <div class="example-code">
            <div class="row">
                <div class="col-lg-8 col-md-offset-2">
                    <div class="content-box">

                        <div id="edit" class="form-bordered">
                        {{Form::open(['route'=>['users.update',$user->id],'method'=>'put','name'=>'user-edit','class'=>'col-md-12', 'onsubmit'=>'return validate();'])}}
                            <form name="user-edit" class="col-md-12" action="" method="post">
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            Name:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-9">
                                        <input type="text" name="user_name" id="user_name" value="{{$user->user_name}}" required />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            Email:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-9">
                                        <input type="email" name="mail" id="mail" value="{{$user->mail}}" required />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                           New password:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-9">
                                        <input type="password" name="password" id="password" value=""  />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            User Role:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-5">
                                        <select id="role" name="role" class="chosen-select">
                                            <option value="-1">--- Select a Role ---</option>
                                            <option value="admin" <?php
                                    	if($user->role == 'admin') echo " selected";
                                    ?>>admin</option>

                                            <option value="sales" <?php
                                    	if($user->role == 'sales') echo " selected";
                                    ?>>sales</option>
                                            <option value="stock" <?php
                                    	if($user->role == 'stock') echo " selected";
                                    ?>>stock</option>
                                            <option value="account" <?php
                                    	if($user->role == 'account') echo " selected";
                                    ?>>account</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            House:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-5">
                                        <select id="house_id" name="house_id" class="chosen-select">
                                            <option value="-1">--- Select a House ---</option>
                                            @foreach ($houses as $house)
                                            	<option value="{{$house->house_id}}"
                                            		<?php
                                    	if($user->house_id == $house->house_id) echo " selected";
                                    ?>
                                            	>{{$house->house_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                                <div class="form-row pad10A">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="col-md-6">
                                            <input type="submit" value="Update User" class="btn primary-bg large">
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ URL::route('users.index') }}" class="btn large bg-gray" title="">
                                                <span class="button-content"> Cancel</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            {{Form::close()}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    function validate()
    {
        if($('#role').val()==-1)
        {
            alert("Please select a Role!");
            return false;
        }
        if($('#house_id').val()==-1)
        {
            alert("Please select a House!");
            return false;
        }

        return true;
    }

</script>
@stop