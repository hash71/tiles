 @extends('layouts.default.default')
 @section('addClient')

 <div id="page-title">
    <h3>Add New Client</h3>
</div><!-- #page-title -->

<div id="page-content">
    <div class="example-box">
        <div class="example-code">
            <div class="row">
                <div class="col-lg-10 col-md-offset-1">
                    <div class="content-box">

                        <div id="edit" class="form-bordered">

                            {{ Form::open(['route'=>'clients.store'],['name'=>'profile-edit','class'=>'col-md-12']) }}
                            
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Name:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <input type="text" name="client_name" id="name" value="" required />
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Email:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <input type="email" name="client_email" id="email" value=""/>
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Mobile:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-4">
                                        <input type="text" name="mobile_number" id="phone" value="" required/>
                                    </div>
                                </div>

                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Address:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        <input type="text" name="address" id="address" value="" />
                                    </div>
                                </div>

                                
                                <div class="form-row pad10A">
                                    <div class="col-md-6 col-md-offset-4">
                                        <div class="col-md-6">
                                            <input type="submit" value="Add Client" class="btn primary-bg large">
                                        </div>
                                        <div class="col-md-6">
                                            <a href="{{ URL::route('clients.index') }}" class="btn large bg-gray" title="">
                                                <span class="button-content"> Cancel</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>

                            {{ Form::close() }}
                        </div>


                    </div>    
                </div>
            </div>
        </div>     
    </div>
</div>

@stop