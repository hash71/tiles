<!DOCTYPE html>

    <html>

    @include('layouts.default.metaHead')

    <body>
        <div id="loading" class="ui-front loader ui-widget-overlay bg-white opacity-100">

            {{ HTML::image('assets/images/loader-dark.gif') }}

        </div>

        <div id="page-wrapper" class="demo-example">

            @include('layouts.default.header')
            @include('layouts.default.sidebar')


            <div id="page-content-wrapper">

            @yield('index')
            @yield('clientList')
            @yield('clientDetails')
            @yield('employeeList')
            @yield('employeeDetails')
            @yield('addClient')
            @yield('addEmployee')
            @yield('createProduct')
            @yield('productList')
            @yield('lcList')
            @yield('createBill')
            @yield('print_bill')
            @yield('salesReport')
            @yield('due_payment')
            @yield('returnProduct')
            @yield('incomeInput')
            @yield('expenseInput')
            @yield('incomeSheet')
            @yield('expenseSheet')
            @yield('stockPending')
            @yield('dailyStock')
            @yield('employee_payment')
            @yield('ownerIndex')
            @yield('createCategory')
            @yield('dueTransaction')
            @yield('expenditure')
            @yield('user')
            @yield('monthlySales')
            @yield('productSales')
            @yield('clientSales')
            @yield('clientBalance')
            @yield('dueSheet')
            @yield('bankBalance')

            </div><!-- #page-content -->

        </div><!-- #page-wrapper -->

    </body>
</html>

