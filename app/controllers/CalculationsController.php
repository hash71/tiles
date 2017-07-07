<?php

class CalculationsController extends \BaseController
{

	/* Get Client balance sheet View */
	public function clientBalance()
	{
		$data = null;

		$selected_client = -1;

		$clients = Client::all();

		return View::make('calculations.clientBalanceSheet',compact('data','clients','selected_client'));
	}


	/* Date comparison helper function */
	function datecomp($a, $b)
	{
	    $t1 = strtotime($a[0]);
	    $t2 = strtotime($b[0]);
	    return $t1 - $t2;
	}

	/* Get Client balance sheet filtering result*/
	public function clientBalanceFilter()
	{
		$data = null;
		$selected_client = Input::get('client_name');

		if($selected_client != -1)
		{
			//Get the bill payment info
			$results = DB::select(DB::raw("select client_id, client_name, bill_date, bill_id, gross, cash, less from bill_info natural join client where client_id = $selected_client")) ;

			$i=0;
			foreach ($results as $singleresult)
			{
				$data[$i][0] = $singleresult->bill_date;
				$data[$i][1] = 'Bill ('.$singleresult->bill_id. ')';
				$data[$i][2] = sprintf("%.2f",floatval($singleresult->gross));
				$data[$i][3] = sprintf("%.2f",floatval($singleresult->cash));
				$data[$i][4] = sprintf("%.2f",floatval($singleresult->less));
				$i++;
			}

			//Get the due payment info
			$dueresults = DB::select(DB::raw("select client_id, client_name, due_pay_date, due_transaction.bill_id, due_transaction.cash, due_transaction.less from client natural join bill_info join due_transaction on bill_info.bill_id = due_transaction.bill_id where client_id = $selected_client")) ;

			foreach ($dueresults as $singleresult)
			{
				$data[$i][0] = $singleresult->due_pay_date;
				$data[$i][1] = 'Due payment ('.$singleresult->bill_id. ')';
				$data[$i][2] = sprintf("%.2f",floatval(0));
				$data[$i][3] = sprintf("%.2f",floatval($singleresult->cash));
				$data[$i][4] = sprintf("%.2f",floatval($singleresult->less));
				$i++;
			}

			if($data != null)
			{
				usort($data, "CalculationsController::datecomp");
			}
		}

		$clients 		= Client::all(); //grabbing all client name for dropdown

		$client_name 	= Client::where('client_id',$selected_client)->pluck('client_name');

		return View::make('calculations.clientBalanceSheet',compact('data','clients','selected_client','client_name'));
	}


	/* Get Bank Balance Sheet */
	public function bankBalance()
	{
		$data = null;
		$selected_bank = -1;

		$banks = DB::select(DB::raw("select * from bank_list"));

		return View::make('calculations.bankBalanceSheet',compact('data','banks','selected_bank'));
	}

	/* Date comparison helper function for bank balance sheet */
	function bankdatecomp($a, $b)
	{
	    $t1 = strtotime($a[0]);
	    $t2 = strtotime($b[0]);
	    return $t2 - $t1;
	}

	/* Get Bank balance sheet filtering result*/
	public function bankBalanceFilter()
	{
		$data = null;
		$selected_bank = Input::get('bank_name');

		if($selected_bank!=-1)
		{
			//Bank cash withdraw
			$incomeresults = DB::select(DB::raw("select * from income where category='Bank withdraw' AND description like '$selected_bank%' "));

			$i = 0;

			foreach ($incomeresults as $result)
			{
				$data[$i][0] = $result->date;
				$token 		 = strtok($result->description,"()");

				$data[$i][1] = strtok("()");
				$data[$i][2] = $result->amount;
				$data[$i][3] = "-";
				$data[$i][4] = 0;

				$i++;
			}

			//Bank Deposit
			$expenseresults = DB::select(DB::raw("select * from expense where category='Bank deposit' AND  description like '$selected_bank%' ")) ;

			foreach ($expenseresults as $result)
			{
				$data[$i][0] = $result->date;
				$token 		 = strtok($result->description,"()");

				$data[$i][1] = strtok("()");
				$data[$i][2] = "-";
				$data[$i][3] = $result->amount;
				$data[$i][4] = 0;

				$i++;
			}

			if($data != null)
			{
				usort($data, "CalculationsController::bankdatecomp");
			}

			$balance = 0;

			//balance calculations
			for($j=$i-1;$j>=0;$j--)
			{
				if($data[$j][2]=='-') //bank credit
				{
					$balance -= $data[$j][3];
				}
				else
				{
					$balance += $data[$j][2];
				}

				$data[$j][4] = $balance;
			}
		}

		$banks = DB::select(DB::raw("select * from bank_list")); //get all the bank names

		return View::make('calculations.bankBalanceSheet',compact('data','banks','selected_bank'));
	}


	/* Store Expense Input handler */
	public function storeExpense()
	{
		/* Balance forward update */
		$timestamp = date('Y-m-d',strtotime('+1 day',time()));//ajker date er sathe 1 jog

		$total = Input::get('amount');

		$d = DB::table('bf')
				->where('date','>=',date('Y-m-d'))
				->where('date','<',$timestamp)
				->where('house_id',Input::get('house'))
				->first();

		if(sizeof($d)) //Entry for today already exists, update it
		{
			$total =  DB::table('bf')
						->where('id',$d->id)
						->pluck('bf') - $total;

			DB::table('bf')
				->where('id',$d->id)
				->update(['bf'=>$total]);
		}
		else //No entry exists yet, Insert new
		{
			$bf = 0;

			$bf = DB::table('bf')
					->where('date','<',date('Y-m-d'))
					->where('house_id',Auth::user()->house_id)
					->orderBy('date','desc')
					->pluck('bf');

			$total = $bf - $total;

			DB::table('bf')
				->insert(
					[
						'house_id'=>Input::get('house'),
						'bf'=>($total),
						'date'=>date('Y-m-d H:i:s')
					]);
		}


		//Expense input in bank category
		if(Input::get('bank')!=-1)
		{
			$catcon = Input::get('bank')."(".Input::get('description').")";

			DB::table('expense')
				->insert(
					[
						'category'=>Input::get('category'),
						'amount'=>Input::get('amount'),
						'description'=>$catcon,
						'house_id'=>Input::get('house'),
						'date'=>date('Y-m-d H:i:s')
					]);
		}
		else
		{

			DB::table('expense')->insert(['category'=>Input::get('category'),'amount'=>Input::get('amount'),'description'=>Input::get('description'),'house_id'=>Input::get('house'),'date'=>date('Y-m-d H:i:s')]);
		}

		return Redirect::to('calculations/expenseSheet');
	}


	/* Store Income input handler */
	public function storeIncome()
	{
		/* Balance forward update */
		$timestamp = date('Y-m-d',strtotime('+1 day',time()));//ajker date er sathe 1 jog

		$total = Input::get('amount');

		$d = DB::table('bf')
				->where('date','>=',date('Y-m-d'))
				->where('date','<',$timestamp)
				->where('house_id',Input::get('house'))
				->first();

		if(sizeof($d)) //Entry exists for today, update it
		{
			$total =  DB::table('bf')
				->where('id',$d->id)
				->pluck('bf') + $total;

			DB::table('bf')
				->where('id',$d->id)
				->update(['bf'=>$total]);
		}
		else //No entry, insert new
		{
			$bf = 0;

			$bf = DB::table('bf')
					->where('date','<',date('Y-m-d'))
					->where('house_id',Auth::user()->house_id)
					->orderBy('date','desc')
					->pluck('bf');

			$total = $bf + $total;

			DB::table('bf')
				->insert(
					[
						'house_id'=>Input::get('house'),
						'bf'=>($total),
						'date'=>date('Y-m-d H:i:s')
					]);
		}

		//Income input in bank category
		if(Input::get('bank')!=-1)
		{
			$catcon = Input::get('bank')."(".Input::get('description').")";

			DB::table('income')
				->insert(
					[
						'category'=>Input::get('category'),
						'amount'=>Input::get('amount'),
						'description'=>$catcon,
						'house_id'=>Input::get('house'),
						'date'=>date('Y-m-d H:i:s')
					]);
		}
		else
		{
			DB::table('income')
				->insert(
					[
						'category'=>Input::get('category'),
						'amount'=>Input::get('amount'),
						'description'=>Input::get('description'),
						'house_id'=>Input::get('house'),
						'date'=>date('Y-m-d H:i:s')
					]);
		}

		return Redirect::to('calculations/incomeSheet');
	}


	/** Get Expense sheet page **/
	public function expenseSheet()
	{
		$selected['from'] 		= date("Y-m-d");
		$selected['to'] 		= date("Y-m-d");
		$selected['house'] 		= -5;
		$selected['category'] 	= -5;

		$timestamp 	= date('Y-m-d',strtotime('+1 day',strtotime($selected['to'])));

		$expenses 	= DB::table('expense')
						->where('date','>=',date("Y-m-d"))
						->where('date','<',$timestamp)
						->get();

		$houses 	= DB::table('house')->get();

		$employees 	= Employee::select('id','name')->get();

		$categories = DB::table('category')->get();

		$bank 		= DB::table('bank_list')->lists('name');

		return View::make('calculations.expenseSheet',compact('expenses','houses','employees','selected','categories','bank'));
	}


	/** Get Expense sheet filtering result **/
	public function expenseSheetFiltering()
	{
		$selected['from'] 		= Input::get('from');
		$selected['to'] 		= Input::get('to');
		$selected['house'] 		= Input::get('house');
		$selected['category'] 	= Input::get('category');


		$houses 	= DB::table('house')->get();
		$employees 	= Employee::select('id','name')->get();
		$categories = DB::table('category')
						->where('cat_type','expense')
						->get();

		$timestamp = date('Y-m-d',strtotime('+1 day',strtotime($selected['to'])));

		$expenses = DB::table('expense')
						->where('date','>=',$selected['from'])
						->where('date','<',$timestamp)
						->get();// only date selected by default

		if($selected['house']!= -1 && $selected['category']!=-1) //both selected
		{
			$expenses = DB::table('expense')
							->where('date','>=',$selected['from'])
							->where('date','<',$timestamp)
							->where('house_id',Input::get('house'))
							->where('category',Input::get('category'))
							->get();

			$selected['house'] 		= Input::get('house');
			$selected['category'] 	= Input::get('category');

		}
		elseif ($selected['house']!= -1) //only house selected
		{
			$expenses = DB::table('expense')
							->where('date','>=',$selected['from'])
							->where('date','<',$timestamp)
							->where('house_id',Input::get('house'))
							->get();

			$selected['house'] = Input::get('house');

		}
		elseif ($selected['category']!=-1) //only category selected
		{
			$expenses = DB::table('expense')
							->where('date','>=',$selected['from'])
							->where('date','<',$timestamp)
							->where('category',Input::get('category'))
							->get();

			$selected['category'] = Input::get('category');
		}


		if(Input::get('bank')!= -100) //bank selected
		{
			$var = Input::get('bank');

			$i = 0;

			while (isset($expenses[$i]))
			{
				$slice = explode('(', $expenses[$i]->description);

				if($slice[0] != $var)
				{
					unset($expenses[$i]);
				}

				$i++;
			}
		}

		$bank = DB::table('bank_list')->lists('name'); //get all the banks name

		return View::make('calculations.expenseSheet',compact('expenses','houses','employees','selected','categories','bank'));
	}


	/** Get income sheet page **/
	public function incomeSheet()
	{
		$selected['from'] 		= date("Y-m-d");
		$selected['to'] 		= date("Y-m-d");
		$selected['house'] 		= -5;
		$selected['category'] 	= -5;

		$timestamp = date('Y-m-d',strtotime('+1 day',strtotime($selected['to'])));

		$incomes = 	DB::table('income')
						->where('date','>=',date("Y-m-d"))
						->where('date','<',$timestamp)
						->get();

		$houses 	= DB::table('house')->get();
		$employees 	= Employee::select('id','name')->get();
		$categories = DB::table('category')->get();

		$bank = DB::table('bank_list')->lists('name');

		return View::make('calculations.incomeSheet',compact('incomes','houses','employees','selected','categories','bank'));
	}

	/** Get income sheet filtering result **/
	public function incomeSheetFiltering()
	{

		$selected['from'] 		= Input::get('from');
		$selected['to'] 		= Input::get('to');
		$selected['house'] 		= Input::get('house');
		$selected['category'] 	= Input::get('category');

		$timestamp 	= date('Y-m-d',strtotime('+1 day',strtotime($selected['to'])));

		$houses 	= DB::table('house')->get();
		$employees 	= Employee::select('id','name')->get();
		$categories = DB::table('category')->where('cat_type','income')->get();

		$incomes 	= DB::table('income')
						->where('date','>=',$selected['from'])
						->where('date','<',$timestamp)
						->get();// only date selected by default

		if($selected['house']!= -1 && $selected['category']!=-1)//both selected
		{
			$incomes = DB::table('income')
						->where('date','>=',$selected['from'])
						->where('date','<',$timestamp)
						->where('house_id',Input::get('house'))
						->where('category',Input::get('category'))
						->get();

			$selected['house'] 		= Input::get('house');
			$selected['category'] 	= Input::get('category');


		}
		elseif ($selected['house']!= -1) //only house selected
		{
			$incomes = DB::table('income')
						->where('date','>=',$selected['from'])
						->where('date','<',$timestamp)
						->where('house_id',Input::get('house'))
						->get();

			$selected['house'] = Input::get('house');

		}
		elseif ($selected['category']!=-1) //only category selected
		{
			$incomes = DB::table('income')
						->where('date','>=',$selected['from'])
						->where('date','<',$timestamp)
						->where('category',Input::get('category'))
						->get();

			$selected['category'] = Input::get('category');
		}

		if(Input::get('bank')!= -100) //bank selected
		{
			$var = Input::get('bank');

			$i = 0;

			while (isset($incomes[$i]))
			{
				$slice = explode('(', $incomes[$i]->description);

				if($slice[0] != $var)
				{
					unset($incomes[$i]);
				}

				$i++;
			}
		}


		$bank = DB::table('bank_list')->lists('name'); //get all the banks name

		return View::make('calculations.incomeSheet',compact('incomes','houses','employees','selected','categories','bank'));
	}


	/** Get category page for income/expense **/
	public function createCategory()
	{
		$expense_category 	= DB::table('category')
								->where('cat_type','expense')
								->orderBy('name','ASC')
								->lists('name');

		$income_category 	= DB::table('category')
								->where('cat_type','income')
								->orderBy('name','ASC')
								->lists('name');

		$bank_name 			= DB::table('bank_list')
								->orderBy('name','ASC')
								->lists('name');

		return View::make('calculations.createCategory',compact('expense_category','income_category','bank_name'));
	}


	/** Save newly created category **/
	public function storeCategory($type)
	{
		if($type == 'income')
		{
			DB::table('category')
				->insert(
					[
						'name'=>Input::get('cat_name'),
						'cat_type'=>'income'
					]);
		}
		elseif ($type == 'expense')
		{
			DB::table('category')
				->insert(
					[
						'name'=>Input::get('cat_name'),
						'cat_type'=>'expense'
					]);
		}
		elseif ($type == 'bank')
		{
			DB::table('bank_list')
				->insert(
					[
						'name'=>Input::get('cat_name')
					]);
		}

		return Redirect::to('calculations/createCategory');
	}


	/** Get Daily expenditure sheet. Daily income, expense and balance sheet **/
	public function expenditure()
	{
		$houses = DB::table('house')->get();

		$selected['house'] 	= -1;
		$selected['from'] 	= date('Y-m-d');

		$bf 			= 0;
		$sale 			= 0;
		$due_collection = 0;
		$expenses 		= null;
		$incomes 		= null;


		return View::make('calculations.expenditure',compact('houses','selected','sale','due_collection','bf','expenses','incomes'));
	}


	/** Expenditure sheet filtering **/
	public function expenditureFiltering()
	{
		$houses = DB::table('house')->get();

		$selected['house'] 	= Input::get('house');
		$selected['from'] 	= Input::get('from');

		$timestamp = date('Y-m-d',strtotime('+1 day',strtotime($selected['from'])));

		//total sales
		$users 	= DB::table('user')
					->where('house_id',$selected['house'])
					->lists('id');//selected house er under ar users


		$cash = Bill::where('bill_date','>=',$selected['from'])
					->where('bill_date','<',$timestamp)
					->whereIn('salesman_id',$users)
					->sum('cash');

		$sale = $cash;// + $cheque + $credit_card;

		//bf updated
		$bf = 0;

		$bf = DB::table('bf')->where('date','<',$selected['from'])
				->where('house_id',$selected['house'])
				->orderBy('date','desc')
				->pluck('bf');

		// bf updated

		//due
		$cash = DB::table('due_transaction')->where('due_pay_date','>=',$selected['from'])
					->where('due_pay_date','<',$timestamp)
					->where('house_id',$selected['house'])
					->sum('cash');

		$due_collection = $cash ;//+ $cheque + $credit_card;


		$expenses = DB::table('expense')
						->where('date','>=',$selected['from'])
						->where('date','<',$timestamp)
						->where('house_id',$selected['house'])
						->get();

		$incomes = DB::table('income')
						->where('date','>=',$selected['from'])
						->where('date','<',$timestamp)
						->where('house_id',$selected['house'])
						->get();

		return View::make('calculations.expenditure',compact('houses','selected','sale','due_collection','bf','expenses','incomes'));
	}

}
