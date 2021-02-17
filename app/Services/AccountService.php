<?php


namespace App\Services;


use App\Models\Account;

class AccountService
{
    public static function updateAccount(Account $account, $value, $type)
    {
        $account->old_value = $account->value;
        if ($type == 'add') {
            $account->value = $account->value + $value;
        } else {
            $account->value = $account->value - $value;
        }
        $account->save();
        $account->refresh();
    }
}
