<?php

namespace Database\Seeders;

use App\Models\Donation;
use App\Models\Donor;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DonationSeeder extends Seeder
{
    public function run(): void
    {
        $donors = Donor::pluck('id', 'email');

        $donations = [
            ['donor_email' => 'amischats@example.test', 'amount' => 150.00, 'payment_method' => 'virement', 'donated_at' => Carbon::now()->subDays(4), 'receipt_number' => 'RC-2024-001'],
            ['donor_email' => 'julie@example.test', 'amount' => 45.00, 'payment_method' => 'carte', 'donated_at' => Carbon::now()->subDays(10), 'receipt_number' => 'RC-2024-002'],
            ['donor_email' => 'marc@example.test', 'amount' => 90.00, 'payment_method' => 'espèces', 'donated_at' => Carbon::now()->subMonth()->addDays(2), 'receipt_number' => 'RC-2024-003'],
        ];

        foreach ($donations as $donation) {
            if (! $donors->has($donation['donor_email'])) {
                continue;
            }

            Donation::create([
                'donor_id' => $donors[$donation['donor_email']],
                'amount' => $donation['amount'],
                'payment_method' => $donation['payment_method'],
                'donated_at' => $donation['donated_at'],
                'receipt_number' => $donation['receipt_number'],
                'is_receipt_sent' => true,
            ]);
        }
    }
}
