<?php

namespace App\Http\Controllers\Datatable;

use App\Http\Controllers\Controller;
use App\Models\Voucher;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class VoucherController extends Controller
{
    public function __invoke(Request $request)
    {
        $vouchers = Voucher::latest()->with('createdBy')
            ->select([
                'vouchers.*',
                'users.name as created_by_name',
            ])
            ->leftJoin('users', 'vouchers.created_by', '=', 'users.id');

        return DataTables::of($vouchers)
            ->addColumn('used_at', function ($voucher) {
                return $voucher->used_at ? $voucher->used_at->format('d M Y H:i') : '-';
            })
            ->addColumn('created_by_name', function ($voucher) {
                return $voucher->created_by_name ?? 'N/A';
            })
            ->addColumn('action', function ($voucher) {
                $actions = '';

                // Edit button
                $actions .= '<button type="button" class="btn btn-sm btn-warning me-1" onclick="editVoucher('.$voucher->id.')" title="Edit">
                                <i class="icon-pencil-alt"></i>
                            </button>';

                // Delete button
                $actions .= '<button type="button" class="btn btn-sm btn-danger" onclick="deleteVoucher('.$voucher->id.')" title="Delete">
                                <i class="icon-trash"></i>
                            </button>';

                return $actions;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
}
