<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\LA\LAActivity;
use Illuminate\Http\Request;
use App\Models\User;
use Validator;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $ListActivity = [];

        $ListActivity['ongoing'] = LAActivity::select('id', 'method_id', 'name', 'start_date', 'end_date', 'created_by', 'updated_by')
            ->whereRaw("date(start_date) <= '" . date('Y-m-d') . "' AND date(end_date) >= '" . date('Y-m-d') . "'")
            ->whereHas('method')
            ->with(['method:id,name'])
            ->orderBy('start_date', 'ASC')
            ->take(3)
            ->get();

        return view('user.dashboard', compact(['ListActivity']));
    }

    // Form Change Pasword
    public function changePasswordForm()
    {
        return view('user.change-password', compact([]));
    }

    // Process Change Pasword
    public function changePasswordProcess(Request $input)
    {
        $rules = [
            'old_password'          => 'required|min:8|max:30',
            'password'              => 'required|regex:/^[A-Za-z0-9!@#$%=%.,^*+-]+$/|min:8|max:30|confirmed',
            'password_confirmation' => 'required|min:8|max:30',
        ];

        $Validator          = Validator::make($input->all(), $rules);
        if ($Validator->fails()) {
            return redirect()->back()->withErrors($Validator);
        }

        // check if Password has not changed
        if ($input->old_password == $input->password) {
            return redirect()->back()->with(['error' => '<i class="fa fa-warning"></i> Password has not changed'])->withInput($input->all());
        }

        // Check Old Password
        $OLDPasswordDB       = auth()->user()->password;

        $verify_old_password = password_verify($input->old_password, $OLDPasswordDB);
        if (!$verify_old_password) {
            return redirect()->back()->with(['error' => '<i class="fa fa-warning"></i> Wrong old password'])->withInput($input->except(['old_password']));
        }

        $User = User::find(auth()->id());
        $User->password = bcrypt($input->password);
        $save = $User->save();

        if (!$save) {
            return redirect()->back()->with(['error' => '<i class="fa fa-warning"></i> Password failed to change'])->withInput($input->all());
        }

        auth()->logout();

        return redirect()->route('login')->with(['success' => '<i class="fa fa-check"></i> Password changed successfully']);
    }
}
