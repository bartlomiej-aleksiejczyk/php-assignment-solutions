<?php

$startMemory = memory_get_usage(true);

$filePath = 'dataset/data/CZ.csv';

$femaleCount = 0;
$maleCount = 0;

if (($handle = fopen($filePath, 'rb')) !== false) {
    while (!feof($handle)) {
        $line = fgets($handle);
        if ($line !== false) {
            $data = str_getcsv($line);
            if (isset($data[2])) {
                if ($data[2] === 'F') {
                    $femaleCount++;
                } elseif ($data[2] === 'M') {
                    $maleCount++;
                }
            }
            unset($data);
        }
    }
    fclose($handle);
}

$knownGenderCount = $femaleCount + $maleCount;

if ($knownGenderCount > 0) {
    $femalePercentage = ($femaleCount / $knownGenderCount) * 100;
    $malePercentage = ($maleCount / $knownGenderCount) * 100;
} else {
    $femalePercentage = $malePercentage = 0;
}

$peakMemory = memory_get_peak_usage(true);

echo "Females: $femaleCount (" . number_format($femalePercentage, 2) . "%)\n";
echo "Males: $maleCount (" . number_format($malePercentage, 2) . "%)\n";
echo "Peak Memory Usage: " . ($peakMemory) . " bytes \n";
