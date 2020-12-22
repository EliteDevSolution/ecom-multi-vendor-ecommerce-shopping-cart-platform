<?php

namespace App\Http\Controllers\Admin;

use App\Vendor;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Deposit;
use App\Product;
use Session;
use App\Transaction;

class VendorManagementController extends Controller
{
    public function allVendors() {
      $data['vendors'] = Vendor::orderBy('shop_name', 'ASC')->paginate(15);
      $data['term'] = '';
      return view('admin.VendorManagement.allVendors', $data);
    }

    public function allVendorsSearchResult(Request $request) {
      $data['term'] = $request->term;
      $data['vendors'] = Vendor::where('shop_name', 'like', '%'.$request->term.'%')->orderBy('shop_name', 'ASC')->paginate(15);
      return view('admin.VendorManagement.allVendors',$data);
    }

    public function bannedVendors() {
      $data['term'] = '';
      $data['vendors'] = Vendor::where('status', 'blocked')->paginate(15);
      return view('admin.VendorManagement.bannedVendors', $data);
    }

    public function bannedVendorsSearchResult(Request $request) {
      $data['term'] = $request->term;
      $data['vendors'] = Vendor::where('shop_name', 'like', '%'.$request->term.'%')->where('status', 'blocked')->paginate(15);
      return view('admin.VendorManagement.bannedVendors',$data);
    }

    public function vendorDetails($vendorID) {
      $data['vendor'] = Vendor::find($vendorID);
      return view('admin.VendorManagement.vendorDetails.vendorDetails', $data);
    }

    public function updateVendorDetails (Request $request) {
      $validatedData = $request->validate([
        'shop_name' => 'required',
        'email' => 'required',
        'phone' => 'required',
      ]);

      $vendor = Vendor::find($request->vendorID);
      $vendor->shop_name = $request->shop_name;
      $vendor->email = $request->email;
      $vendor->phone = $request->phone;
      $vendor->status = $request->status=='on'?'active':'blocked';
      $vendor->save();


      Session::flash('success', 'Vendor details has been updated successfully!');

      return redirect()->back();
      // return $request->all();
    }

    public function updateVendorBalance(Request $request) {
      $validatedData = $request->validate([
          'amount' => 'required',
      ]);

      $vendor = Vendor::find($request->vendorID);
      $balance = $vendor->balance;
      // if add money operation is selected then add the amount...
      if ($request->has('operation')) {
        $balance = $balance + $request->amount;
        $successMessage = 'Amount has been added successfully!';
      } else {
        $balance = $balance - $request->amount;
        $successMessage = 'Amount has been subtracted successfully!';
      }
      if($request->has('message')) {
        $name = $vendor->shop_name;
        $subject = 'Balance updated in your account';
        $message = $request->message;
        send_email( $vendor->email, $name, $subject, $message);
      }

      $vendor->balance = $balance;
      $vendor->save();
      Session::flash('success', $successMessage);
      return redirect()->back();
    }

    public function addSubtractBalance($vendorID) {
      $data['vendor'] = Vendor::find($vendorID);
      return view('admin.VendorManagement.vendorDetails.addSubtractBalance', $data);
    }

    public function emailToVendor($vendorID) {
      $data['vendor'] = Vendor::find($vendorID);
      return view('admin.VendorManagement.vendorDetails.emailToVendor', $data);
    }

    public function sendEmailToVendor(Request $request) {
      $validatedData = $request->validate([
          'subject' => 'required',
          'message' => 'required'
      ]);
      $vendor = Vendor::find($request->vendorID);
      $to = $vendor->email;
      $name = $vendor->shop_name;
      $subject = $request->subject;
      $message = $request->message;
       send_email( $to, $name, $subject, $message);
      Session::flash('success', 'Mail sent successfully!');
      return redirect()->back();
    }

    public function depositLog($empID) {
        $data['deposits'] = Deposit::where('employee_id', $empID)->where('status', 1)->paginate(15);
        return view('admin.deposit.depositLog', $data);
    }

    public function jobs(Request $request, $empId) {
      if (empty($request->term)) {
        $data['term'] = '';
        $data['jobs'] = Job::where('employee_id', $empId)->latest()->paginate(9);
      } else {
        $data['term'] = $request->term;
        $data['jobs'] = Job::where('employee_id', $empId)->where('title', 'like', '%'.$request->term.'%')->latest()->paginate(9);
      }
      return view('admin.job.index', $data);
    }

    public function trxlog($empId) {
      $data['trs'] = Transaction::where('employee_id', $empId)->latest()->paginate(15);
      return view('admin.trxlog', $data);
    }
}
