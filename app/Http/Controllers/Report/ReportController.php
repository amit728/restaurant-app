<?php

namespace App\Http\Controllers\Report;

use App\Exports\SaleReportExport;
use App\Http\Controllers\Controller;
use App\Sale;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index()
    {
        return view('report/index');
    }

    public function show(Request $request)
    {
        //return "fjhgdfj";
        $request->validate([
            'dateStart' => 'required',
            'dateEnd' => 'required',
        ]);

        $dateStart = date("Y-m-d H:i:s", strtotime($request->dateStart . '00:00:00'));
        $dateEnd = date("Y-m-d H:i:s", strtotime($request->dateEnd . '23:59:59'));
        $sales = Sale::whereBetween('updated_at', [$dateStart, $dateEnd])
            ->where('sales_status', 'paid');

        return view('report/showReport')
            ->with('dateStart', date("m/d/y H:i:s", strtotime($request->dateStart . '00:00:00')))
            ->with('dateEnd', date("m/d/y H:i:s", strtotime($request->dateEnd . '00:00:00')))
            ->with('totalSale', $sales->sum('total_price'))
            ->with('sales', $sales->paginate(5));
    }

    public function export(Request $request)
    {
        return Excel::download(new SaleReportExport(
            $request->dateStart, $request->dateEnd), 'saleReport.xlsx');
    }
}
