<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Refund;
use Illuminate\Http\Request;

class RefundController extends Controller
{
    public function index()
    {
        $refunds = Refund::with([
            'payment',
            'order.user:id,name,email',
            'order.restaurant:id,name',
            'initiatedBy:id,name',
        ])->latest()->paginate(20);

        return view('admin.refunds.index', compact('refunds'));
    }
    public function create() {}
    public function store() {}
    public function show() {}
}
