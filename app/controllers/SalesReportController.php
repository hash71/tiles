<?php

class SalesReportController extends \BaseController
{

    /** Sales report show page **/
    public function index()
    {
        $selected['from'] = date("Y-m-d");
        $selected['to'] = date("Y-m-d");
        $selected['shop'] = -5;
        $selected['client'] = -5;
        $selected['payment'] = -5;
        $selected['product'] = -5;
        $selected['billid'] = "";

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));
        $products = ProductTracker::lists('product_code');


        $bill_info = Bill::where('bill_date', '>=', date("Y-m-d"))
            ->where('bill_date', '<', $timestamp)
            ->get();

        $i = 0;

        $data = null;

        foreach ($bill_info as $bill) {
            $data[$i]['bill_id'] = $bill->bill_id;
            $data[$i]['ref_bill_id'] = $bill->ref_bill_id;
            $data[$i]['tax'] = Bill::where('bill_id', $bill->bill_id)->pluck('tax');
            $data[$i]['bill_date'] = $bill->bill_date;
            $data[$i]['ref_bill_date'] = $bill->ref_bill_date;

            $data[$i]['client_name'] = Client::where('client_id', $bill->client_id)->pluck('client_name');

            $data[$i]['products'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_code');

            $data[$i]['quantity'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_quantity');

            $data[$i]['gross'] = $bill->gross;
            $data[$i]['less'] = $bill->less;
            $data[$i]['net'] = $bill->net;
            $data[$i]['carrying_cost'] = $bill->carrying_cost;

            $data[$i]['payment'] = [
                'cash' => $bill->cash,
                'cheque' => $bill->cheque,
                'credit_card' => $bill->credit_card
            ];

            $data[$i]['due'] = round($data[$i]['net'] + $data[$i]['carrying_cost'] - ($data[$i]['payment']['cash'] + $data[$i]['payment']['cheque']), 2);

            $i++;
        }

        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        $single_product_filter = false;

        return View::make('salesReport.report', compact('data', 'shops', 'clients', 'selected', 'products', 'single_product_filter'));
    }

    //nazmul vai start
    public function chalanIndex()
    {
        $selected['from'] = date("Y-m-d");
        $selected['to'] = date("Y-m-d");
        $selected['shop'] = -5;
        $selected['client'] = -5;
        $selected['payment'] = -5;
        $selected['product'] = -5;
        $selected['billid'] = "";

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));
        $products = ProductTracker::lists('product_code');


        $bill_info = DB::table('chalan_parent')->where('created_at', '>=', date("Y-m-d"))
                    ->where('created_at', '<', $timestamp)
                    ->where('clear', 1)
                    ->get();

        $i = 0;

        $data = null;

        foreach ($bill_info as $bill) {
            $data[$i]['bill_id'] = $bill->id;
            $data[$i]['ref_chalan_id'] = $bill->ref_chalan_id;
            $data[$i]['parent_bill'] = $bill->parent_bill;

            $data[$i]['bill_date'] = $bill->created_at;

            $data[$i]['client_name'] = Client::where('client_id', Bill::where('bill_id', $bill->parent_bill)->pluck('client_id'))->pluck('client_name');

            if (!is_null(Input::get('product'))) {
                $data[$i]['products'] = DB::table('chalan_product')->where('chalan_id', $bill->id)->where('product_id', Input::get('product'))->lists('product_id');
                $data[$i]['quantity'] = DB::table('chalan_product')->where('chalan_id', $bill->id)->where('product_id', Input::get('product'))->lists('total_piece');
            } else {


                $data[$i]['products'] = DB::table('chalan_product')->where('chalan_id', $bill->id)->lists('product_id');
                $data[$i]['quantity'] = DB::table('chalan_product')->where('chalan_id', $bill->id)->lists('total_piece');
            }


//                $data[$i]['gross'] = $bill->gross;
//                $data[$i]['less'] = $bill->less;
//                $data[$i]['net'] = $bill->net;
//                $data[$i]['carrying_cost'] = $bill->carrying_cost;
//
//                $data[$i]['payment'] = [
//                    'cash' => $bill->cash,
//                    'cheque' => $bill->cheque,
//                    'credit_card' => $bill->credit_card
//                ];
//
//                $data[$i]['due'] = round($data[$i]['net'] + $data[$i]['carrying_cost'] - ($data[$i]['payment']['cash'] + $data[$i]['payment']['cheque']), 2);

            $i++;
        }

        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        return View::make('salesReport.chalanReport', compact('data', 'shops', 'clients', 'selected', 'products'));
    }


    public function returnIndex()
    {
        $selected['from'] = date("Y-m-d");
        $selected['to'] = date("Y-m-d");
        $selected['shop'] = -5;
        $selected['client'] = -5;
        $selected['payment'] = -5;
        $selected['product'] = -5;
        $selected['billid'] = "";

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));
        $products = ProductTracker::lists('product_code');


        $bill_info = DB::table('return_transaction')->where('return_date', '>=', date("Y-m-d"))
            ->where('return_date', '<', $timestamp)
            ->get();

        $i = 0;

        $data = null;

        foreach ($bill_info as $bill) {
            $data[$i]['bill_id'] = $bill->bill_id;
            $data[$i]['bill_date'] = $bill->return_date;
            $data[$i]['client_name'] = Client::where('client_id', Bill::where('bill_id', $bill->bill_id)->pluck('client_id'))->pluck('client_name');
            $data[$i]['product_code'] = $bill->product_id;
            $data[$i]['quantity'] = $bill->return_quantity;
            $i++;
        }

        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        return View::make('salesReport.returnReport', compact('data', 'shops', 'clients', 'selected', 'products'));
    }
    //nazmul vai end


    /** Later bill reprint from sales module filtering with bill ID **/
    public function sales_reprint_filter()
    {
        $selected['billid'] = Input::get('billID');

        $products = ProductTracker::lists('product_code');

        $bill_info = Bill::where('bill_id', $selected['billid'])->get();

        $i = 0;

        $data = null;

        foreach ($bill_info as $bill) {
            $data[$i]['bill_id'] = $bill->bill_id;
            $data[$i]['tax'] = Bill::where('bill_id', $bill->bill_id)->pluck('tax');
            $data[$i]['bill_date'] = $bill->bill_date;

            $data[$i]['client_name'] = Client::where('client_id', $bill->client_id)->pluck('client_name');

            $data[$i]['products'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_code');

            $data[$i]['quantity'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_quantity');

            $data[$i]['gross'] = $bill->gross;
            $data[$i]['less'] = $bill->less;
            $data[$i]['net'] = $bill->net;
            $data[$i]['carrying_cost'] = $bill->carrying_cost;

            $data[$i]['payment'] = [
                'cash' => $bill->cash,
                'cheque' => $bill->cheque,
                'credit_card' => $bill->credit_card
            ];

            $data[$i]['due'] = round($data[$i]['net'] + $data[$i]['carrying_cost'] - ($data[$i]['payment']['cash'] + $data[$i]['payment']['cheque'] + $data[$i]['payment']['credit_card']), 2);

            $i++;
        }

        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        return View::make('salesReport.report', compact('data', 'shops', 'clients', 'selected', 'products'));
    }


    /** Monthly sales report page. Total sales of a day will show in a single row **/
    public function monthlySales()
    {
        $month = date('m');
        $year = date('Y');
        $selected_shop = -1;

        $results = DB::select(DB::raw("select bill_id, net,bill_date, result.total_product from bill_info natural join (select bill_id, SUM(product_quantity) as total_product from bill_product group by bill_id) as result where bill_date like '%$year-$month%'"));

        $data = null;

        for ($i = 0; $i <= 31; $i++)
            $data[$i] = null;

        foreach ($results as $singleresult) {
            $onlydate = intval(substr($singleresult->bill_date, 8, 2));

            if ($data[$onlydate] == NULL) {
                $data[$onlydate][0] = $onlydate;
                $data[$onlydate][1] = 0.0;    //net sales (TK)
                $data[$onlydate][2] = 0.0;    //total amound (sft)
            }

            $data[$onlydate][1] += floatval($singleresult->net);
            $data[$onlydate][2] += floatval($singleresult->total_product);
        }

        $shops = DB::table('house')->where('house_id', '!=', 1)->get();

        return View::make('salesReport.monthlySales', compact('month', 'year', 'data', 'shops', 'selected_shop'));
    }


    /** Monthly sales filtering. By Date, Year, Shop **/
    public function monthlySalesFiltering()
    {
        $month = Input::get('month');
        $year = Input::get('year');
        $selected_shop = Input::get('shop_name');

        if ($selected_shop == -1) //empty 'true' query for all shop
        {
            $shop_query = 1;
        } else //selected shop query for a single shop
        {
            $shop_query = 'user.house_id = ' . $selected_shop;
        }


        //joining bill_info table with bill_product table for total sum of product for a single day
        //joining user table for finding the house_id of the bill created. then use it for shop filtering.
        $results = DB::select(DB::raw("select bill_id, net,bill_date, result.total_product, user.house_id from bill_info natural join (select bill_id, SUM(product_quantity) as total_product from bill_product group by bill_id) as result inner join user on user.id = bill_info.salesman_id where bill_date like '%$year-$month%' and $shop_query "));

        $data = null;

        for ($i = 0; $i <= 31; $i++)
            $data[$i] = null;

        foreach ($results as $singleresult) {
            $onlydate = intval(substr($singleresult->bill_date, 8, 2));

            if ($data[$onlydate] == NULL) {
                $data[$onlydate][0] = $onlydate;
                $data[$onlydate][1] = 0.0;    //net sales (TK)
                $data[$onlydate][2] = 0.0;    //total amound (sft)
            }

            $data[$onlydate][1] += floatval($singleresult->net);
            $data[$onlydate][2] += floatval($singleresult->total_product);
        }

        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        return View::make('salesReport.monthlySales', compact('month', 'year', 'data', 'shops', 'selected_shop'));
    }

    /** Total product sales index page with current date data **/
    public function productSales()
    {
        $selected['from'] = date("Y-m-d");
        $start = $selected['from'];
        $selected['to'] = date("Y-m-d");
        $grp = Input::get('grp');
        $size_selected = -1;

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));

        $results = DB::select(DB::raw("select table1.product_code, total_product, unit_product_size from (select product_code,SUM(product_quantity) as total_product from bill_product where bill_id in (select bill_id from bill_info where bill_date >= '$start' AND bill_date < '$timestamp') group by product_code) as table1 join (select product_code, unit_product_size from lc_product group by product_code) as table2 on table1.product_code=table2.product_code order by table1.product_code ASC"));

        $data = null;
        $i = 0;

        foreach ($results as $singleresult) {
            $data[$i][0] = $singleresult->product_code;
            $data[$i][1] = sprintf("%.2f", floatval($singleresult->total_product));
            $data[$i][2] = $singleresult->unit_product_size;
            $i++;
        }

        $units_size = Product::select('unit_product_size')
            ->distinct()
            ->orderBy('unit_product_size')
            ->get();

        return View::make('salesReport.productSales', compact('selected', 'data', 'units_size', 'grp', 'size_selected'));
    }


    /** Total product sales filtering **/
    public function productSalesFiltering()
    {
        $selected['from'] = Input::get('from');
        $start = $selected['from'];
        $selected['to'] = Input::get('to');
        $grp = Input::get('grp');
        $size_selected = Input::get('unit_size');

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));


        if ($size_selected != -1) {
            $results = DB::select(DB::raw("select table1.product_code, total_product, unit_product_size from (select product_code,SUM(product_quantity) as total_product from bill_product where bill_id in (select bill_id from bill_info where bill_date >= '$start' AND bill_date < '$timestamp') group by product_code) as table1 join (select product_code, unit_product_size from lc_product group by product_code) as table2 on table1.product_code=table2.product_code where unit_product_size = '$size_selected'  order by table1.product_code ASC"));
        } else {
            $results = DB::select(DB::raw("select table1.product_code, total_product, unit_product_size from (select product_code,SUM(product_quantity) as total_product from bill_product where bill_id in (select bill_id from bill_info where bill_date >= '$start' AND bill_date < '$timestamp') group by product_code) as table1 join (select product_code, unit_product_size from lc_product group by product_code) as table2 on table1.product_code=table2.product_code order by table1.product_code ASC"));
        }


        $data = null;
        $i = -1;
        $prev = "";

        foreach ($results as $singleresult) {
            $cur_product = $singleresult->product_code;

            if (strcasecmp($grp, "on") == 0) //case insensitive string comparison for 'group' selection
            {
                $cur_elem = strtok($cur_product, "-");

                if ($prev == $cur_elem) {
                    $data[$i][1] += floatval($singleresult->total_product);
                } else {
                    $i++;
                    $data[$i][0] = $cur_elem;
                    $data[$i][1] = floatval($singleresult->total_product);
                    $data[$i][2] = $singleresult->unit_product_size;
                }

                $prev = $cur_elem;
            } else {
                $i++;
                $data[$i][0] = $cur_product;
                $data[$i][1] = floatval($singleresult->total_product);
                $data[$i][2] = $singleresult->unit_product_size;

            }
        }

        $units_size = Product::select('unit_product_size')
            ->distinct()
            ->orderBy('unit_product_size')
            ->get();

        return View::make('salesReport.productSales', compact('selected', 'data', 'units_size', 'grp', 'size_selected'));
    }


    /** Total sales by client index page with current date data **/
    public function clientSales()
    {
        $selected['from'] = date("Y-m-d");
        $start = $selected['from'];
        $selected['to'] = date("Y-m-d");

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));

        $results = DB::select(DB::raw("select bill_info.bill_id, client.client_name, SUM(net) as total_bill, SUM(product_table.quantity) as total_product from bill_info join (select bill_id, SUM(product_quantity) as quantity from bill_product where bill_id in (select bill_id from bill_info where bill_date >= '$start' AND bill_date < '$timestamp' ) group by bill_id ) as product_table on bill_info.bill_id = product_table.bill_id join client on client.client_id = bill_info.client_id group by bill_info.client_id"));

        $data = null;
        $i = 0;
        foreach ($results as $singleresult) {
            $data[$i][0] = $singleresult->client_name;
            $data[$i][1] = sprintf("%.2f", floatval($singleresult->total_product));
            $data[$i][2] = sprintf("%.2f", floatval($singleresult->total_bill));
            $i++;
        }

        return View::make('salesReport.clientSales', compact('selected', 'data'));
    }


    /** Total sales by client filtering **/
    public function clientSalesFiltering()
    {
        $selected['from'] = Input::get('from');
        $start = $selected['from'];
        $selected['to'] = Input::get('to');

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));

        $results = DB::select(DB::raw("select bill_info.bill_id, client.client_name, SUM(net) as total_bill, SUM(product_table.quantity) as total_product from bill_info join (select bill_id, SUM(product_quantity) as quantity from bill_product where bill_id in (select bill_id from bill_info where bill_date >= '$start' AND bill_date < '$timestamp' ) group by bill_id ) as product_table on bill_info.bill_id = product_table.bill_id join client on client.client_id = bill_info.client_id group by bill_info.client_id"));

        $data = null;
        $i = 0;
        foreach ($results as $singleresult) {
            $data[$i][0] = $singleresult->client_name;
            $data[$i][1] = sprintf("%.2f", floatval($singleresult->total_product));
            $data[$i][2] = sprintf("%.2f", floatval($singleresult->total_bill));
            $i++;
        }

        return View::make('salesReport.clientSales', compact('selected', 'data'));
    }


    /** Due Sheet for only Due amount data **/
    public function dueSheet()
    {
        $selected['from'] = date("Y-m-d");
        $selected['to'] = date("Y-m-d");

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));

        $selected['shop'] = -5;
        $selected['client'] = -5;
        $selected['payment'] = -5;
        $selected['product'] = -5;
        $selected['billid'] = "";

        $products = ProductTracker::lists('product_code');

        $bills = Bill::where('bill_date', '>=', $selected['from'])
            ->where('bill_date', '<', $timestamp)
            ->lists('bill_id');

        $bill_due = DB::table('due')
            ->where('due_amount', '>', 0)
            ->lists('bill_id');

        $bills = array_intersect($bills, $bill_due);

        $data = null;

        if (sizeof($bills)) {
            $bill_info = Bill::whereIn('bill_id', $bills)->get();

            $i = 0;

            foreach ($bill_info as $bill) {
                $data[$i]['bill_id'] = $bill->bill_id;
                $data[$i]['tax'] = Bill::where('bill_id', $bill->bill_id)->pluck('tax');
                $data[$i]['bill_date'] = $bill->bill_date;

                $data[$i]['client_name'] = Client::where('client_id', $bill->client_id)->pluck('client_name');

                $data[$i]['products'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_code');

                $data[$i]['quantity'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_quantity');

                $data[$i]['gross'] = $bill->gross;
                $data[$i]['total_less'] = $bill->less;

                $due_payment_less = DB::select(DB::raw("select SUM(less) as due_less from due_transaction where bill_id = $bill->bill_id group by bill_id"));

                if (sizeof($due_payment_less) > 0) {
                    $data[$i]['total_less'] += $due_payment_less[0]->due_less;
                }

                $data[$i]['net'] = $bill->net;
                $data[$i]['carrying_cost'] = $bill->carrying_cost;

                $due_paid = DB::select(DB::raw("select SUM(cash) as total_paid from due_transaction where bill_id = $bill->bill_id group by bill_id"));

                if (sizeof($due_paid) > 0) {
                    $bill->cash += $due_paid[0]->total_paid;
                }

                $data[$i]['payment'] = [
                    'cash' => $bill->cash,
                    'cheque' => $bill->cheque,
                    'credit_card' => $bill->credit_card
                ];

                $data[$i]['due'] = DB::table('due')
                    ->where('bill_id', $bill->bill_id)
                    ->pluck('due_amount');

                sort($data[$i]['products']);

                $i++;
            }
        }

        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        return View::make('salesReport.dueSheet', compact('data', 'shops', 'clients', 'selected', 'products'));;
    }


    /** Due Sheet filtering page **/
    public function dueSheetFiltering()
    {
        $selected['from'] = Input::get('from');
        $selected['to'] = Input::get('to');
        $selected['shop'] = -5;
        $selected['client'] = -5;
        $selected['payment'] = -5;
        $selected['product'] = -5;


        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));

        $products = ProductTracker::lists('product_code');

        $bill_date = Bill::where('bill_date', '>=', Input::get('from'))
            ->where('bill_date', '<', $timestamp)
            ->lists('bill_id');

        $bills = $bill_date;

        if (Input::get('shop_name') != -1) {
            $user = DB::table('user')
                ->where('house_id', Input::get('shop_name'))
                ->lists('id');

            $bill_shop = Bill::whereIn('salesman_id', $user)->lists('bill_id');//shop er under er sob bill

            $bills = array_intersect($bills, $bill_shop);

            $selected['shop'] = Input::get('shop_name');
        }

        if (Input::get('client_name') != -1) {
            $bill_client = Bill::where('client_id', Input::get('client_name'))->lists('bill_id');

            $bills = array_intersect($bills, $bill_client);

            $selected['client'] = Input::get('client_name');

        }

        if (Input::get('product') != -1) {
            $bill_product = BillProduct::where('product_code', Input::get('product'))->lists('bill_id');

            $bills = array_intersect($bills, $bill_product);

            $selected['product'] = Input::get('product');

        }


        $bill_due = DB::table('due')
            ->where('due_amount', '>', 0)
            ->lists('bill_id');

        $bills = array_intersect($bills, $bill_due);

        $data = null;

        if (sizeof($bills)) {
            $bill_info = Bill::whereIn('bill_id', $bills)->get();

            $i = 0;

            foreach ($bill_info as $bill) {
                $data[$i]['bill_id'] = $bill->bill_id;
                $data[$i]['tax'] = Bill::where('bill_id', $bill->bill_id)->pluck('tax');
                $data[$i]['bill_date'] = $bill->bill_date;

                $data[$i]['client_name'] = Client::where('client_id', $bill->client_id)->pluck('client_name');

                $data[$i]['products'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_code');

                $data[$i]['quantity'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_quantity');

                $data[$i]['gross'] = $bill->gross;
                $data[$i]['total_less'] = $bill->less;

                $due_payment_less = DB::select(DB::raw("select SUM(less) as due_less from due_transaction where bill_id = $bill->bill_id group by bill_id"));

                if (sizeof($due_payment_less) > 0) {
                    $data[$i]['total_less'] += $due_payment_less[0]->due_less;
                }

                $data[$i]['net'] = $bill->net;
                $data[$i]['carrying_cost'] = $bill->carrying_cost;

                $due_paid = DB::select(DB::raw("select SUM(cash) as total_paid from due_transaction where bill_id = $bill->bill_id group by bill_id"));

                if (sizeof($due_paid) > 0) {
                    $bill->cash += $due_paid[0]->total_paid;
                }

                $data[$i]['payment'] = [
                    'cash' => $bill->cash,
                    'cheque' => $bill->cheque,
                    'credit_card' => $bill->credit_card
                ];

                $data[$i]['due'] = DB::table('due')
                    ->where('bill_id', $bill->bill_id)
                    ->pluck('due_amount');

                sort($data[$i]['products']);

                $i++;
            }
        }


        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        return View::make('salesReport.dueSheet', compact('data', 'shops', 'clients', 'selected', 'products'));

    }

    /** Bill Delete **/
    public function destroy()
    {
        $bill_id = Input::get('bill_id');

        $bill = Bill::where('bill_id', $bill_id)->first();

        $bill_date = date('Y-m-d', strtotime($bill->bill_date));

        $house_id = DB::table('user')
            ->where('id', $bill->salesman_id)
            ->pluck('house_id');

        $results = DB::select(DB::raw("select * from bf where date(date)='$bill_date' and house_id='$house_id'"));

        $minus = DB::table('bf')->where('id', $results[0]->id)->pluck('bf') - $bill->cash;

        DB::table('bf')
            ->where('id', $results[0]->id)
            ->update(['bf' => $minus]);

        //nazmul vai start

        //chalan bill clear

        $chalans = DB::table('chalan_parent')->where('parent_bill', $bill_id)->where('clear',1)->lists('id');

        //product tracker update for cleared chalan bills

        $products_amount = DB::table('chalan_product')
            ->groupBy('product_id')
            ->whereIn('chalan_id', $chalans)
            ->selectRaw('sum(total_piece) as total_piece, product_id ')
            ->get();


        foreach ($products_amount as $amount) {

            $sofar = ProductTracker::where('product_code', $amount->product_id)->pluck('total_sold_unit');

            $sofar -= $amount->total_piece;

            ProductTracker::where('product_code', $amount->product_id)->update(['total_sold_unit' => $sofar]);

//             //owner table
//
//             $sofar = DB::table('owner_calculation')->where('product_code', $key)->pluck('total_sale');
//
//             $unit_price = DB::table('bill_product')
//                 ->where('bill_id', $bill_id)->pluck('unit_sale_price');
// //            dd($unit_price);
//             $sofar -= $value * $unit_price;
//
//             DB::table('owner_calculation')->where('product_code', $key)->update(['total_sale' => $sofar]);

        }

        $chalans = DB::table('chalan_parent')->where('parent_bill', $bill_id)->lists('id');

        DB::table('chalan_parent')->where('parent_bill', $bill_id)->delete();
        DB::table('chalan_product')->whereIn('chalan_id', $chalans)->delete();

        //nazmul vai end


        Bill::where('bill_id', $bill_id)->delete();

        BillProduct::where('bill_id', $bill_id)->delete();

        DB::table('due')
            ->where('bill_id', $bill_id)
            ->delete();

        return Redirect::to('salesReport');
    }


    //nazmul vai start
    public function chalanDestroy()
    {

        $bill_id = Input::get('bill_id');

        $chalan = DB::table('chalan_parent')->find($bill_id);

        //product tracker update for cleared chalan bills
//        dd("bla");
        $products_amount = DB::table('chalan_product')
            ->where('chalan_id', $bill_id)
            ->lists('product_quantity', 'product_id');


        foreach ($products_amount as $key => $value) {

            $sofar = ProductTracker::where('product_code', $key)->pluck('total_sold_unit');

            $sofar -= $value;

            ProductTracker::where('product_code', $key)->update(['total_sold_unit' => $sofar]);

            //owner table

            $sofar = DB::table('owner_calculation')->where('product_code', $key)->pluck('total_sale');

            $unit_price = DB::table('bill_product')
                ->where('bill_id', $chalan->parent_bill)->pluck('unit_sale_price');

            $sofar -= $value * $unit_price;

            DB::table('owner_calculation')->where('product_code', $key)->update(['total_sale' => $sofar]);

        }

        DB::table('chalan_parent')->where('id', $bill_id)->delete();
        DB::table('chalan_product')->where('chalan_id', $bill_id)->delete();

        //nazmul vai end

        return Redirect::to('chalanReport');
    }
    //nazmul vai end


    /** Sales report filtering result **/
    public function sales_filtering()
    {
        $selected['from'] = Input::get('from');
        $selected['to'] = Input::get('to');
        $selected['shop'] = -5;
        $selected['client'] = -5;
        $selected['payment'] = -5;
        $selected['product'] = -5;

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));

        $products = ProductTracker::lists('product_code');

        $bill_date = Bill::where('bill_date', '>=', Input::get('from'))
            ->where('bill_date', '<', $timestamp)
            ->lists('bill_id');

        $bills = $bill_date;

        if (Input::get('shop_name') != -1) {
            $user = DB::table('user')
                ->where('house_id', Input::get('shop_name'))
                ->lists('id');

            $bill_shop = Bill::whereIn('salesman_id', $user)->lists('bill_id');//shop er under er sob bill

            $bills = array_intersect($bills, $bill_shop);

            $selected['shop'] = Input::get('shop_name');
        }

        if (Input::get('client_name') != -1) {
            $bill_client = Bill::where('client_id', Input::get('client_name'))->lists('bill_id');

            $bills = array_intersect($bills, $bill_client);

            $selected['client'] = Input::get('client_name');
        }

        if (Input::get('product') != -1) {
            $bill_product = BillProduct::where('product_code', Input::get('product'))->lists('bill_id');

            $bills = array_intersect($bills, $bill_product);

            $selected['product'] = Input::get('product');
        }

        $bill_due = DB::table('due')
            ->where('due_amount', '>', 0)
            ->lists('bill_id');

        if (sizeof($bill_due)) {
            $bill_paid = Bill::whereNotIn('bill_id', $bill_due)->lists('bill_id');
        } else {
            $bill_paid = [];
        }


        if (Input::get('payment') == 'Due') {
            $bills = array_intersect($bills, $bill_due);

            $selected['payment'] = 'Due';

        } elseif (Input::get('payment') == 'Paid') {
            $bills = array_intersect($bills, $bill_paid);

            $selected['payment'] = 'Paid';

        }

        $data = null;

        if (sizeof($bills)) {
            $bill_info = Bill::whereIn('bill_id', $bills)->get();

            $i = 0;

            foreach ($bill_info as $bill) {
                $data[$i]['bill_id'] = $bill->bill_id;
                $data[$i]['ref_bill_id'] = $bill->ref_bill_id;
                $data[$i]['tax'] = Bill::where('bill_id', $bill->bill_id)->pluck('tax');

                $data[$i]['bill_date'] = $bill->bill_date;
                $data[$i]['ref_bill_date'] = $bill->ref_bill_date;

                $data[$i]['client_name'] = Client::where('client_id', $bill->client_id)->pluck('client_name');

                if (Input::get('product') != -1) {
                    $data[$i]['products'] = BillProduct::where('bill_id', $bill->bill_id)->where('product_code', Input::get('product'))->lists('product_code');
                    $data[$i]['quantity'] = BillProduct::where('bill_id', $bill->bill_id)->where('product_code', Input::get('product'))->lists('product_quantity');
                } else {
                    $data[$i]['products'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_code');
                    $data[$i]['quantity'] = BillProduct::where('bill_id', $bill->bill_id)->lists('product_quantity');
                }


                $data[$i]['gross'] = $bill->gross;
                $data[$i]['less'] = $bill->less;
                $data[$i]['net'] = $bill->net;
                $data[$i]['carrying_cost'] = $bill->carrying_cost;

                $data[$i]['payment'] = [
                    'cash' => $bill->cash,
                    'cheque' => $bill->cheque,
                    'credit_card' => $bill->credit_card
                ];

                $data[$i]['due'] = round($data[$i]['net'] + $data[$i]['carrying_cost'] - ($data[$i]['payment']['cash'] + $data[$i]['payment']['cheque']), 2);

                $i++;
            }
        }


        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        $single_product_filter = ($selected['product'] < 0) ? false : true;

        return View::make('salesReport.report', compact('data', 'shops', 'clients', 'selected', 'products', 'single_product_filter'));
    }

//    nazmul vai start
//chalan report filtering
    public function chalan_filtering()
    {
        $selected['from'] = Input::get('from');
        $selected['to'] = Input::get('to');
        $selected['shop'] = -5;
        $selected['client'] = -5;
        $selected['payment'] = -5;
        $selected['product'] = -5;

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));

        $products = ProductTracker::lists('product_code');

        $bill_date = DB::table('chalan_parent')->where('created_at', '>=', Input::get('from'))
            ->where('created_at', '<', $timestamp)
            ->where('clear', 1)
            ->lists('id');

        $parent_bill = DB::table('chalan_parent')->where('created_at', '>=', Input::get('from'))
            ->where('created_at', '<', $timestamp)
            ->where('clear', 1)
            ->lists('parent_bill');

        $bills = $bill_date;

        if (Input::get('shop_name') != -1) {
            $user = DB::table('user')
                ->where('house_id', Input::get('shop_name'))
                ->lists('id');

            $bill_shop = DB::table('chalan_parent')->whereIn('salesman_id', $user)->lists('id');//shop er under er sob bill

            $bills = array_intersect($bills, $bill_shop);

            $selected['shop'] = Input::get('shop_name');
        }

        if (Input::get('client_name') != -1) {


            $bill_client = Bill::where('client_id', Input::get('client_name'))->whereIn('bill_id', $parent_bill)->lists('bill_id');

            $bill_client = DB::table('chalan_parent')->whereIn('parent_bill', $bill_client)->lists('id');

            $bills = array_intersect($bills, $bill_client);


            $selected['client'] = Input::get('client_name');

        }

        if (Input::get('product') != -1) {
            $bill_product = DB::table('chalan_product')->where('product_id', Input::get('product'))->lists('chalan_id');

            $bills = array_intersect($bills, $bill_product);

            $selected['product'] = Input::get('product');
        }

//        $bill_due = DB::table('due')
//            ->where('due_amount', '>', 0)
//            ->lists('bill_id');

//        if (sizeof($bill_due)) {
//            $bill_paid = Bill::whereNotIn('bill_id', $bill_due)->lists('bill_id');
//        } else {
//            $bill_paid = [];
//        }


//        if (Input::get('payment') == 'Due') {
//            $bills = array_intersect($bills, $bill_due);
//
//            $selected['payment'] = 'Due';
//
//        } elseif (Input::get('payment') == 'Paid') {
//            $bills = array_intersect($bills, $bill_paid);
//
//            $selected['payment'] = 'Paid';
//
//        }

        $data = null;

        if (sizeof($bills)) {
            $bill_info = DB::table('chalan_parent')->whereIn('id', $bills)->get();

            $i = 0;

            foreach ($bill_info as $bill) {
                $data[$i]['bill_id'] = $bill->id;
                $data[$i]['parent_bill'] = $bill->parent_bill;
                $data[$i]['ref_chalan_id'] = $bill->ref_chalan_id;

                $data[$i]['bill_date'] = $bill->created_at;

                $data[$i]['client_name'] = Client::where('client_id', Bill::where('bill_id', $bill->parent_bill)->pluck('client_id'))->pluck('client_name');

                if (Input::get('product') != -1) {
                    $data[$i]['products'] = DB::table('chalan_product')->where('chalan_id', $bill->id)->where('product_id', Input::get('product'))->lists('product_id');
                    $data[$i]['quantity'] = DB::table('chalan_product')->where('chalan_id', $bill->id)->where('product_id', Input::get('product'))->lists('total_piece');
                } else {
                    $data[$i]['products'] = DB::table('chalan_product')->where('chalan_id', $bill->id)->lists('product_id');
                    $data[$i]['quantity'] = DB::table('chalan_product')->where('chalan_id', $bill->id)->lists('total_piece');
                }


//                $data[$i]['gross'] = $bill->gross;
//                $data[$i]['less'] = $bill->less;
//                $data[$i]['net'] = $bill->net;
//                $data[$i]['carrying_cost'] = $bill->carrying_cost;
//
//                $data[$i]['payment'] = [
//                    'cash' => $bill->cash,
//                    'cheque' => $bill->cheque,
//                    'credit_card' => $bill->credit_card
//                ];
//
//                $data[$i]['due'] = round($data[$i]['net'] + $data[$i]['carrying_cost'] - ($data[$i]['payment']['cash'] + $data[$i]['payment']['cheque']), 2);

                $i++;
            }
        }


        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        return View::make('salesReport.chalanReport', compact('data', 'shops', 'clients', 'selected', 'products'));
    }

    public function return_filtering()
    {
        $selected['from'] = Input::get('from');
        $selected['to'] = Input::get('to');
        $selected['shop'] = -5;
        $selected['client'] = -5;
        $selected['payment'] = -5;
        $selected['product'] = -5;

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));

        $products = ProductTracker::lists('product_code');

        $bills = DB::table('return_transaction')->where('return_date', '>=', Input::get('from'))
            ->where('return_date', '<', $timestamp)
            ->lists('bill_id');

        if (Input::get('shop_name') != -1) {
            $user = DB::table('user')
                ->where('house_id', Input::get('shop_name'))
                ->lists('id');

            $bill_shop = DB::table('return_transaction')->whereIn('salesman_id', $user)->lists('bill_id');//shop er under er sob bill

            $bills = array_intersect($bills, $bill_shop);

            $selected['shop'] = Input::get('shop_name');
        }

        if (Input::get('client_name') != -1) {
            $bill_client = Bill::where('client_id', Input::get('client_name'))->lists('bill_id');

            $bills = array_intersect($bills, $bill_client);
            $selected['client'] = Input::get('client_name');
        }

        if (Input::get('product') != -1) {
            $bill_product = DB::table('return_transaction')->where('product_id', Input::get('product'))->lists('bill_id');
            $bills = array_intersect($bills, $bill_product);
            $selected['product'] = Input::get('product');
        }

        $data = null;

        if (sizeof($bills)) {
            $bill_info = DB::table('return_transaction')->whereIn('bill_id', $bills)->orderBy('return_date','desc')->get();
            $i = 0;
            foreach ($bill_info as $bill) {
                $data[$i]['bill_id'] = $bill->bill_id;
                $data[$i]['bill_date'] = $bill->return_date;
                $data[$i]['client_name'] = Client::where('client_id', Bill::where('bill_id', $bill->bill_id)->pluck('client_id'))->pluck('client_name');
                $data[$i]['product_code'] = $bill->product_id;
                $data[$i]['quantity'] = $bill->return_quantity;
                $i++;
            }
        }


        $shops = DB::table('house')
            ->where('house_id', '!=', 1)
            ->get();

        $clients = Client::all();

        return View::make('salesReport.returnReport', compact('data', 'shops', 'clients', 'selected', 'products'));
    }
// nazmul vai end


    /** Due Transaction list show page **/
    public function dueTransaction()
    {
        $selected['shop_id'] = -1;
        $selected['from'] = date('Y-m-d');
        $selected['to'] = date('Y-m-d');

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));
        $shops = DB::table('house')->get();

        $transactions = DB::table('due_transaction')
            ->where('due_pay_date', '>=', $selected['from'])
            ->where('due_pay_date', '<', $timestamp)
            ->get();

        $transactions_id = DB::table('due_transaction')
            ->where('due_pay_date', '>=', $selected['from'])
            ->where('due_pay_date', '<', $timestamp)
            ->lists('bill_id');

        if (sizeof($transactions_id)) {
            $clients = Bill::whereIn('bill_id', $transactions_id)
                ->select('bill_id', 'client_id')
                ->get();

            $client_name = Client::select('client_id', 'client_name')->get();

            $due_amounts = DB::table('due')
                ->whereIn('bill_id', $transactions_id)
                ->get();
        }

        return View::make('salesReport.dueTransaction', compact('shops', 'selected', 'transactions', 'clients', 'client_name', 'due_amounts'));
    }

    /** Due transaction list filtering view **/
    public function dueTransactionFiltering()
    {
        $selected['shop_id'] = Input::get('shop_name');
        $selected['from'] = Input::get('from');
        $selected['to'] = Input::get('to');

        $timestamp = date('Y-m-d', strtotime('+1 day', strtotime($selected['to'])));
        $shops = DB::table('house')->get();

        if ($selected['shop_id'] == -1) {
            $transactions = DB::table('due_transaction')
                ->where('due_pay_date', '>=', $selected['from'])
                ->where('due_pay_date', '<', $timestamp)
                ->get();

            $transactions_id = DB::table('due_transaction')
                ->where('due_pay_date', '>=', $selected['from'])
                ->where('due_pay_date', '<', $timestamp)
                ->lists('bill_id');


        } else {
            $transactions = DB::table('due_transaction')
                ->where('due_pay_date', '>=', $selected['from'])
                ->where('due_pay_date', '<', $timestamp)
                ->where('house_id', $selected['shop_id'])
                ->get();

            $transactions_id = DB::table('due_transaction')
                ->where('due_pay_date', '>=', $selected['from'])
                ->where('due_pay_date', '<', $timestamp)
                ->where('house_id', $selected['shop_id'])
                ->lists('bill_id');
        }

        if (sizeof($transactions_id)) {
            $clients = Bill::whereIn('bill_id', $transactions_id)
                ->select('bill_id', 'client_id')
                ->get();

            $client_name = Client::select('client_id', 'client_name')->get();

            $due_amounts = DB::table('due')
                ->whereIn('bill_id', $transactions_id)
                ->get();
        }

        return View::make('salesReport.dueTransaction', compact('shops', 'selected', 'transactions', 'clients', 'client_name', 'due_amounts'));
    }


    public function tax($id)
    {
        Bill::where('bill_id', $id)->update(['tax' => 1]);

        return Redirect::to('salesReport');
    }

    public function untax($id)
    {
        Bill::where('bill_id', $id)->update(['tax' => 0]);

        return Redirect::to('salesReport');
    }

}
