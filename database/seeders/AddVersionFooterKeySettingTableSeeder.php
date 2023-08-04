<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class AddVersionFooterKeySettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $keyExist = Setting::where('key', 'show_version_on_footer')->exists();
        if (! $keyExist) {
            Setting::create(['key' => 'show_version_on_footer', 'value' => true]);
        }
    }
}
