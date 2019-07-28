<?php
declare(strict_types=1);

require_once '../core/include.php';

use ServiceSystem\DemonstrationHelper;

$data               = (new DemonstrationHelper())->getDatabaseCurrentState();
$firstRowData       = $data[0] ?? [];
$summery            = (int) ($firstRowData['sum']           ?? 0);
$countFibonacci     = (int) ($firstRowData['count_fib']     ?? 0);
$countPrimeNumber   = (int) ($firstRowData['count_prime']   ?? 0);
?>
<table border="1">
    <thead>
        <tr>
            <th>Summery</th>
            <th>Fibonacci</th>
            <th>PrimeNumber</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?=$summery?></td>
            <td><?=$countFibonacci?></td>
            <td><?=$countPrimeNumber?></td>
        </tr>
    </tbody>
</table>