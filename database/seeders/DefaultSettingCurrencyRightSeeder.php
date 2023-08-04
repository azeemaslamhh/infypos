<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DefaultSettingCurrencyRightSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keyExist = Setting::where('key', 'is_currency_right')->exists();
        if (! $keyExist) {
            Setting::create(['key' => 'is_currency_right', 'value' => false]);
        }
    }
}
