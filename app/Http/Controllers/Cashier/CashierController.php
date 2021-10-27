<?php

namespace App\Http\Controllers\Cashier;

use App\Category;
use App\Http\Controllers\Controller;
use App\Menu;
use App\Sale;
use App\SaleDetail;
use App\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CashierController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('cashier.index')->with('categories', $categories);
    }

    public function getTable()
    {
        $tables = Table::all();
        $html = '';
        foreach ($tables as $table) {
            $html .= '<div class="col-md-2 mb-4">';
            $html .= '<button class="btn btn-primary btn-table"
                        data-id="' . $table->id . '"
                        data-name="' . $table->name . '">
                        <img class="img-fluid" src="' . url('/images/table.png') . '">
                        <br>';
            if ($table->status == "available") {
                $html .= '<span class="badge badge-success">' . $table->name . '</span>';
            } else {
                $html .= '<span class="badge badge-danger">' . $table->name . '</span>';
            }
            $html .= '</button>';
            $html .= '</div>';
        }

        return $html;
    }

    public function getMenuByCategory($category_id)
    {
        $menus = Menu::where('category_id', $category_id)->get();
        //echo "<pre>";
        // print_r($menus);die();
        $html = '';

        foreach ($menus as $menu) {
            $html .= '
            <div class="col-md-3 text-center">
                <a class="btn btn-outline-secondary btn-menu" data-id="' . $menu->id . '">
                    <img class="img-fluid" src="' . url('/menu_images/' . $menu->image) . '" >
                    <br>
                    ' . $menu->name . '
                    <br>
                    $' . number_format($menu->price) . '
                </a>
            </div>';
        }

        return $html;
    }

    public function orderFood(Request $request)
    {
        //print_r($request->menu_id);
        //print_r($_REQUEST[]);
        $menu = Menu::find($request->menu_id);
        $table_id = $request->table_id;
        $table_name = $request->table_name;
        $sale = Sale::where('table_id', $table_id)->where('sales_status', 'unpaid')->first();

        // if there is no sale for the selected table
        if (!$sale) {
            $user = Auth::user();
            $sale = new Sale();
            $sale->table_id = $table_id;
            $sale->table_name = $table_name;
            $sale->user_id = $user->id;
            $sale->user_name = $user->name;
            $sale->save();

            $sale_id = $sale->id;

            // Update table status
            $table = Table::find($table_id);
            $table->status = "unavailable";
            $table->save();
        } else {
            // if there is a sale on selected table
            $sale_id = $sale->id;
        }

        // Add order menu to sale deatils table
        $saleDetail = new SaleDetail();
        $saleDetail->sale_id = $sale_id;
        $saleDetail->menu_id = $menu->id;
        $saleDetail->menu_name = $menu->name;
        $saleDetail->menu_price = $menu->price;
        $saleDetail->quantity = $request->quantity;
        $saleDetail->save();

        // Update total price in sales table
        $sale->total_price = $sale->total_price + ($request->quantity * $menu->price);
        $sale->save();

        $html = $this->getSaleDetail($sale_id);

        return $html;
    }

    public function getSaleDetailByTable($table_id)
    {
        $sale = Sale::where('table_id', $table_id)->where('sales_status', 'unpaid')->first();
        $html = '';

        if ($sale) {
            $sale_id = $sale->id;
            $html .= $this->getSaleDetail($sale_id);
        } else {
            $html .= "No rocord found for the selected table";
        }

        return $html;
    }

    private function getSaleDetail($sale_id)
    {
        // list all sale-details
        $html = "<p>Sale ID: " . $sale_id . "</p>";
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->get();
        $html .= '.
                 <div class="bg-info">
                 <table class="table table-bordered">
                     <thead class="thead-dark">
                         <tr>
                         <th scope="col">#</th>
                         <th scope="col">Menu</th>
                         <th scope="col">Quantity</th>
                         <th scope="col">Price</th>
                         <th scope="col">Total</th>
                         <th scope="col">Status</th>
                         </tr>
                     </thead>
                     <tbody>';

        $showBtnPayment = true;

        foreach ($saleDetails as $saleDetail) {

            $decreaseButton = '<button class="btn btn-sm btn-danger btn-decrease-quantity" disabled>-</button>';
            if ($saleDetail->quantity > 1) {
                $decreaseButton = '<button data-id="' . $saleDetail->id . '" class="btn btn-sm btn-danger btn-decrease-quantity">-</button>';
            }

            $html .= '
                     <tr>
                         <td>' . $saleDetail->menu_id . '</td>
                         <td>' . $saleDetail->menu_name . '</td>
                         <td>
                            ' . $decreaseButton . ' ' . $saleDetail->quantity . '
                            <button data-id="' . $saleDetail->id . '" class="btn btn-sm btn-primary btn-increase-quantity">+</button>
                        </td>
                         <td>' . $saleDetail->menu_price . '</td>
                         <td>' . ($saleDetail->menu_price * $saleDetail->quantity) . '</td>';
            if ($saleDetail->status == 'noConfirm') {
                $showBtnPayment = false;
                $html .= '<td><a data-id="' . $saleDetail->id . '" class="btn btn-danger btn-sm btn-delete-saledetail"><i class="fas fa-trash-alt"></i></a></td>';
            } else {
                $html .= '<td><i class="fas fa-check-circle"></i></td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody>
                 </table>
                 </div>';

        $sale = Sale::find($sale_id);
        $html .= "<hr>";
        $html .= "<h3>Total Amount: $" . number_format($sale->total_price) . "</h3>";

        if ($showBtnPayment == 'confirm') {
            $html .= '<button
                            data-id="' . $sale_id . '"
                            data-totalAmount="' . $sale->total_price . '"
                            data-toggle="modal"
                            data-target="#exampleModal"
                            class="btn btn-success btn-block btn-payment">
                        Payment
                     </button>';
        } else {
            $html .= '<button data-id="' . $sale_id . '" class="btn btn-warning btn-block btn-confirm-order">
            Confirm Order
        </button>';
        }

        return $html;
    }

    public function confirmOrderStatus(Request $request)
    {
        $sale_id = $request->sale_id;
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->update(
            ['status' => 'confirm']);
        $html = $this->getSaleDetail($sale_id);
        return $html;
    }

    public function deleteSaleDetail(Request $request)
    {
        $saleDetail_id = $request->saleDetail_id;
        $saleDetail = SaleDetail::find($saleDetail_id);
        $sale_id = $saleDetail->sale_id;
        $menu_price = ($saleDetail->menu_price * $saleDetail->quantity);
        $saleDetail->delete();

        // update total price
        $sale = Sale::find($sale_id);
        $sale->total_price = $sale->total_price - $menu_price;
        $sale->save();

        // check if there is any sale detail using sale_id
        $saleDetails = SaleDetail::where('sale_id', $sale_id)->first();

        if ($saleDetail) {
            $html = $this->getSaleDetail($sale_id);
        } else {
            $html = "No rocord found for the selected table";
        }

        return $html;
    }

    public function savePayment(Request $request)
    {
        $saleID = $request->saleID;
        $receivedAmount = $request->receivedAmount;
        $paymentType = $request->paymentType;

        // Update sales info in the sales table using sale model
        $sale = Sale::find($saleID);
        $sale->total_received = $receivedAmount;
        $sale->change = $receivedAmount - $sale->total_price;
        $sale->payment_type = $paymentType;
        $sale->sales_status = 'paid';
        $sale->save();

        //update table to be available
        $table = Table::find($sale->table_id);
        $table->status = "available";
        $table->save();

        return "/cashier/showReceipt/" . $saleID;
    }

    public function showReceipt($saleID)
    {
        $sale = Sale::find($saleID);
        $saleDetails = SaleDetail::where('sale_id', $saleID)->get();
        return view('cashier.showReceipt')->with('sale', $sale)->with('saleDetails', $saleDetails);
    }

    public function increaseQuantity(Request $request)
    {
        $saleDetail_id = $request->saleDetail_id;

        // Update Quantity
        $saleDetail = SaleDetail::where('id', $saleDetail_id)->first();
        $saleDetail->quantity = $saleDetail->quantity + 1;
        $saleDetail->save();

        // Update Total Amount
        $sale = Sale::where('id', $saleDetail->sale_id)->first();
        $sale->total_price = $sale->total_price + $saleDetail->menu_price;
        $sale->save();

        $html = $this->getSaleDetail($saleDetail->sale_id);
        return $html;
    }

    public function decreaseQuantity(Request $request)
    {
        $saleDetail_id = $request->saleDetail_id;

        // Update Quantity
        $saleDetail = SaleDetail::where('id', $saleDetail_id)->first();
        $saleDetail->quantity = $saleDetail->quantity - 1;
        $saleDetail->save();

        // Update Total Amount
        $sale = Sale::where('id', $saleDetail->sale_id)->first();
        $sale->total_price = $sale->total_price - $saleDetail->menu_price;
        $sale->save();

        $html = $this->getSaleDetail($saleDetail->sale_id);
        return $html;
    }

}
