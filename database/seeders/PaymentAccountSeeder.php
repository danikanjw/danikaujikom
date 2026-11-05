<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentAccountSeeder extends Seeder
{
    public function run(): void
    {
        $account = [
            'bank_name' => 'Bank AlkesHub',
            'account_number' => '0000000000',
            'account_holder' => 'Toko AlkesHub',
        ];

        $now = now();
        $existingAccount = DB::table('payment_accounts')
            ->where('bank_name', $account['bank_name'])
            ->where('account_number', $account['account_number']);

        if ($existingAccount->exists()) {
            $existingAccount->update([
                'account_holder' => $account['account_holder'],
                'is_active' => true,
                'updated_at' => $now,
            ]);

            return;
        }

        DB::table('payment_accounts')->insert($account + [
            'is_active' => true,
            'created_at' => $now,
            'updated_at' => $now,
        ]);
    }
}
