<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function check_balance($account_id)
    {
        $user = User::where('email', $account_id)->first();

        // if user not exist
        if (!$user) {
            return [
                'status' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        return response()->json([
            'account_id' => $account_id,
            'balance' => "$user->balance"
        ]);
    }

    public function deposit(Request $request)
    {
        $account_id = $request->input('account_id');
        $deposit = $request->input('deposit');

        $user = User::where('email', $account_id)->first();

        // if user not exist
        if (!$user) {
            return [
                'status' => false,
                'message' => 'User tidak ditemukan'
            ];
        }

        $update_deposit = User::where('email', $account_id)->update([
            'balance' => $user->balance + $deposit
        ]);
        if ($update_deposit) {
            return response()->json([
                'response_code' => '00',
                'response_message' => 'Success'
            ]);
        } else {
            return response()->json([
                'response_code' => '01',
                'response_message' => 'Failed'
            ]);
        }
    }
}
