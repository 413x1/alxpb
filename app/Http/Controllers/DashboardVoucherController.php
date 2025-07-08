<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Yajra\DataTables\Services\DataTable;

class DashboardVoucherController extends Controller
{
    public function __construct()
    {
        $this->base_view_path = 'pages.admin.voucher.';
    }

    public function index()
    {

        return view($this->base_view_path .'index');
    }

    public function getVoucherData(Request $request)
    {
        $size = $request->input('length', 10); // Number of records per page
        $start = $request->input('start', 0); // Offset
        $search = $request->input('search.value'); // Search keyword

        $query = Voucher::select('vouchers.*', 'users.name as created_by_name')
            ->join('users', 'vouchers.created_by', '=', 'users.id');

        // Filtering
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('vouchers.code', 'like', "%{$search}%")
                    ->orWhere('users.name', 'like', "%{$search}%");
            });
        }

        // Use DataTables for paging, sorting, etc.
        return DataTables::of($query)
            ->addColumn('action', function ($row) {
                return '<a href="javascript:void(0);" class="btn-edit-voucher" data-id="'. $row->id .'"><i class="icon-pencil-alt"></i></a>';
            })
            ->make(true);
    }
}
