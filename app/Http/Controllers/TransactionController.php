<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TransactionController extends Controller
{
    //

    private $transaction_model;
    private $user_model;

    public function __construct()
    {
        $this->transaction_model = new \App\Models\TransactionModel;
        $this->user_model = new \App\Models\User;
    }

    public function deposit()
    {

        $data['deposit_active'] = 'active';
        return view('transaction.deposit', $data);
    }

    public function deposit_save(Request $request)
    {
        $request->validate([
            'amount' => 'required'
        ]);
        $new_balance = auth()->user()->account_balance + $request->get('amount');

        $values = array(
            'user' => auth()->user()->id,
            'type' => 'Credit',
            'source_type' => 'Bank',
            'source_id' => 0,
            'target_type' => 'User',
            'target_id' => auth()->user()->id,
            'amount' => $request->get('amount'),
            'balance' => $new_balance,
            'created_at' => date('Y-m-d H:i:s'),
        );

        $account_balance = ['account_balance' => $new_balance];

        $this->transaction_model->save_data($values);
        $this->user_model->save_data($account_balance, auth()->user()->id);

        return redirect('/statement')->with('success', 'Amount successfully credited');
    }

    public function withdraw()
    {

        $data['withdraw_active'] = 'active';
        return view('transaction.withdraw', $data);
    }

    public function withdraw_save(Request $request)
    {
        if (auth()->user()->account_balance <= 0 || $request->get('amount') > auth()->user()->account_balance) {
            return redirect()->back()->with('error', ' Insufficient balance..!');
        } else {
            $request->validate([
                'amount' => 'required'
            ]);
            $new_balance = auth()->user()->account_balance - $request->get('amount');

            $values = array(
                'user' => auth()->user()->id,
                'type' => 'Debit',
                'source_type' => '',
                'source_id' => 0,
                'target_type' => '',
                'target_id' => 0,
                'amount' => $request->get('amount') ?: 0,
                'balance' => $new_balance,
                'created_at' => date('Y-m-d H:i:s'),
            );

            $account_balance = ['account_balance' => $new_balance];

            $this->transaction_model->save_data($values);
            $this->user_model->save_data($account_balance, auth()->user()->id);

            return redirect('/statement')->with('success', ' Amount successfully debited');
        }
    }

    public function transfer()
    {

        $data['transfer_active'] = 'active';
        return view('transaction.transfer', $data);
    }

    public function transfer_save(Request $request)
    {
        if (auth()->user()->account_balance <= 0 || $request->get('amount') > auth()->user()->account_balance) {
            return redirect()->back()->with('error', ' Insufficient balance..!');
        } elseif ($request->get('amount') > auth()->user()->account_balance) {
            return redirect()->back()->with('error', ' Insufficient balance..!');
        } else {
            $request->validate([
                'amount' => 'required',
                'email' => 'required|email',
            ]);
            if ($target_user = $this->user_model->get_row($request->get('email'))) {

                $new_balance = auth()->user()->account_balance - $request->get('amount');
                $values = array(
                    'user' => auth()->user()->id,
                    'type' => 'Debit',
                    'source_type' => 'User',
                    'source_id' => auth()->user()->id,
                    'target_type' => 'User',
                    'target_id' => $target_user->id,
                    'amount' => $request->get('amount'),
                    'balance' => $new_balance,
                    'created_at' => date('Y-m-d H:i:s'),
                );

                $account_balance = ['account_balance' => $new_balance];

                $this->transaction_model->save_data($values);
                $this->user_model->save_data($account_balance, auth()->user()->id);



                //-------------------------------------
                $target_user_new_balance = $target_user->account_balance + $request->get('amount');

                $target_values = array(
                    'user' => $target_user->id,
                    'type' => 'Credit',
                    'source_type' => 'User',
                    'source_id' => auth()->user()->id,
                    'target_type' => 'User',
                    'target_id' => $target_user->id,
                    'amount' => $request->get('amount'),
                    'balance' => $target_user_new_balance,
                    'created_at' => date('Y-m-d H:i:s'),
                );

                $target_user_account_balance = ['account_balance' => $target_user_new_balance];

                $this->transaction_model->save_data($target_values);
                $this->user_model->save_data($target_user_account_balance, $target_user->id);

                return redirect('/statement')->with('success', 'Amount successfully transfered');
            } else {
                return redirect()->back()->with('error', ' Target account not found..!');
            }
        }
    }

    public function statement()
    {

        $data['statement_active'] = 'active';
        $data['data'] = $this->transaction_model->get_statement(auth()->user()->id);
        // dd($data['data']);
        return view('transaction.statement', $data);
    }


    public function delete($id = 0)
    {
        if ($id > 0) {
            $this->transaction_model->delete_data($id);
            return redirect()->back()->with("success", "Item Deleted successfully!");
        }
    }
}
