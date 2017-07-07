<?php

class ChalanController extends \BaseController
{

    public static function remainingProductCount()
    {
        $product_remainedSft = DB::table('lc_product')
                                ->groupBy('product_code')
                                ->selectRaw ('product_code,unit_product_size, sum(quantity) as quantity, sum(wastage_before_stock) as wastage_before_stock')
                                ->get();

        //Get the remaining amount of products in stock to check while billing
        $remain_at_stock = array();
        foreach ($product_remainedSft as $product)
        {
          $product->quantity -= $product->wastage_before_stock;
          $product->quantity -= ProductTracker::where('product_code',$product->product_code)->pluck('total_sold_unit');
          $product->quantity -= ProductTracker::where('product_code',$product->product_code)->pluck('wastage_after_stock');
          $unit_size = explode('X',$product->unit_product_size);
          $unit_size = ($unit_size[0] * $unit_size[1])/144.0;
          $product->quantity *= $unit_size;

          $remain_at_stock[$product->product_code] = $product->quantity;

          unset($product->wastage_before_stock);
          unset($product->unit_product_size);
        }

        return $remain_at_stock;
    }

    public function getCreate()
    {
        $bills = Bill::orderBy('bill_id', 'desc')->lists('bill_id');

        $remain_at_stock = self::remainingProductCount();

        return View::make('bills.chalanCreate', compact('bills','remain_at_stock'));
    }

    public function postTransaction()
    {
        /*If anyway user bypasses the client side validation for remaining product, then check it again here*/
        $remain_at_stock = self::remainingProductCount();
        $i = 1;
        while(($prod_code = Input::get('code'.$i)) != null)
        {
            $product_qty = Input::get('productqty'.$i);
            if( $remain_at_stock[$prod_code] < $product_qty) //Not enough product to deliver, stop creating chalan bill
            {
                return Redirect::to('chalan/create')->with('error', 'Some products are not available at stock.');
            }
            $i++;
        }
        /* Check Ends */



        $bill_id = Input::get('name');

        $challan_id = DB::table('chalan_parent')->insertGetId([
                                                                'parent_bill' => $bill_id,
                                                                'ref_chalan_id' => Input::get('ref_chalan_id'),
                                                                'salesman_id' => Auth::user()->id,
                                                                'created_at'  => date("Y-m-d H:i:s")
                                                            ]);

        $i = 1;

        while(Input::get('code'.$i) != null)
        {
            if (Input::get('productqty'.$i) == 0)
            {
                $i++;
                continue;
            }
            DB::table('chalan_product')->insert([
                'chalan_id' => $challan_id,
                'product_id' => Input::get('code'.$i),
                'product_quantity' => Input::get('productsft'.$i),
                'total_piece' => Input::get('productqty'.$i)
            ]);
            $i++;
        }

        return View::make('bills.printChallan',compact('challan_id'));
    }

}
