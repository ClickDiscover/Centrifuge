<?php
use Jenssegers\Agent\Agent;

date_default_timezone_set('UTC');

require dirname(__DIR__) . '/vendor/autoload.php';





$config = array(
    "region" => "us-east-1",
    "version" => "latest",
    "credentials" => [
        "key" => "AKIAITCZFDXHYHEVBGEA",
        "secret" => "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",
    ],
);
$baseDir = 's3://events.flagshippromotions.com/segment-logs/GsDiILK8mG/';
// $dir = $baseDir . '1441324800000'; // Yesterday
// $dir = $baseDir . '1441238400000'; // 2 Days ago
// $dir = $baseDir . '1441411200000'; // Today
$dir = $baseDir; // All
echo "Starting for " . $dir . PHP_EOL;
$client = new Aws\S3\S3Client($config);
$client->registerStreamWrapper();

function stream($iterator) {

    foreach ($iterator as $file) {
        $name = (string) $file;
        if ($stream = fopen($name, 'r')) {

            $done = false;
            // Loop through Lines
            while (!feof($stream) && !$done) {
                $str = stream_get_line($stream, 4096, PHP_EOL);
                $done = (trim($str) === '0');

                yield json_decode($str, true);
            } // End Lines
            fclose($stream);
        }
    } // End files
}



function select($stream, $max, $typeSelect, $namespace, $prop) {
    $total = [];
    $count = 0;
    $events = [];

    foreach ($stream as $json) {
        $type = $json['type'];
        if (isset($type)) {
            if (empty($events[$type])) {
                $events[$type] = 0;
            }
            $events[$type] += 1;

            if ($count > $max) {
                return [$total, $events];
            }
            $count++;

            if ($json['type'] === $typeSelect) {
                if (isset($json[$namespace]) && isset($json[$namespace][$prop])) {
                    $total[] = $json[$namespace][$prop];
                }
            }
        }
    } // End files
    return [$total, $events];
}


function userAgentCounts($total, $dkey) {
    $devices = [];
    foreach ($total as $t) {

        $agent = new Agent();
        $agent->setUserAgent($t);
        $d = [
            'browser' => $agent->browser(),
            'device' => $agent->device(),
            'platform' => $agent->platform(),
        ];

        if (empty($devices[$d[$dkey]])) {
            $devices[$d[$dkey]] = 0;
        }
        $devices[$d[$dkey]]++;


        if ($agent->isRobot()) {
            $d['robot'] = $agent->robot();
        }
    }
    return $devices;
}



$max = 10000;

$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
// list($views, $viewEvents) = select(stream($iterator), $max, 'page', 'context', 'utm_campaign');
foreach (stream($iterator) as $v) {
    if ($v['type'] !== 'page' && $v['type'] !== 'identify') {
        print_r($v);
    }
}

// $max = 10000000;
// $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));


// list($views, $viewEvents) = select(stream($iterator), $max, 'page', 'properties', 'website');
// echo 'Views' . PHP_EOL;
// // print_r($raw);
// // print_r($viewEvents);
// $landerViews = array_count_values($views);


// $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
// list($clicks, $clickEvents) = select(stream($iterator), $max, 'track', 'properties', 'website');
// echo 'Clicks' . PHP_EOL;
// $landerClicks = array_count_values($clicks);

// foreach (array_keys($landerViews) as $id) {
//     $v = $landerViews[$id];
//     $c = isset($landerClicks[$id]) ? $landerClicks[$id] : 0;
//     $ctr = ($v == 0) ? 0 : ($c / (double) $v);

//     if ($c != 0) {
//         echo "ID: " . $id . " Views: " . $v . " Clicks " . $c . " CTR " . $ctr . PHP_EOL;
//     }
// }

// print_r($viewEvents);
// print_r(userAgentCounts($views, 'device'));
// print_r(userAgentCounts($views, 'browser'));
// print_r(userAgentCounts($views, 'platform'));

// print_r($clickEvents);
// print_r(userAgentCounts($clicks, 'device'));
// print_r(userAgentCounts($clicks, 'browser'));
// print_r(userAgentCounts($clicks, 'platform'));



// echo 'totaL ' . count($total). PHP_EOL;
// echo 'raw ' . print_r($raw, 1) . PHP_EOL;
// print_r($events);
// sort($total);
// print_r([
//     'Min' => min($total),
//     'Max' => max($total),
//     'mean' => array_sum($total) / (float) count($total),
//     'median' => $total[count($total) / 2],
// ]);

