@extends('layouts.default.default')

@section('employeeDetails')

 <div id="page-title">
    <h3>Employee Details</h3>
</div><!-- #page-title -->

<div id="page-content">    
    <div class="example-box">
        <div class="example-code">
            <div class="row">
                <div class="col-md-12">
                    <div class="profile-box content-box">
                        <div class="content-box-header clearfix bg-blue-alt">
                        {{HTML::image('img/'.$employee->image, "", ['width'=>72])}}
                            <!-- <img width="72" src="assets/images/adam.jpg" alt="" > -->
                            <div class="user-details">
                                {{ $employee->name }}
                                <span>{{ $employee->designation }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12">
                    <div class="content-box tabs hidden-overflow">
                        

                        <h3 class="content-box-header bg-blue-alt">

                            <ul class="float-left">
                                <li>
                                    <a href="#details" title="Tab 1">
                                        Profile Details
                                    </a>
                                </li>
                                <li>
                                    <a href="#edit" title="Tab 2">
                                        Profile Edit
                                    </a>
                                </li>
                                <li>
                                    <a href="#transactions" title="Tab 3">
                                        Payment
                                    </a>
                                </li>
                            </ul>
                        </h3>

                        <div id="details">
                            <div class="col-md-12">
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Name:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $employee->name }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Email:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $employee->email }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Mobile:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $employee->mobile_number }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Address:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $employee->address }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Joining Date:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $employee->started_working_on }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Salary:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $employee->salary }} (BDT)
                                    </div>
                                </div>

                                <div class="divider"></div>
                                <!--<div class="form-row">
                                    <a href="#" class="btn medium primary-bg" title="">
                                        <span class="button-content"> Send Email</span>
                                    </a>

                                    <a href="#" class="btn medium bg-red" title="">
                                        <span class="button-content"> Delete This Account</span>
                                    </a>
                                </div>
                                -->
                            </div>
                        </div>

                        @include('employees.edit')
                        @include('employees.transactions')  
                        

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