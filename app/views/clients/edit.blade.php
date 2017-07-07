    <!-- model binding korte hoi model er instance er sathe. ar form er field gular name
    oi instance mane model er field / table er field er nam er sathe matching hoite hoi -->

    <div id="edit">
        {{ Form::model($client,['route'=>['clients.update',$client->client_id],'name'=>'profile-edit','class'=>'col-md-12','method'=>'put']) }}   

        <div class="form-row">
            <div class="form-label col-md-2">

                {{Form::label('name','Name')}}

            </div>
            <div class="form-input col-md-10" id="name">
                {{ Form::text('client_name') }}                                        
            </div>
        </div>
        <div class="form-row">
            <div class="form-label col-md-2">

                {{Form::label('email','Email')}}
            </div>
            <div class="form-input col-md-10" id="email">

                {{ Form::email('client_email') }}
            </div>
        </div>
        <div class="form-row">
            <div class="form-label col-md-2">

                {{Form::label('mobile','Mobile')}}
            </div>
            <div class="form-input col-md-10" id="phone">

                {{Form::text('mobile_number')}}
            </div>
        </div>
        <div class="form-row">
            <div class="form-label col-md-2">

                {{Form::label('address','Address')}}
            </div>
            <div class="form-input col-md-10" id="phone">

                {{Form::text('address')}}
            </div>
        </div>

        <div class="divider"></div>
        <div class="form-row">
            <div class="form-label col-md-2">
                {{ Form::submit('Save Changes',['class'=>'btn primary-bg medium']) }}                                        
            </div>
            

            {{ HTML::decode(HTML::linkRoute('clients.show', '<span class="button-content"> Cancel</span>', ['id'=>$client->client_id],['class'=>'btn medium bg-gray']) ) }}
           
        </div>


        {{ Form::close() }}


    </div>