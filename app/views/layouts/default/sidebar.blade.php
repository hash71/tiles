<div id="page-sidebar" class="scrollable-content no-print">

    <div id="sidebar-menu">
        <ul>

            @if(Auth::user()->role == 'owner')
                <li>
                    <a href="{{ URL::route('owner.index') }}" title="Dashboard">
                        <i class="glyph-icon icon-dashboard"></i>
                        Owner
                    </a>
                </li>
            @endif
            <li>
                <a href="{{ URL::route('sessions.create') }}" title="Dashboard">
                    <i class="glyph-icon icon-dashboard"></i>
                    Dashboard
                </a>
            </li>

            @if(Auth::user()->role == 'admin')

                <li>
                    <a href="javascript:;" title="Human Resources">
                        <i class="glyph-icon icon-user"></i>
                        Users
                    </a>
                    <ul>

                        <li>
                            <a href="{{URL::route('users.index')}}" title="User List">
                                <i class="glyph-icon icon-chevron-right"></i>
                                User List
                            </a>
                        </li>

                        <li>
                            <a href="{{URL::route('users.create')}}" title="Creat User">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Add New User
                            </a>
                        </li>


                    </ul>
                </li>
            @endif

            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'account' ||Auth::user()->role == 'f_admin')

                <li>
                    <a href="javascript:;" title="Human Resources">
                        <i class="glyph-icon icon-user"></i>
                        Human Resources
                    </a>
                    <ul>

                        <li>
                            <a href="{{URL::route('employees.index')}}" title="Employee List">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Employee List
                            </a>
                        </li>
                        @if(Auth::user()->role != 'account')
                            <li>
                                <a href="{{URL::route('employees.create')}}" title="Create New Employee">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Create New Employee
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{URL::route('clients.index')}}" title="Clients list">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Clients list
                            </a>
                        </li>
                        <li>
                            <a href="{{URL::route('clients.create')}}" title="Create New Client">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Create New Client
                            </a>
                        </li>
                        @if(Auth::user()->role =='account')
                            <li>
                                <a href="{{URL::to('employee_payment')}}" title="Employee List">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Employee Payment
                                </a>
                            </li>
                        @endif
                    </ul>
                </li>
            @endif

            <li>
                @if(Auth::user()->role != 'owner')
                    <a href="javascript:;" title="Sales">
                        <i class="glyph-icon icon-bar-chart-o"></i>
                        Sales
                    </a>
                @endif
                <ul>
                    @if(Auth::user()->role != 'owner')
                        <li>
                            <a href="{{ URL::to('salesReport') }}" title="Sales Report">
                                <i class="glyph-icon icon-chevron-right"></i>
                                @if(Auth::user()->role =='sales')
                                    Bill reprint
                                @else
                                    Sales Report
                                @endif
                            </a>
                        </li>
                        @if(Auth::user()->role != 'sales' && Auth::user()->role != 'f_admin')
                            <li>
                                <a href="{{ URL::to('chalanReport') }}">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Chalan Report
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::to('returnReport') }}">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Product Return Report
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::to('monthlySales') }}" title="Monthly Sales">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Monthly Sales
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::to('productSales') }}" title="Product Sales">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Product Sales
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::to('clientSales') }}" title="Client Sales">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Client Sales
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::to('dueSheet') }}" title="Due Sheet">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Due Sheet
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::to('dueTransaction') }}" title="Sales Report">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Due Collection Sheet
                                </a>
                            </li>
                        @endif
                    @endif

                    @if(Auth::user()->role == 'sales')
                        <li>
                            <a href="{{ URL::to('bills/create1') }}" title="1 no. Bill">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Regular Bill
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('chalan/create') }}" title="1 no. Bill">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Chalan Bill
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('bills/create2') }}" title="2 no. Bill">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Request Bill
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('bills/duePayment') }}" title="Due Payment">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Due Payment
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('bills/returnProduct') }}" title="Product Return">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Product Return
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'stock'|| Auth::user()->role == 'sales' )
                <li>
                    <a href="javascript:;" title="Stock">
                        <i class="glyph-icon icon-clipboard"></i>
                        Stock
                    </a>
                    <ul>
                        <li>
                            <a href="{{URL::route('products.index')}}" title="Product List">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Product List
                            </a>
                        </li>
                        @if(Auth::user()->role != 'sales')
                            @if(Auth::user()->role == 'stock')
                                <li>
                                    <a href="{{URL::route('products.create')}}" title="Add New Product">
                                        <i class="glyph-icon icon-chevron-right"></i>
                                        Add New Product
                                    </a>
                                </li>
                            @endif
                            <li>
                                <a href="{{URL::route('lc.index')}}" title="LC Information">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    LC Information
                                </a>
                            </li>
                            <li>
                                <a href="{{ URL::to('dailyStock') }}" title="Daily Stock Sheet">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Daily Stock Sheet
                                </a>
                            </li>
                            @if(Auth::user()->role == 'stock')
                                <li>
                                    <a href="{{ URL::to('stockPending') }}" title="Clear Pending Stock">
                                        <i class="glyph-icon icon-chevron-right"></i>
                                        Clear Pending Stock
                                    </a>
                                </li>
                                <li>
                                    <a href="{{URL::to('wastage')}}" title="Product List">
                                        <i class="glyph-icon icon-chevron-right"></i>
                                        Wastage
                                    </a>
                                </li>
                            @endif
                        @endif
                    </ul>
                </li>
            @endif

            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'account' || Auth::user()->role == 'stock')
                <li>
                    <a href="{{ URL::to('billEdit') }}" title="Income Input Form">
                        <i class="glyph-icon icon-chevron-right"></i>
                        Bill Release
                    </a>
                </li>
            @endif

            @if(Auth::user()->role == 'admin' || Auth::user()->role == 'account')

                <li>
                    <a href="javascript:;" title="Accounts">
                        <i class="glyph-icon icon-dollar"></i>
                        Accounts
                    </a>
                    <ul>
                        @if(Auth::user()->role == 'account')
                            <li>
                                <a href="{{ URL::to('calculations/incomeInput') }}" title="Income Input Form">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Income Input Form
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ URL::to('calculations/incomeSheet') }}" title="Income Sheet">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Income Sheet
                            </a>
                        </li>
                        @if(Auth::user()->role == 'account')
                            <li>
                                <a href="{{ URL::to('calculations/expenseInput') }}" title="Expense Input Form">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Expense Input Form
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ URL::to('calculations/expenseSheet') }}" title="Expense Sheet">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Expense Sheet
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('calculations/expenditure') }}" title="Income Input Form">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Top Sheet
                            </a>
                        </li>
                        @if(Auth::user()->role == 'account')
                            <li>
                                <a href="{{ URL::to('calculations/createCategory') }}" title="Add New Category">
                                    <i class="glyph-icon icon-chevron-right"></i>
                                    Add New Category
                                </a>
                            </li>
                        @endif
                        <li>
                            <a href="{{ URL::to('calculations/clientBalance') }}" title="Client Balance">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Client Balance Sheet
                            </a>
                        </li>
                        <li>
                            <a href="{{ URL::to('calculations/bankBalance') }}" title="Bank Balance">
                                <i class="glyph-icon icon-chevron-right"></i>
                                Bank Balance Sheet
                            </a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>
        <div class="divider mrg5T mobile-hidden"></div>
        <div class="text-center">
            Developed &amp; maintained by <a href="http://megaminds.co" target="_blank"><strong>Megaminds</strong></a>
        </div>

    </div>
    <!--
    <div id="page-footer" class="font-white">Developed and Maintained by <a href="#" class="font-size-18">diffMakers</a></div>
    -->
</div><!-- #page-sidebar -->
