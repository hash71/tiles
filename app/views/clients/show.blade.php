@extends('layouts.default.default')

@section('clientDetails')

 <div id="page-title">
    <h3>Client Details</h3>
</div><!-- #page-title -->

<div id="page-content">
    <h2>{{ $client->client_name }}</h2><br/>

    <div class="example-box">
        <div class="example-code">
            <div class="row">
                <div class="col-lg-12">
                    <div class="content-box tabs hidden-overflow">
                        <h3 class="content-box-header bg-green">
                        
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
                                        {{ $client->client_name }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Email:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $client->client_email }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Mobile:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $client->mobile_number }}
                                    </div>
                                </div>
                                <div class="form-row">
                                    <div class="form-label col-md-2">
                                        <label for="">
                                            Address:
                                        </label>
                                    </div>
                                    <div class="form-input col-md-10">
                                        {{ $client->address }}
                                    </div>
                                </div>

                                <div class="divider"></div>
                                <!--
                                <div class="form-row">
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

                        
                            @include('clients.edit');
                           
                            

                        

                    </div>    
                </div>
            </div>
        </div>     
    </div>
</div>

@stop