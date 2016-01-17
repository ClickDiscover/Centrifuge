<?php
use Jenssegers\Agent\Agent;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

date_default_timezone_set('UTC');

require dirname(__DIR__) . '/vendor/autoload.php';

$file = "/Users/patrick/src/flagship/tmp/user_agents.csv";
$limit = 10000000;
$devices = [
    'browser' => [],
    'device' => [],
    'platform' => [],
    'bots' => []
];
$robots = [];
$total = 0;

$cd = new CrawlerDetect;
$crawlers = 0;
$ctypes = [];

$row = 0;
if (($handle = fopen($file, "r")) !== FALSE) {
    while (($data = fgetcsv($handle)) !== FALSE) {
        $row++;
        if ($row > $limit) {
            break;
        }
        $agent = new Agent();
        $agent->setUserAgent($data[0]);
        $browser = $agent->browser();
        $device = $agent->device();
        $platform = $agent->platform();
        $total++;

        // $version = $agent->version($browser);
        // if (isset($version)) {
        //     $version = $browser . ' ' . $version;
        // }
        // if (empty($devices['version'][$version])) {
        //     $devices['version'][$version] = 0;
        // }
        // $devices['version'][$version]++;

        if ($cd->isCrawler($data[0])) {
            $crawlers++;
            $m = $cd->getMatches();
            if (empty($ctypes[$m])) {
                $ctypes[$m] = 0;
            }
            $ctypes[$m]++;
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


        if ($agent->isRobot()) {

            if (empty($devices['bots'][$agent->robot()])) {
                $devices['bots'][$agent->robot()] = 0;
            }
            $devices['bots'][$agent->robot()]++;

            $robots[] = [
                'agent' => $data[0],
                'type' => $agent->robot()
            ];
        }
    }
    fclose($handle);
}

echo "UAs analyzed {$total}" .PHP_EOL;
echo "Robots " . count($robots) .PHP_EOL;
print_r($devices);

echo "Crawlers {$crawlers}" .PHP_EOL;
print_r($ctypes);
