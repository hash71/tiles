@extends('layouts.default.default')
@section('addEmployee')
<div id="page-title">
    <h3>Add New Employee</h3>

</div><!-- #page-title -->

<div id="page-content">    
    <div class="example-box">
        <div class="example-code">
            <div class="row">
                <div class="col-lg-8 col-md-offset-2">
                    <div class="content-box">

                        <div id="edit" class="form-bordered">

                            <form name="profile-edit" class="col-md-12" action="{{ URL::route('employees.store') }}" method="post" enctype="multipart/form-data">
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            Name:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-5">
                                        <input type="text" name="name" id="name" value="" required />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            Email:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-5">
                                        <input type="email" name="email" id="email" value="" required />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            Designation:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-3">
                                        <input type="text" name="designation" id="designation" value="" />
                                    </div>
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Mobile:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <input type="text" name="mobile_number" id="phone" value="" />
                                    </div>
                                </div>
                                
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            Joining Date:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-3">
                                        <input type="text"  class="datepicker" name="started_working_on" id="joindate" value="" required/>
                                    </div>
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Salary:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <input type="number" name="salary" id="salary" min="0" value="0" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-3">
                                        <label for="">
                                            Address:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-9">
                                        <input type="text" name="address" id="address" value="" />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-3" style="margin-top: 70px;">
                                        <label for="">
                                            Image:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-9">
                                        <input type="file" name="file" id="file" class="col-md-8 float-left" onchange="readURL(this);" style="margin-top:70px;">
                                        
                                        {{ HTML::image('img/placeholder.jpg', 'Image', array('class' => 'col-md-4','id' => 'blah')) }}  
                                    </div>
                                </div>
                                <div class="form-row pad10A">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="col-md-6">
                                            <input type="submit" value="Add" class="btn primary-bg large">
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ URL::route('employees.index') }}" class="btn large bg-gray" title="">
                                                <span class="button-content"> Cancel</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            </form>
                        </div>

                    </div>    
                </div>
            </div>
        </div>     
    </div>
</div>

<!-- image preview script-->
    <script>
    function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#blah')
                        .attr('src', e.target.result)
                        .width(150)
                        .height(200);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop