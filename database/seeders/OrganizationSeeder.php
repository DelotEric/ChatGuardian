<?php

namespace Database\Seeders;

use App\Models\Organization;
use Illuminate\Database\Seeder;

class OrganizationSeeder extends Seeder
{
    public function run(): void
    {
        $organization = Organization::first();

        if (!$organization) {
            Organization::create(Organization::defaults());
            return;
        }

        if (!$organization->api_token) {
            $organization->update(['api_token' => Organization::defaults()['api_token']]);
        }
    }
}
