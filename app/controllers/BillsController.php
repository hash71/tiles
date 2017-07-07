<?php

class BillsController extends \BaseController
{

    /**
     * All bill related works done here
     *
     * @return Response
     */


    /** This is an unused function, not routed from project **/
    public function index()
    {
        $bills = Bill::all();

        return View::make('bills.index', compact('bills'));
    }


    /** Show the form for creating a new Regular bill **/
    public function create1()
    {
        $clients = Client::all();

        //Get product lists for dropdown autocomplete option
        $product_code = ProductTracker::lists('product_code');
        $product_size = DB::table('lc_product')->distinct()->select('product_code', 'unit_product_size')->get();

        $remain_at_stock = ChalanController::remainingProductCount();

        return View::make('bills.create1', compact('clients', 'product_code', 'product_size','remain_at_stock'));
    }

    /** Show the form for creating a new Request bill **/
    public function create2()
    {
        $clients = Client::all();

        //Get product lists for dropdown autocomplete option
        $product_code = ProductTracker::lists('product_code');
        $product_size = DB::table('lc_product')->distinct()->select('product_code', 'unit_product_size')->get();

        $remain_at_stock = ChalanController::remainingProductCount();

        return View::make('bills.create2', compact('clients', 'product_code', 'product_size','remain_at_stock'));
    }


    /** Store a newly created bill in storage. **/
    public function store()
    {
      /*If anyway user bypasses the client side validation for remaining product, then check it again here*/
      $remain_at_stock = ChalanController::remainingProductCount();
      $x = explode('(', Input::get('product_code1'));
      $pc = $x[0];
      $i = 1;

      while ($pc) {
          $x = explode('(', Input::get('product_code' . $i));

          $prod_code = $x[0];
          $product_qty = Input::get('product_quantity' . $i);

          if( $remain_at_stock[$prod_code] < $product_qty) //Not enough product to deliver, stop creating chalan bill
          {
              return Redirect::to('bills/create1')->with('error', 'Some products are not available at stock.');
          }

          $i++;
          $x = explode('(', Input::get('product_code' . $i));
          $pc = $x[0];
      }
      /* Check Ends */


        /* 1st a client table a kaj korte hobe */

        $selected_value = Input::get('name'); //get client name

        //If No existing client selected, then We need to create a new Client
        if ($selected_value == -1) {
            $selected_value = time() - 1413616082; //Generating unique id for client

            $c = new Client;

            $c->client_id = $selected_value;
            $c->client_name = Input::get('client_name');
            $c->mobile_number = Input::get('mobile_number');
            $c->client_email = Input::get('client_email');
            $c->address = Input::get('address');

            try {
                $c->save();
            } catch (Exception $e) {
                return Redirect::back()->with('message','Couldn\'t create bill, If the client already exists choose it from dropdown');
            }

        } else //Client already exists, If there is any change in info, update client
        {
            Client::where('client_id', $selected_value)
                ->update(
                    [
                        'client_name' => Input::get('client_name'),
                        'mobile_number' => Input::get('mobile_number'),
                        'client_email' => Input::get('client_email'),
                        'address' => Input::get('address'),
                    ]);

        }


        /** bf table update **/
        $timestamp = date('Y-m-d', strtotime('+1 day', time()));//ajker date er sathe 1 jog

        $total = Input::get('cash');//+Input::get('cheque')+Input::get('credit_card');

        //already present day te kono entry ache kina checking
        $d = DB::table('bf')
            ->where('date', '>=', date('Y-m-d'))
            ->where('date', '<', $timestamp)
            ->where('house_id', Auth::user()->house_id)
            ->first();


        if (sizeof($d)) //jodi entry thake taile update
        {
            $total = $total + DB::table('bf')->where('id', $d->id)->pluck('bf');
            DB::table('bf')
                ->where('id', $d->id)
                ->update(['bf' => $total]);
        } else //entry na thakle insert notun record
        {
            $bf = 0;

            $bf = DB::table('bf')->where('date', '<', date('Y-m-d'))
                ->where('house_id', Auth::user()->house_id)
                ->orderBy('date', 'desc')
                ->pluck('bf');

            // bf updated
            $total += $bf;
            DB::table('bf')->insert(['house_id' => Auth::user()->house_id, 'bf' => $total, 'date' => date('Y-m-d H:i:s')]);
        }
        /** bf table update end **/


        /*ekhon bill ar 2 ta table a kaj */

        $bill_id = time() - 1413616082;//uniqe bill id hiasbe timestamp nilam


        $bill = new Bill;//bill info table a entry ditechi

        $bill->bill_id = $bill_id;
        $bill->ref_bill_id = Input::get('ref_bill_id');
        $bill->client_id = $selected_value;
        $bill->gross = Input::get('gross');
        $bill->less = Input::get('less');
        $bill->net = Input::get('net');
        $bill->cash = Input::get('cash');
        $bill->cheque = Input::get('cheque');
        $bill->credit_card = Input::get('credit_card');
        $bill->salesman_id = Auth::user()->id;
        $bill->adjust_gross = Input::get('custgross');
        $bill->adjust_discount = Input::get('custless');
        $bill->bill_date = date('Y-m-d H:i:s');
        $bill->carrying_cost = Input::get('carryingcost');

        if(empty(Input::get('ref_bill_date')))
          $bill->ref_bill_date = $bill->bill_date;
        else
          $bill->ref_bill_date = Input::get('ref_bill_date');

        $bill->save();


        /** Working on due table **/

        //only Cash amount will be deducted, and remaining total(including check) will be considered as due
        $due_amount = (Input::get('net') + Input::get('carryingcost')) - (Input::get('cash'));

        if ($due_amount > 0) //If there is any due in the bill, make new entry in due table
        {
            DB::table('due')
                ->insert(
                    [
                        'bill_id' => $bill_id,
                        'client_id' => $selected_value,
                        'due_amount' => $due_amount
                    ]);
        }

        /** Due table work ends **/


        /** Working on Bill_product table **/
        $x = explode('(', Input::get('product_code1'));
        $pc = $x[0];
        $i = 1;

        while ($pc) {
            $bp = new BillProduct;
            $bp->bill_id = $bill_id;

            $x = explode('(', Input::get('product_code' . $i));


            $bp->product_code = $x[0];
            $bp->product_quantity = Input::get('product_quantity' . $i);
            $bp->unit_sale_price = Input::get('unit_sale_price' . $i);
            $bp->adjust_unit_price = Input::get('adjust_unit_price' . $i);

            //Entry in owner table
            $totalSale = DB::table('owner_calculation')->where('product_code', $x[0])->pluck('total_sale');

            //change
            $product_size = DB::table('lc_product')
                ->where('product_code', $x[0])
                ->pluck('unit_product_size');

            $y = explode('X', $product_size);

            $size = $y[0] * $y[1];

            $bp->total_piece = ceil((Input::get('product_quantity' . $i) * 144) / $size);

            $bp->save();

            $i++;

            $x = explode('(', Input::get('product_code' . $i));

            $pc = $x[0];
        }


        View::share('check', 1);

        return Redirect::to('printBill1');
    }


    /** This is an unused function, not routed from project **/
    public function show($id)
    {
        $bill = Bill::findOrFail($id);

        return View::make('bills.show', compact('bill'));
    }

    /** This is an unused function, not routed from project **/
    public function edit($id)
    {
        $bill = Bill::find($id);

        return View::make('bills.edit', compact('bill'));
    }


    /** This is an unused function, not routed from project **/
    public function update($id)
    {
        $bill = Bill::findOrFail($id);

        $validator = Validator::make($data = Input::all(), Bill::$rules);

        if ($validator->fails()) {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $bill->update($data);

        return Redirect::route('bills.index');
    }


    /** This is an unused function, not routed from project **/
    public function destroy($id)
    {
        Bill::destroy($id);

        return Redirect::route('bills.index');
    }


    /** Due payment form submit handler **/
    public function due_transaction()
    {

        /** Update bf table **/

        $bill = Bill::where('bill_id', Input::get('bill_id'))->first();

        $timestamp = date('Y-m-d', strtotime('+1 day', time()));//ajker date er sathe 1 jog

        $cash = Input::get('cash');

        $total = $cash;

        $d = DB::table('bf')
            ->where('date', '>=', date('Y-m-d'))
            ->where('date', '<', $timestamp)
            ->where('house_id', Auth::user()->house_id)
            ->first();

        if (sizeof($d)) //already entry thakle update
        {
            $total = $total + DB::table('bf')->where('id', $d->id)->pluck('bf');
            DB::table('bf')
                ->where('id', $d->id)
                ->update(['bf' => $total]);
        } else //entry na thakle insert notun record
        {
            $bf = 0;

            $bf = DB::table('bf')
                ->where('date', '<', date('Y-m-d'))
                ->where('house_id', Auth::user()->house_id)
                ->orderBy('date', 'desc')
                ->pluck('bf');

            $total += $bf;
            DB::table('bf')
                ->insert(
                    [
                        'house_id' => Auth::user()->house_id,
                        'bf' => $total,
                        'date' => date('Y-m-d H:i:s')
                    ]);
        }
        /*bf update ends*/


        /** Insert in Due_transaction table **/
        $bill_id = Input::get('bill_id');
        $cheque = Input::get('cheque');
        $credit_card = Input::get('credit_card');
        $less = Input::get('less');
        $new_due = Input::get('olddue') - ($cash + $less);

        DB::table('due_transaction')
            ->insert(
                [
                    'bill_id' => $bill_id,
                    'cash' => $cash,
                    'cheque' => $cheque,
                    'credit_card' => $credit_card,
                    'less' => $less,
                    'house_id' => Auth::user()->house_id,
                    'prev_due' => Input::get('olddue'),
                    'due_pay_date' => date('Y-m-d H:i:s')
                ]);


        //update Due table
        DB::table('due')
            ->where('bill_id', $bill_id)
            ->update(['due_amount' => $new_due]);


        return Redirect::to('printBill2');
    }


    /** Return product page View Get request  **/
    public function return_product()
    {
        $bill = Bill::lists('bill_id');
        return View::make('bills.returnProduct')->with('bills', $bill);
    }


    /** Return transaction form post handler **/
    public function return_transaction()
    {
        $bill_id = Input::get('name');

        $i = 1;

        $return_transaction_id = time() - 1413616082;

        //return transaction table a insert korlam
        while (Input::get('code' . $i) != null) {

            /** owner table insert **/
            $totalSale = DB::table('owner_calculation')
                ->where('product_code', Input::get('code' . $i))
                ->pluck('total_sale');

            $totalSale = $totalSale - (Input::get('returnsft' . $i) * Input::get('rate' . $i));

            DB::table('owner_calculation')
                ->where('product_code', Input::get('code' . $i))
                ->update(['total_sale' => $totalSale]);

            /** owner table insert end **/


            //If this product have some return value, insert it in return transaction table
            if (Input::get('returnqty' . $i) > 0) {
                DB::table('return_transaction')
                    ->insert(
                        [
                            'bill_id' => $bill_id,
                            'return_transaction_id' => $return_transaction_id,
                            'product_id' => Input::get('code' . $i),
                            'return_quantity' => Input::get('returnqty' . $i),
                            'salesman_id' => Auth::user()->id,
                            'return_date' => date('Y-m-d H:i:s')
                        ]);
            }


            //Updating product tracker table with the return amount of the product
            $sold = ProductTracker::where('product_code', Input::get('code' . $i))->pluck('total_sold_unit');

            $sold -= Input::get('returnqty' . $i);

            $affectedRows = ProductTracker::where('product_code', Input::get('code' . $i))->update(array('total_sold_unit' => $sold));

            $i++;
        }

        /* Updating Due table deducting the return product price */
        if (Input::get('due') > 0) {
            DB::table('due')
                ->where('bill_id', $bill_id)
                ->update(['due_amount' => Input::get('due')]);
        }

        /* Updating Bill_info table with the cashback money amount */
        if (Input::get('back') > 0) {
            Bill::where('bill_id', $bill_id)->update(['cashback' => Input::get('back')]);
        }

        return View::make('bills.printReturnBill',compact('return_transaction_id'));
    }

    public function returnBill($bill_for,$return_transaction_id)
    {
      $bill_info = DB::table('return_transaction')->where('return_transaction_id',$return_transaction_id)->get();

      $singledata = DB::table('return_transaction')->where('return_transaction_id',$return_transaction_id)->first();

      $salesman_house = DB::table('user')->where('id',$singledata->salesman_id)->pluck('house_id');

      $bill_for = ucfirst($bill_for).' copy';

      $client_id = Bill::where('bill_id',$singledata->bill_id)->pluck('client_id');

      $client = Client::where('client_id',$client_id)->first();

      return View::make('bills.printReturn',compact('bill_info','salesman_house','bill_for','client','singledata'));
    }


    public function editBill()
    {
        $bills = Bill::orderBy('bill_id', 'desc')->lists('bill_id');

        return View::make('bills.billEdit', compact('bills'));
    }

    public function updateBill()
    {

        $bill_id = Input::get('name');

        $sum = 0;

        $i = 1;

        while (Input::get('code' . $i) != null) {

            $prod_sft = Input::get('returnsft' . $i);

            $prev = BillProduct::where('bill_id', $bill_id)->where('product_code', Input::get('code' . $i))->first();

            $sum += ($prod_sft * $prev->unit_sale_price);

            $qty = $prev->product_quantity - $prod_sft;

            $pc = $prev->total_piece - Input::get('returnqty' . $i);

            BillProduct::where('bill_id', $bill_id)->where('product_code', Input::get('code' . $i))->update([
                'product_quantity' => $qty,
                'total_piece' => $pc
            ]);

            $i++;
        }

        // dd($sum);

        $amnt = DB::table('due')
                ->where('bill_id', $bill_id)
                ->pluck('due_amount') - $sum;

        if ($amnt < 0) {
            $amnt = 0;
        }

        DB::table('due')
            ->where('bill_id', $bill_id)
            ->update([
                'due_amount' => $amnt
            ]);

        $bill = Bill::where('bill_id', $bill_id)->first();

        $less = $bill->less + $sum;

        if($less > $bill->gross)
          $less = $bill->gross;

        $net = $bill->gross - $less;

        Bill::where('bill_id',$bill_id)->update([
                                                    'less' => $less,
                                                    'net' => $net
                                                ]);

        return Redirect::to('billEdit')->with('message','Bill released successfully');
    }


    /** Bill print page get request handler, This one is for customer copy **/
    public function print1()
    {

        //Get Bill info, client info and product info
        $bill_info = Bill::orderBy('bill_id', 'desc')
            ->where('salesman_id', Auth::user()->id)
            ->first();

        $client = Client::where('client_id', $bill_info->client_id)->first();

        $products = BillProduct::where('bill_id', $bill_info->bill_id)->get();


        /*Make group of the similar products based on their mother code*/
        for ($i = 0; $i < sizeof($products); $i++) {
            $products[$i]->size = DB::table('lc_product')
                ->where('product_code', $products[$i]->product_code)
                ->pluck('unit_product_size');

            $x = explode('-', $products[$i]->product_code);

            for ($k = $i + 1; $k < sizeof($products); $k++) {
                $y = explode('-', $products[$k]->product_code);

                if ($x[0] == $y[0]) {
                    $products[$i]->product_quantity += $products[$k]->product_quantity;

                    $products[$k]->product_code = null;
                }
            }
        }

        foreach ($products as $key => $value) {
            if (is_null($value->product_code)) {
                unset($products[$key]);
            } else {
                $z = explode('-', $value->product_code);
                $products[$key]->product_code = $z[0];
            }
        }
        /* 	Grouping products ends 	*/


        $bill_for = Input::get('bill_for');

        $salesman = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('user_name');

        $salesman_house = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('house_id');


        return View::make('bills.print1', compact('salesman_house', 'salesman', 'bill_info', 'products', 'client', 'due', 'bill_for'));
    }


    /** Printing bill later from office, this one is for customer copy **/
    public function print1office($id)
    {
        //Get Bill info, client info and product info
        $bill_info = Bill::where('bill_id', $id)->first();

        $client = Client::where('client_id', $bill_info->client_id)->first();

        $products = BillProduct::where('bill_id', $bill_info->bill_id)->get();


        /*Make group of the similar products based on their mother code*/
        for ($i = 0; $i < sizeof($products); $i++) {
            $products[$i]->size = DB::table('lc_product')
                ->where('product_code', $products[$i]->product_code)
                ->pluck('unit_product_size');


            $x = explode('-', $products[$i]->product_code);

            for ($k = $i + 1; $k < sizeof($products); $k++) {
                $y = explode('-', $products[$k]->product_code);

                if ($x[0] == $y[0]) {
                    $products[$i]->product_quantity += $products[$k]->product_quantity;

                    $products[$k]->product_code = null;
                }
            }
        }

        foreach ($products as $key => $value) {
            if (is_null($value->product_code)) {
                unset($products[$key]);
            } else {
                $z = explode('-', $value->product_code);
                $products[$key]->product_code = $z[0];
            }
        }
        /* 	Grouping products ends 	*/


        $bill_for = Input::get('bill_for');

        $salesman = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('user_name');

        $salesman_house = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('house_id');

        return View::make('bills.print1', compact('salesman_house', 'salesman', 'bill_info', 'products', 'client', 'due', 'bill_for'));
    }


    /** Bill print page get request handler, This one is for office copy **/
    public function print4()
    {
        //Get Bill info, client info and product info
        $bill_info = Bill::orderBy('bill_id', 'desc')
            ->where('salesman_id', Auth::user()->id)
            ->first();

        $client = Client::where('client_id', $bill_info->client_id)->first();

        $products = BillProduct::where('bill_id', $bill_info->bill_id)->get();


        /*Make group of the similar products based on their mother code*/
        for ($i = 0; $i < sizeof($products); $i++) {

            $products[$i]->size = DB::table('lc_product')
                ->where('product_code', $products[$i]->product_code)
                ->pluck('unit_product_size');

            $x = explode('-', $products[$i]->product_code);

            for ($k = $i + 1; $k < sizeof($products); $k++) {
                $y = explode('-', $products[$k]->product_code);

                if ($x[0] == $y[0]) {
                    $products[$i]->product_quantity += $products[$k]->product_quantity;

                    $products[$k]->product_code = null;
                }
            }
        }

        foreach ($products as $key => $value) {
            if (is_null($value->product_code)) {
                unset($products[$key]);
            } else {
                $z = explode('-', $value->product_code);
                $products[$key]->product_code = $z[0];
            }
        }
        /* 	Grouping products ends 	*/


        $bill_for = Input::get('bill_for');

        $salesman = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('user_name');

        $salesman_house = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('house_id');

        return View::make('bills.print4', compact('salesman_house', 'salesman', 'bill_info', 'products', 'client', 'due', 'bill_for'));
    }


    /** Printing bill later from office, this one is for office copy **/
    public function print4office($id)
    {
        //Get Bill info, client info and product info
        $bill_info = Bill::where('bill_id', $id)->first();

        $client = Client::where('client_id', $bill_info->client_id)->first();

        $products = BillProduct::where('bill_id', $bill_info->bill_id)->get();


        /*Make group of the similar products based on their mother code*/
        for ($i = 0; $i < sizeof($products); $i++) {

            $products[$i]->size = DB::table('lc_product')
                ->where('product_code', $products[$i]->product_code)
                ->pluck('unit_product_size');

            $x = explode('-', $products[$i]->product_code);

            for ($k = $i + 1; $k < sizeof($products); $k++) {
                $y = explode('-', $products[$k]->product_code);

                if ($x[0] == $y[0]) {
                    $products[$i]->product_quantity += $products[$k]->product_quantity;

                    $products[$k]->product_code = null;
                }
            }
        }

        foreach ($products as $key => $value) {
            if (is_null($value->product_code)) {
                unset($products[$key]);
            } else {
                $z = explode('-', $value->product_code);
                $products[$key]->product_code = $z[0];
            }
        }
        /* 	Grouping products ends 	*/


        $bill_for = Input::get('bill_for');

        $salesman = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('user_name');

        $salesman_house = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('house_id');

        return View::make('bills.print4', compact('salesman_house', 'salesman', 'bill_info', 'products', 'client', 'due', 'bill_for'));
    }


    /** Due payment Bill print page get request handler **/
    public function print2()
    {

        //Get bill info
        $bill_id = DB::table('due_transaction')
            ->orderBy('due_pay_date', 'desc')
            ->where('house_id', Auth::user()->house_id)
            ->pluck('bill_id');

        $bill_info = Bill::where('bill_id', $bill_id)->first();


        //Get client info and due info from the selected bill id
        if ($bill_id) {
            $client_id = Bill::where('bill_id', $bill_id)->pluck('client_id');

            $client = Client::where('client_id', $client_id)->first();

            $due = DB::table('due_transaction')
                ->where('bill_id', $bill_id)
                ->orderBy('due_pay_date', 'desc')
                ->first();

            $due_now = DB::table('due')
                ->where('bill_id', $bill_id)
                ->first();

            $due_now = $due_now->due_amount;
        }

        $bill_for = Input::get('bill_for');

        $salesman = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('user_name');

        $salesman_house = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('house_id');

        return View::make('bills.print2', compact('salesman_house', 'salesman', 'bill_id', 'client', 'due', 'due_now', 'bill_for'));
    }


    /** Bill Chalan print page get request handler **/
    public function print3($challanid)
    {
        $challan = DB::table('chalan_parent')->where('id',$challanid)->first();

        $bill_info = Bill::where('bill_id', $challan->parent_bill)->first();

        $client = Client::where('client_id', $bill_info->client_id)->first();

        $products = DB::table('chalan_product')->where('chalan_id',$challanid)->get();

        $boxes = ProductTracker::select('product_code', 'box')->get();

        $sizes = Product::select('product_code', 'unit_product_size')->get();


        $salesman = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('user_name');

        $salesman_house = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('house_id');

        return View::make('bills.print3', compact('salesman_house', 'salesman', 'bill_info', 'products', 'client', 'due', 'boxes', 'sizes','challan'));
    }


    /** Bill Chalan print later from office **/
    public function print3office($id)
    {
        $bill_info = Bill::where('bill_id', $id)->first();

        $client = Client::where('client_id', $bill_info->client_id)->first();

        $products = BillProduct::where('bill_id', $bill_info->bill_id)->get();

        $boxes = ProductTracker::select('product_code', 'box')->get();

        $sizes = Product::select('product_code', 'unit_product_size')->get();

        $bill_for = Input::get('bill_for');

        $salesman = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('user_name');

        $salesman_house = DB::table('user')
            ->where('id', $bill_info['salesman_id'])
            ->pluck('house_id');

        return View::make('bills.print3', compact('salesman_house', 'salesman', 'bill_info', 'products', 'client', 'due', 'bill_for', 'boxes', 'sizes'));
    }

}
