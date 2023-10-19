<?php

// returns a new curl multi handle - generates 1 thread
$mch = curl_multi_init();

$url = "http://localhost/multithread/worker.php";

// declare channel 1 to worker - generates 1 thread
$ch1 = curl_init($url);
curl_setopt($ch1, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch1, CURLOPT_POSTFIELDS, ["script" => '
    for ($i = 0; $i <= 20; $i++) {
        sleep(1);
        file_put_contents("a.txt", "arquivo a ".$i);
    }
    echo "terminou de executar o a\n";
']);

// declare channel 2 to worker - generates 1 thread
$ch2 = curl_init($url);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_POSTFIELDS, ["script" => '
    for ($i = 0; $i <= 20; $i++) {
        sleep(1);
        file_put_contents("b.txt", "arquivo b ".$i);
    }
    echo "terminou de executar o b\n";
']);



// set multihandle for channel 1
curl_multi_add_handle($mch, $ch1);
curl_multi_exec($mch, $active);

// set multihandle for channel 2
curl_multi_add_handle($mch, $ch2);
curl_multi_exec($mch, $active);

do {
    curl_multi_exec($mch, $active);
} while ($active > 0);

$result1 = curl_multi_getcontent($ch1);
$result2 = curl_multi_getcontent($ch2);

echo $result1;
echo $result2;
