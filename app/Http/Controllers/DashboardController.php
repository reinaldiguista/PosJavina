<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Member;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\ProdukNew;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $member = Member::distinct('customer_type')->get();

        $cart = Cart::with('member')
        ->select('customer_id', 'employee_id')
        ->where('isSend', 1)
        ->where('flag', 0)
        ->distinct()
        ->get();
        
        $produk = ProdukNew::count();
        $members = Member::count();

        $tanggal_awal = date('Y-m-01');
        $tanggal_akhir = date('Y-m-d');

        $data_tanggal = array();
        $data_pendapatan = array();

        while (strtotime($tanggal_awal) <= strtotime($tanggal_akhir)) {
            $data_tanggal[] = (int) substr($tanggal_awal, 8, 2);

            $total_penjualan = Penjualan::where('transaction_status', 1)->where('created_at', 'LIKE', "%$tanggal_awal%")->sum('total_price');

            $pendapatan = $total_penjualan ;
            $data_pendapatan[] += $pendapatan;

            $tanggal_awal = date('Y-m-d', strtotime("+1 day", strtotime($tanggal_awal)));
        }

        $tanggal_awal = date('Y-m-01');

        if (auth()->user()->level == 1) {
            return view('admin.dashboard', compact( 'produk', 'member', 'members', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        } elseif (auth()->user()->level == 2) {
            return view('supervisor.dashboard', compact( 'produk', 'member', 'members', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        } elseif (auth()->user()->level == 3) {
            return view('kasir.dashboard', compact( 'cart','produk', 'member', 'members', 'tanggal_awal', 'tanggal_akhir', 'data_tanggal', 'data_pendapatan'));
        } else {
            return view('pendamping.dashboard', compact('member'));
        }
    }
}
