<?php

namespace App\Modules\OrderSummary\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\OrderSummary\Models\OrderSummary;
use App\Modules\OrderSummary\Http\Repositories\OrderSummaryRepository;

class OrderSummaryController extends Controller
{
    protected $orderSummaryRepository;

    public function __construct(OrderSummaryRepository $orderSummaryRepository)
    {
        $this->orderSummaryRepository = $orderSummaryRepository;
    }

    public function index()
    {
        $orders = $this->orderSummaryRepository->all(null, null, [], false, 10);

        return view('order_summary::index', compact('orders'));
    }

    public function getTypeIndex($type)
    {
        $filter = [];

        if(request('search')){
            $filter['search'] = request('search');
        }

        if(request('status')){
            $filter['status'] = request('status');
        }

        if(request('orderType')){
            $filter['orderType'] = request('orderType');
        }

        if(request('from')){
            $filter['from'] = request('from');
        }

        $orders = $this->orderSummaryRepository->all('from', $type, [], false, 10, $filter);

        return view('order_summary::index', compact('orders'));
    }

    public function show(OrderSummary $order_summary)
    {
        $order = $order_summary;
        return view('order_summary::show', compact('order'));
    }

    public function edit(OrderSummary $order_summary)
    {
        $status = request()->status;
        $this->orderSummaryRepository->update($order_summary->id, ['status' => $status]);
        flash()->success(__('Order has been updated status ' . $status));

        return redirect()->back();
    }

    public function orderFroms($type)
    {
        $orderFroms = $this->orderSummaryRepository->orderFroms('from', $type);
        return response()->json($orderFroms);
    }


    public function orderCustomers($type)
    {
        $orderCustomers = $this->orderSummaryRepository->orderCustomers('from', $type);
        return response()->json($orderCustomers);
    }

}
