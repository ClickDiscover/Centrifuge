<?php
use Jenssegers\Agent\Agent;


date_default_timezone_set('UTC');

require dirname(__DIR__) . '/vendor/autoload.php';

function select($iterator, $max, $type, $namespace, $prop) {
    $total = [];
    $count = 0;
    $raw = ['f' => 0, 'l' => 0];
    $events = [];

    foreach ($iterator as $file) {
        $name = (string) $file;
        if ($stream = fopen($name, 'r')) {

            $raw['f']++;
            $done = false;
            // Loop through Lines
            while (!feof($stream) && !$done) {
                $raw['l']++;
                $str = stream_get_line($stream, 4096, PHP_EOL);
                $done = (trim($str) === '0');

                $json = json_decode($str, true);
                $type = $json['type'];
                if (isset($type)) {
                    if (empty($events[$type])) {
                        $events[$type] = 0;
                    }
                    $events[$type] += 1;

                    if ($count > $max) {
                        return [$total, $events, $raw];
                    }
                    $count++;


                    if ($json['type'] === $type) {
                        if (isset($json[$namespace]) && isset($json[$namespace][$prop])) {
                            $total[] = $json[$namespace][$prop];
                        }
                    }
                }
            } // End Lines
            fclose($stream);
        }
    } // End files
    return [$total, $events, $raw];
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




$config = array(
    "region" => "us-east-1",
    "version" => "latest",
    "credentials" => [
        "key" => "AKIAITCZFDXHYHEVBGEA",
        "secret" => "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",
    ],
);
$baseDir = 's3://events.flagshippromotions.com/segment-logs/GsDiILK8mG/';
$dir = $baseDir . '1441324800000';


$client = new Aws\S3\S3Client($config);
$client->registerStreamWrapper();
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

$max = 1000;
list($views, $viewEvents, $raw) = select($iterator, $max, 'page', 'context', 'user_agent');
echo 'Views' . PHP_EOL;
print_r($raw);
print_r(count($views));

$iterator->rewind();
list($clicks, $clickEvents, $raw) = select($iterator, $max, 'track', 'context', 'user_agent');
echo 'Clicks' . PHP_EOL;
print_r($raw);
print_r(count($clicks));

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

