<table>
    <thead>
        <tr class="">
            <th>#</th>
            <th>Receipt ID</th>
            <th>Date Time</th>
            <th>Table</th>
            <th>Staff</th>
            <th>Total Amount</th>
        </tr>
    </thead>
    <tbody>
    @php
        $countSale = 1;
    @endphp
    @foreach($sales as $sale)
        <tr class="bg-secondary text-light">
            <td>{{$countSale++}}</td>
            <td>{{$sale->id}}</td>
            <td>{{date("m/d/y H:i:s", strtotime($sale->updated_at))}}</td>
            <td>{{$sale->table_name}}</td>
            <td>{{$sale->user_name}}</td>
            <td>{{$sale->total_price}}</td>
        </tr>
        <tr class="">
            <th></th>
            <th>Menu ID</th>
            <th>Menu</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Price</th>
        </tr>
        @foreach($sale->saleDetails as $saleDetail)
            <tr>
                <td></td>
                <td>{{$saleDetail->menu_id}}</td>
                <td>{{$saleDetail->menu_name}}</td>
                <td>{{$saleDetail->quantity}}</td>
                <td>{{$saleDetail->menu_price}}</td>
                <td>{{$saleDetail->menu_price * $saleDetail->quantity}}</td>
        @endforeach
        @endforeach
        <tr>
            <td colspan="5">Total amount from {{$dateStart}} to {{$dateEnd}}.</td>
            <td>{{number_format($totalSale, 2)}}
        </tr>
    </tbody>
</table>
