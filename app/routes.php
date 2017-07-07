<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('index', function () {

    if (Auth::user()->role == 'owner') {
        return Redirect::route('owner.index');
    }

    return View::make('layouts.default.index');

})->before('auth');

Route::get('/', 'SessionsController@create');

Route::get('login', 'SessionsController@create');

Route::get('logout', 'SessionsController@destroy');

Route::resource('sessions', 'SessionsController');


//ajax request from due payment page
Route::post('chat', function () {

    if (Request::ajax()) {
        $client_id = DB::table('due')
            ->where('bill_id', Input::get('id'))
            ->pluck('client_id');

        $client_info = Client::where('client_id', $client_id)->first();

        $due = DB::table('due')
            ->where('bill_id', Input::get('id'))
            ->pluck('due_amount');

        return Response::json([$client_info, $due]);
    }
});

//ajax request for client info in bill page
Route::post('kat', function () {
    if (Request::ajax()) {
        $client_info = Client::where('client_id', Input::get('id'))->first();

        return Response::json([$client_info]);
    }
});

//ajax request for bill info in return product page
Route::post('rat', function () {

    if (Request::ajax()) {

        $bill_info = Bill::where('bill_id', Input::get('id'))->first();

        $due = DB::table('due')
            ->where('bill_id', Input::get('id'))
            ->pluck('due_amount');

        if ($due == NULL)
            $due = 0;


        $products = BillProduct::where('bill_id', Input::get('id'))->get();

        foreach ($products as $product) {
            $product->total_piece_ordered = $product->total_piece;
        }

        //nazmul_vai start
        if (Input::get('type') == 'chalan') {
            $chalans = DB::table('chalan_parent')->where('parent_bill', Input::get('id'))->lists('id');
            for ($i = 0; $i < sizeof($products); $i++) {

                $already_taken = DB::table('chalan_product')
                    ->whereIn('chalan_id', $chalans)
                    ->where('product_id', $products[$i]->product_code)
                    ->sum('total_piece');
                $product_returned = DB::table('return_transaction')
                    ->where('bill_id', Input::get('id'))
                    ->where('product_id', $products[$i]->product_code)
                    ->sum('return_quantity');

                $products[$i]->total_piece -= $already_taken - $product_returned;
            }
        } else {
            $chalans = DB::table('chalan_parent')->where('parent_bill', Input::get('id'))->lists('id');
            for ($i = 0; $i < sizeof($products); $i++) {
                $already_taken = DB::table('chalan_product')
                    ->whereIn('chalan_id', $chalans)
                    ->where('product_id', $products[$i]->product_code)
                    ->sum('total_piece');
                $product_returned = DB::table('return_transaction')
                    ->where('bill_id', Input::get('id'))
                    ->where('product_id', $products[$i]->product_code)
                    ->sum('return_quantity');
                $products[$i]->total_piece = $already_taken - $product_returned;
            }
        }
        //nazmul_vai end


        $size_array = DB::table('lc_product')
            ->select('product_code', 'unit_product_size')
            ->get();

        return Response::json([$bill_info, $products, $due, $size_array]);
    }

});

Route::get('pok', function () {
  $product_remainedSft = DB::table('lc_product')
                          ->groupBy('product_code')
                          ->selectRaw ('product_code,unit_product_size, sum(quantity) as quantity, sum(wastage_before_stock) as wastage_before_stock')
                          ->get();
                          // dd(json_encode($product_remainedSft));
                          foreach ($product_remainedSft as $product) {
                            $product->quantity -= $product->wastage_before_stock;
                            $product->quantity -= ProductTracker::where('product_code',$product->product_code)->pluck('total_sold_unit');
                            $product->quantity -= ProductTracker::where('product_code',$product->product_code)->pluck('wastage_after_stock');
                            $unit_size = explode('X',$product->unit_product_size);
                            $unit_size = ($unit_size[0] * $unit_size[1])/144;
                            $product->quantity *= $product->quantity;
                            unset($product->wastage_before_stock);
                            unset($product->unit_product_size);
                          }
                          dd(json_encode($product_remainedSft));

});


//Human Resource + users

Route::group(array('before' => 'auth|role:admin-account-f_admin'), function () {

    Route::resource('employees', 'EmployeesController');

    Route::resource('clients', 'ClientsController');

    Route::resource('users', 'UsersController');

});

//Sales
Route::group(array('before' => 'auth|role:sales-admin'), function () {

    Route::controller('chalan', 'ChalanController');

    Route::get('bills/create1', 'BillsController@create1');

    Route::get('bills/create2', 'BillsController@create2');

    Route::get('bills/returnProduct', 'BillsController@return_product');

    Route::get('bills/duePayment', function () {

        $bills = DB::table('due')
            ->where('due_amount', '>', 0)
            ->lists('bill_id');

        return View::make('bills.duePayment')->with('bills', $bills);
    });

    Route::get('printBill1', function () {

        $exception = "balchal";

        return View::make('bills.printBill1');

    });//main bill


    Route::get('printBill2', function () {

        return View::make('bills.printBill2');

    });//due bill


    Route::post('due_transaction', 'BillsController@due_transaction');

    Route::post('return_transaction', 'BillsController@return_transaction');

    Route::resource('bills', 'BillsController');//ei resource ta last a likhte hobe naile conflict hoi

    Route::post('print1', 'BillsController@print1');//customer bill

    Route::post('print2', 'BillsController@print2');//due bill

    Route::get('print3/{challanid}', 'BillsController@print3');//chalan bill

    Route::post('print4', 'BillsController@print4');//office bill
});

Route::group(array('before' => 'auth|role:admin-account-sales'), function () {

    Route::get('printBill1office/{id}', function ($id) {

        return View::make('bills.printBill1office', compact('id'));
    });//office theke korar bill


    //from office
    Route::post('print1office/{id}', 'BillsController@print1office');//customer bill

    Route::post('print2office/{id}', 'BillsController@print2office');//due bill

    Route::post('print3office/{id}', 'BillsController@print3office');//chalan bill

    Route::post('print4office/{id}', 'BillsController@print4office');//office bill
});


//Account

Route::group(array('before' => 'auth|role:account-admin'), function () {

    Route::post('store_expense', 'CalculationsController@storeExpense');

    Route::get('calculations/expenseInput', function () {
        $categories = DB::table('category')
            ->where('cat_type', 'expense')
            ->lists('name');

        $bank_name = DB::table('bank_list')->lists('name');

        $houses = DB::table('house')->get();

        return View::make('calculations.expenseInput', compact('categories', 'bank_name', 'houses'));
    });

    Route::post('store_income', 'CalculationsController@storeIncome');

    Route::get('calculations/incomeInput', function () {
        $categories = DB::table('category')
            ->where('cat_type', 'income')
            ->lists('name');

        $bank_name = DB::table('bank_list')->lists('name');

        $houses = DB::table('house')->get();

        return View::make('calculations.incomeInput', compact('categories', 'bank_name', 'houses'));
    });

    Route::get('calculations/expenseSheet', 'CalculationsController@expenseSheet');

    Route::post('calculations/expenseSheetFiltering', 'CalculationsController@expenseSheetFiltering');

    Route::get('calculations/incomeSheet', 'CalculationsController@incomeSheet');

    Route::post('calculations/incomeSheetFiltering', 'CalculationsController@incomeSheetFiltering');

    Route::get('calculations/createCategory', 'CalculationsController@createCategory');

    Route::post('storeCategory/{type}', 'CalculationsController@storeCategory');

    Route::get('calculations/expenditure', 'CalculationsController@expenditure');

    Route::post('calculations/expenditureFiltering', 'CalculationsController@expenditureFiltering');

    Route::get('tax/{id}', 'SalesReportController@tax');

    Route::get('untax/{id}', 'SalesReportController@untax');

    Route::get('calculations/clientBalance', 'CalculationsController@clientBalance');

    Route::post('calculations/clientBalanceFilter', 'CalculationsController@clientBalanceFilter');

    Route::get('calculations/bankBalance', 'CalculationsController@bankBalance');

    Route::post('calculations/bankBalanceFilter', 'CalculationsController@bankBalanceFilter');
});


//Stock

Route::group(['before' => 'auth|role:stock-admin'], function () {

    Route::post('stockClear', 'StockController@stockClear');

    Route::get('dailyStock', 'StockController@dailyStock');

    Route::get('stockPending', 'StockController@index');

    Route::get('wastage', 'StockController@wastage');

    Route::post('wastageStore', 'StockController@wastageStore');

    Route::resource('lc', 'LcController');


    Route::get('lcDelete/{id}', 'LcController@lcDelete');

});

Route::group(['before' => 'auth|role:stock-admin-sales'], function () {
    Route::resource('products', 'ProductsController');
    Route::post('productFiltering', 'ProductsController@filtering');
});

//Sales Report

Route::group(['before' => 'auth|role:admin-owner-account-stock-f_admin-sales'], function () {

    Route::get('salesReport', 'SalesReportController@index');

    Route::get('chalanReport', 'SalesReportController@chalanIndex');

    Route::get('dueTransaction', 'SalesReportController@dueTransaction');

    Route::post('salesFiltering', 'SalesReportController@sales_filtering');

    Route::post('chalanFiltering', 'SalesReportController@chalan_filtering');

    Route::post('dueTransactionFiltering', 'SalesReportController@dueTransactionFiltering');

    Route::get('print-return/{bill_for}/{return_transaction_id}','BillsController@returnBill');

});

//sales total monthly, by product, by client
Route::group(['before' => 'auth|role:admin-account-stock'], function () {

    Route::get('monthlySales', 'SalesReportController@monthlySales');
    Route::post('monthlySalesFiltering', 'SalesReportController@monthlySalesFiltering');

    Route::get('productSales', 'SalesReportController@productSales');
    Route::post('productSalesFiltering', 'SalesReportController@productSalesFiltering');

    Route::get('clientSales', 'SalesReportController@clientSales');
    Route::post('clientSalesFiltering', 'SalesReportController@clientSalesFiltering');

    Route::get('dueSheet', 'SalesReportController@dueSheet');
    Route::post('dueSheetFiltering', 'SalesReportController@dueSheetFiltering');

    Route::get('returnReport', 'SalesReportController@returnIndex');
    Route::post('returnFiltering', 'SalesReportController@return_filtering');

});

//Only bill reprint filtering for sales
Route::group(['before' => 'auth|role:sales'], function () {

    Route::post('billReprintFilter', 'SalesReportController@sales_reprint_filter');

});

//Employee Payment

Route::group(['before' => 'auth|role:admin-account'], function () {

    Route::get('employee_payment', function () {

        $employees = Employee::all();

        return View::make('employees.payment', compact('employees'));

    });


    Route::post('employee_payment_expense', 'EmployeesController@payment');
});

//Bill Delete

Route::post('billDelete', 'SalesReportController@destroy')->before('auth|role:admin-account');
Route::post('chalanDelete', 'SalesReportController@chalanDestroy')->before('auth|role:admin-account');
Route::get('billEdit', 'BillsController@editBill')->before('auth|role:admin-account-stock');
Route::post('billUpdate', 'BillsController@updateBill')->before('auth|role:admin-account-stock');


// Owner

Route::group(['before' => 'auth|role:admin-owner'], function () {
    Route::resource('owner', 'OwnersController');
});
