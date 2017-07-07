@extends('layouts.default.default')
@section('createCategory')
<div id="page-title">
    <h3>Add New Category</h3>
</div><!-- #page-title -->

<div id="page-content">
    <div class="example-box">
        <div class="example-code">

            <div class="row">
                <div class="col-md-4">

                    <div class="profile-box content-box">
                        <div class="content-box-header clearfix bg-blue-alt">
                            <div class="font-size-23 text-center">
                                Income Category
                            </div>
                        </div>
                        <div class="nav-list pad20L">
                                @foreach($income_category as $i)
                                    <li><a href="#" title="">{{$i}}</a><li>                                    
                                @endforeach
                        </div>

                        
                        {{Form::open(['url'=>['storeCategory','income'],'method'=>'post','onsubmit'=>'return validateincome(this);'])}}
                            <div class="form-input">
                                <input id="incomeinput" name="cat_name" type="text" placeholder="Add New Income Category.....">
                            </div>
                            <input class="btn medium primary-bg col-md-4 col-md-offset-4 mrg10T" type="submit" value="Add New">
                        {{Form::close()}}     

                    </div>

                </div>
                <div class="col-md-4">

                    <div class="profile-box content-box">
                        <div class="content-box-header clearfix bg-green">
                            <div class="font-size-23 text-center">
                                Expense Category
                            </div>
                        </div>
                        <div class="nav-list pad20L">
                            <ul>
                                @foreach($expense_category as $e)
                                    <li><a href="#" title="">{{$e}}</a><li>                                    
                                @endforeach
                            </ul>
                        </div>

                        {{Form::open(['url'=>['storeCategory','expense'],'method'=>'post','onsubmit'=>'return validateexpense(this);'])}}
                            <div class="form-input">
                                <input id="expenseinput" name="cat_name" type="text" placeholder="Add New Expense Category.....">
                            </div>
                            <input class="btn medium bg-green col-md-4 col-md-offset-4 mrg10T" type="submit" value="Add New">
                        {{Form::close()}}

                    </div>

                </div>
                <div class="col-md-4">

                    <div class="profile-box content-box">
                        <div class="content-box-header clearfix bg-orange">
                            <div class="font-size-23 text-center">
                                Bank
                            </div>
                        </div>
                        <div class="nav-list pad20L">
                            <ul>
                                @foreach($bank_name as $bank)
                                    <li><a href="#" title="">{{$bank}}</a><li>                                    
                                @endforeach
                            </ul>
                        </div>
                        {{Form::open(['url'=>['storeCategory','bank'],'method'=>'post','onsubmit'=>'return validatebank(this);'])}}
                        
                            <div class="form-input">
                                <input id="bankinput" name="cat_name" type="text" placeholder="Add New Bank.....">
                            </div>
                            <input class="btn medium bg-orange col-md-4 col-md-offset-4 mrg10T" type="submit" value="Add New">
                        {{Form::close()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
//Income
<script type="text/javascript">
function validateincome()
{
    var field = document.getElementById('incomeinput');
    if(field.value == "")
    {
        field.focus();
        alert('Please Enter a value in Input Field.');
        return false;
    }
    return true;
}

function validateexpense()
{
    var field = document.getElementById('expenseinput');
    if(field.value == "")
    {
        field.focus();
        alert('Please Enter a value in Input Field.');
        return false;
    }
    return true;
}

function validatebank()
{
    var field = document.getElementById('bankinput');
    if(field.value == "")
    {
        field.focus();
        alert('Please Enter a value in Input Field.');
        return false;
    }
    return true;
}
</script>

@stop