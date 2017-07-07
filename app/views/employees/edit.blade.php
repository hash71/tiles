<div id="edit">

{{ Form::model($employee, ['route'=>['employees.update',$employee->id],'method'=>'put','class'=>'col-md-12']) }}
    <!--        <form name="profile-edit" class="col-md-12" action="" method="post"> -->
    <div class="form-row">
        <div class="form-label col-md-2">
            <label for="">
                Name:
            </label>
        </div>

        <div class="form-input col-md-10">
            <input type="text" name="name" id="name" value={{$employee->name}} required />
        </div>

    </div>

    <div class="form-row">
        <div class="form-label col-md-2">
            <label for="">
                Email:
            </label>
        </div>

        <div class="form-input col-md-10">
            <input type="email" name="email" id="email" value={{$employee->email}} required />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label col-md-2">
            <label for="">
                Designation:
            </label>
        </div>
        <div class="form-input col-md-10">
            <input type="text" name="designation" id="designation" value={{$employee->designation}} />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label col-md-2">
            <label for="">
                Mobile:
            </label>
        </div>
        <div class="form-input col-md-10">
            <input type="text" name="mobile_number" id="phone" value={{$employee->mobile_number}} />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label col-md-2">
            <label for="">
                Address:
            </label>
        </div>
        <div class="form-input col-md-10">
            <input type="text" name="address" id="address" value={{$employee->address}} />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label col-md-2">
            <label for="">
                Joining Date:
            </label>
        </div>
        <div class="form-input col-md-10">
            <input type="text" name="joindate" id="joindate" value={{$employee->started_working_on}} disabled="true" />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label col-md-2">
            <label for="">
                Salary:
            </label>
        </div>
        <div class="form-input col-md-10">
            <input type="text" name="salary" id="salary" value={{$employee->salary}} />
        </div>
    </div>
    <div class="form-row">
        <div class="form-label col-md-2" style="margin-top: 70px;">
            <label for="">
                Image:
            </label>
        </div>
        <div class="form-input col-md-10">
            <input type="file" name="file" id="file" class="col-md-8 float-left" onchange="readURL(this);" style="margin-top:70px;">

            <img src="../img/{{$employee->image}}" alt="Image" class="col-md-2" id="blah"> 
        </div>
    </div>

    <div class="divider"></div>
    <div class="form-row">
        <div class="form-label col-md-2">
            <input type="submit" value="Save Changes" class="btn primary-bg medium">
        </div>

        {{ HTML::decode(HTML::linkRoute('employees.show', '<span class="button-content"> Cancel</span>', ['id'=>$employee->id],['class'=>'btn medium bg-gray']) )}}           
        
    </div>

    {{ Form::close()}}
</div>