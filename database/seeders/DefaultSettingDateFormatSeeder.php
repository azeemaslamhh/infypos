<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DefaultSettingDateFormatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $exists = Setting::where('key', 'date_format')->exists();
        if (! $exists) {
            Setting::create(['key' => 'date_format', 'value' => 'y-m-d']);
        }
    }
}
