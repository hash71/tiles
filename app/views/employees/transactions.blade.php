<div id="transactions">
    <table class="table table-condensed">
        <thead>
            <tr>
                <th>S/L</th>
                <th>Date</th>
                <th>Amount(BDT)</th>                
            </tr>
        </thead>
        <tbody>
        <?php $x=1;?>
         @foreach($payments as $payment)
            <tr>
                <td>{{$x++}}</td>
                <td>{{date('d/m/y',strtotime($payment->date))}}</td>
                <td class="font-bold text-left">{{$payment->amount}}</td>                
            </tr>

         @endforeach
        </tbody>
    </table>
</div>