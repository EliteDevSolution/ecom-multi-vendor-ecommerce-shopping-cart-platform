<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\GeneralSetting;
use Session;
use App\Provider;
use Auth;
use Validator;

class GeneralSettingController extends Controller
{
    public function GenSetting(){
      $data['provider'] = Provider::find(1);
      return view('admin.GeneralSettings', $data);
    }

    public function UpdateGenSetting(Request $request){
      // return $request->all();
        $messages = [
          // 'secColorCode.required' => 'Secondary color code is required',
          // 'secColorCode.size' => 'Secondary color code must have 6 characters',
          'baseColorCode.required' => 'Base color code is required',
          'baseColorCode.size' => 'Base color code must have 6 characters',
          'decimalAfterPt.required' => 'Decimal after point is required',
        ];

        $validator = Validator::make($request->all(), [
          'websiteTitle' => 'required',
          'baseColorCode' => 'required|size:6',
          'app_id' => 'required',
          'app_secret' => 'required',
          'baseCurrencyText' => 'required',
          'baseCurrencySymbol' => 'required',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->route('admin.GenSetting')
                        ->withErrors($validator);
        }

        $generalSettings = GeneralSetting::first();

        $generalSettings->website_title = $request->websiteTitle;
        $generalSettings->base_color_code = $request->baseColorCode;
        $generalSettings->main_city = $request->main_city;
        $generalSettings->base_curr_text = $request->baseCurrencyText;
        $generalSettings->base_curr_symbol = $request->baseCurrencySymbol;
        $generalSettings->registration = $request->registration=='on'?1:0;
        $generalSettings->email_verification = $request->emailVerification=='on'?0:1;
        $generalSettings->sms_verification = $request->smsVerification=='on'?0:1;
        $generalSettings->email_notification = $request->emailNotification=='on'?1:0;
        $generalSettings->sms_notification = $request->smsNotification=='on'?1:0;
        $generalSettings->registration = $request->registration=='on'?1:0;

        $generalSettings->save();

        $provider = Provider::find(1);
        $provider->status = $request->status == 'on' ? 1:0;
        $provider->client_id = $request->app_id;
        $provider->client_secret = $request->app_secret;
        $provider->save();

        Session::flash('success', 'Successfully Updated!');

        return redirect()->route('admin.GenSetting');
    }
}
