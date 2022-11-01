<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Compolomus\Sudoku\Sudoku;

$time_start = microtime(true);

$num = 9;

$new = new Sudoku($num);

$all = $new->generate();

$colors = [
    1 => 'Red',
    'Orange',
    'Yellow',
    'Green',
    'Cyan',
    'Blue',
    'Purple',
    'Silver',
    'DarkKhaki'
];

#echo '<pre>' . print_r($all, true) . '</pre>';
echo '<style>
    table, tr, td {
        border: 1px solid;
        text-align: center
    }
    table {
        width: 20%;
    }
    tr, td {
        width: 10px;
    }
</style>';

echo '<table>' . PHP_EOL;

$x = 1;
while ($x <= $num ** 2) {
    if ($x === 1) {
        echo '<tr>' . PHP_EOL;
    }
    echo '<td style="background-color: ' . $colors[$all[$x]] . '">' . $all[$x] . '</td>';

    if ($x % $num === 0) {
        echo '</tr><tr>' . PHP_EOL;
    }
    $x++;
}
echo '</table>' . PHP_EOL;

echo '<div>Generation  ' . round(microtime(true) - $time_start, 4) . ' seconds</div>';
