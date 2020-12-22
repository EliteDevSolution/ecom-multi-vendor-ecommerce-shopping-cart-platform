<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Transaction;

class TrxController extends Controller
{
    public function index($vendorid=null) {
      if (!empty($vendorid)) {
        $data['trs'] = Transaction::where('vendor_id', $vendorid)->latest()->paginate(15);
      } else {
        $data['trs'] = Transaction::latest()->paginate(15);
      }
      return view('admin.trxlog', $data);
    }
}
