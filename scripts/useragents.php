<?php
use Jenssegers\Agent\Agent;

date_default_timezone_set('UTC');

require dirname(__DIR__) . '/vendor/autoload.php';

$file = "/Users/patrick/src/flagship/tmp/user_agents.csv";
$devices = [
    'browser' => [],
    'device' => [],
    'platform' => [],
];
$robots = [];

if (($handle = fopen($file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle)) !== FALSE) {
        $agent = new Agent();
        $agent->setUserAgent($data[0]);
        $browser = $agent->browser();
        $device = $agent->device();
        $platform = $agent->platform();

        $version = $agent->version($browser);
        if (isset($version)) {
            $version = $browser . ' ' . $version;
        }

        if (empty($devices['browser'][$browser])) {
            $devices['browser'][$browser] = 0;
        }
        $devices['browser'][$browser]++;

        if (empty($devices['device'][$device])) {
            $devices['device'][$device] = 0;
        }
        $devices['device'][$device]++;

        if (empty($devices['platform'][$platform])) {
            $devices['platform'][$platform] = 0;
        }
        $devices['platform'][$platform]++;

        if (empty($devices['version'][$version])) {
            $devices['version'][$version] = 0;
        }
        $devices['version'][$version]++;


        if ($agent->isRobot()) {
            $robots[] = [
                'agent' => $data[0],
                'type' => $agent->robot()
            ];
        }
    }
    fclose($handle);
}

print_r($devices);
print_r($robots);
