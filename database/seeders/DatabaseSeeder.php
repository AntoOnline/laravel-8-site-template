<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder {

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {
        DB::table('tools')->insert([
            'name' => 'Your public IP',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'what-is-my-ip-address',
            'icon' => 'fas fa-network-wired',
            'color' => 'bg-success',
            'comment' => 'Determine your public IP address.',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'AWS IP Address Ranges',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'aws-ip-address-ranges',
            'icon' => 'fab fa-aws',
            'color' => 'bg-info',
            'comment' => 'Find all of the AWS public IP addresses.',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'CHMOD calculator',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'chmod-calculator',
            'icon' => 'fas fa-unlock',
            'color' => 'bg-dark',
            'comment' => 'Calculate Linux file/folder permissions.',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'URL Encode/Decode',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'url-encode-decode',
            'icon' => 'fas fa-square-root-alt',
            'color' => 'bg-danger',
            'comment' => 'Encode and decode URLs',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'NTP Tester',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'ntp-tester',
            'icon' => 'fas fa-clock',
            'color' => 'bg-warning',
            'comment' => 'Test a NTP server to the the offset.',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'String Tools',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'string-tools',
            'icon' => 'fas fa-quote-right',
            'color' => 'bg-info',
            'comment' => 'Linearize, beautify and encode JSON/XML.',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'Data Multiplier',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'data-multiplier',
            'icon' => 'fas fa-times',
            'color' => 'bg-info',
            'comment' => 'Use text tags in sample data, then create copies of this data by auto completing values.',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'Base 64 Encode Decode',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'base-64-encode-decode',
            'icon' => 'fas fa-blender',
            'color' => 'bg-primary',
            'comment' => 'Base 64 encode and decode content.',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'HTTP Status Checker',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'http-status-checker',
            'icon' => 'fas fa-heart',
            'color' => 'bg-secondary',
            'comment' => 'Check the HTTP status code of a URL.',
            'visits' => 0,
        ]);

        DB::table('tools')->insert([
            'name' => 'Site File Checker',
            'chargeModel' => 'f',
            'bannerArea' => '',
            'route' => 'site-file-checker',
            'icon' => 'fas fa-file',
            'color' => 'bg-dark',
            'comment' => 'Check if specific files do/donot exist on your site.',
            'visits' => 0,
        ]);
    }

}
