<?php

namespace App\Http\Controllers\Datatable;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class OrderController extends Controller
{
    public function __invoke(Request $request)
    {
        $orders = Order::latest()->with(['customer', 'device', 'product'])
            ->select([
                'orders.*',
            ]);

        return DataTables::of($orders)
            ->addColumn('code', function ($order) {
                return $order->code ?? '-';
            })
            ->addColumn('customer_name', function ($order) {
                return $order->customer->name ?? 'N/A';
            })
            ->addColumn('product_name', function ($order) {
                return $order->product->name ?? 'N/A';
            })
            ->addColumn('device_name', function ($order) {
                return $order->device->name ?? 'N/A';
            })
            ->addColumn('qty', function ($order) {
                return $order->qty ?? 0;
            })
            ->addColumn('total_price', function ($order) {
                return 'Rp '.number_format($order->total_price, 0, ',', '.');
            })
            ->addColumn('status', function ($order) {
                $statusClass = match ($order->status) {
                    'pending' => 'badge-warning',
                    'completed' => 'badge-success',
                    'cancelled' => 'badge-danger',
                    default => 'badge-secondary'
                };

                return '<span class="badge '.$statusClass.'">'.ucfirst($order->status).'</span>';
            })
            ->addColumn('created_at', function ($order) {
                return $order->created_at ? $order->created_at->format('d M Y H:i') : '-';
            })
            ->addColumn('action', function ($order) {
                $actions = '';

                // Edit button only
                $actions .= '<button type="button" class="btn btn-sm btn-warning" onclick="editOrder('.$order->id.')" title="Edit">
                                <i class="icon-pencil-alt"></i>
                            </button>';

                return $actions;
            })
            ->rawColumns(['status', 'action'])
            ->make(true);
    }
}
