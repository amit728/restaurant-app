@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row" id="table-details">
    </div>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <button class="btn btn-primary btn-block" id="btn-show-tables">
                View All Tables
            </button>
            <div id="selected-table"></div>
            <div id="order-detail"></div>
        </div>
        <div class="col-md-7">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    @foreach($categories as $category)
                        <a class="nav-item nav-link" data-id="{{ $category->id }}"
                            data-toggle="tab">
                            {{ $category->name}}
                        </a>
                    @endforeach
                </div>
            </nav>
            <div id="list-menu" class="row mt-2"></div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Payment</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <h4 class="totalAmount"></h4>
        <h4 class="changeAmount"></h4>
        <div class="input-group mb-2 mt-3">
            <div class="input-group-prepend">
                <div class="input-group-text">$</div>
            </div>
            <input type="number" class="form-control" id="received-amount">
        </div>
        <div class="form-group">
            <label for="payment">Payment Type</label>
            <select for="payment" id="payment-type" class="form-control">
                <option value="cash">Cash</option>
                <option value="credit card">Credit Card</option>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary btn-save-payment">Save Payment</button>
      </div>
    </div>
  </div>
</div>

<script>
    $(document).ready(function(){

        // make table-details hidden by default
        $("#table-details").hide();

        // show all table when click on the button
        $("#btn-show-tables").click(function(){
            if($("#table-details") .is(":hidden")){
            $.get("/cashier/getTable", function(data){
                $("#table-details").html(data);
                $("#table-details").slideDown('fast');
                $("#btn-show-tables").html('Hide Tables').removeClass('btn-primary').
                addClass('btn-danger');
            });
            }else{
                $("#table-details").slideUp('fast');
                $("#btn-show-tables").html('View All Tables').removeClass('btn-danger').
                addClass('btn-primary');
            }
        });

        //load menu by category
        $(".nav-link").click(function(){
            $.get("/cashier/getMenuByCategory/"+$(this).data("id"), function(data){
                $("#list-menu").hide();
                $("#list-menu").html(data);
                $("#list-menu").fadeIn('fast');
            });
        });

        var SELECTED_TABLE_ID = '';
        var SELECTED_TABLE_NAME = '';
        var SALE_ID = '';

        // Show seleted table data
        $("#table-details").on("click", ".btn-table", function(){
            SELECTED_TABLE_ID = $(this).data("id");
            SELECTED_TABLE_NAME = $(this).data("name");
            $("#selected-table").html('<br><h3>Table: '+SELECTED_TABLE_NAME+'</h3><hr>');
            $.get("/cashier/getSaleDetailByTable/"+SELECTED_TABLE_ID, function(data){
                $("#order-detail").html(data);
            });
        });

        // Show menus list on table selection
        $("#list-menu").on("click", ".btn-menu", function(){
            if(SELECTED_TABLE_ID == ""){
                alert('You need to select a table for the customer first');
            }else{
                var menu_id = $(this).data("id");
                $.ajax({
                    type: "POST",
                    data: {
                        "_token": $('meta[name="csrf-token"]').attr('content'),
                        "menu_id": menu_id,
                        "table_id": SELECTED_TABLE_ID,
                        "table_name": SELECTED_TABLE_NAME,
                        "quantity": 1
                    },
                    url: "/cashier/orderFood",
                    success: function(data){
                        $("#order-detail").html(data);
                    }
                });
            }
        });

        // Confirm order button
        $("#order-detail").on('click', '.btn-confirm-order', function(){
            var SaleID = $(this).data("id");
            $.ajax({
                type: 'POST',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "sale_id": SaleID
                },
                url: '/cashier/confirmOrderStatus',
                success: function(data){
                 $("#order-detail").html(data);
                }
            })
        });

        // delete sale details
        $("#order-detail").on('click', '.btn-delete-saledetail', function(){
            var saleDetailID = $(this).data("id");
            $.ajax({
                type: 'POST',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "saleDetail_id": saleDetailID
                },
                url: '/cashier/deleteSaleDetail',
                success: function(data){
                    $("#order-detail").html(data);
                }
            })
        });

        // Increase Quantity
        $("#order-detail").on('click', '.btn-increase-quantity', function(){
            var saleDetailID = $(this).data("id");
            $.ajax({
                type: 'POST',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "saleDetail_id": saleDetailID
                },
                url: '/cashier/increaseQuantity',
                success: function(data){
                    $("#order-detail").html(data);
                }
            })
        });

        // Decrease Quantity
        $("#order-detail").on('click', '.btn-decrease-quantity', function(){
            var saleDetailID = $(this).data("id");
            $.ajax({
                type: 'POST',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "saleDetail_id": saleDetailID
                },
                url: '/cashier/decreaseQuantity',
                success: function(data){
                    $("#order-detail").html(data);
                }
            })
        });

        // When use click on payment button
        $("#order-detail").on('click', '.btn-payment', function(){
            var totalAmount = $(this).attr("data-totalAmount");
            $(".totalAmount").html("Total Amount: $" + totalAmount);
            $("#received-amount").val('');
            $(".changeAmount").html('');
            SALE_ID = $(this).data('id');
        });

        // Calculate changeAmount
        $("#received-amount").keyup(function(){
            var totalAmount = $(".btn-payment").attr('data-totalAmount');
            var receivedAmount = $(this).val();
            var changeAmount = receivedAmount - totalAmount;
            $(".changeAmount").html("Total Change: $"+changeAmount);

            // enable/disable button on change ammount
            if(changeAmount >= 0){
                $(".btn-save-payment").prop('disabled', false);
            }else{
                $(".btn-save-payment").prop('disabled', true);
            }
        });

        // Save Payment
        $(".btn-save-payment").click(function(){
           // alert('hrllo');
            var receivedAmount = $("#received-amount").val();
            var paymentType = $("#payment-type").val();
            var saleId = SALE_ID;
            $.ajax({
                type: 'POST',
                data: {
                    "_token": $('meta[name="csrf-token"]').attr('content'),
                    "saleID": saleId,
                    "receivedAmount": receivedAmount,
                    "paymentType": paymentType
                },
                url: "/cashier/savePayment",
                success: function(data){
                    window.location.href=data;
                }
            })
        });

    });
</script>

@endsection
