<?php

namespace App\Repositories;

interface ReportRepositoryInterface
{
    public function driversReport($filter, $range = false, $status = false);
    public function storesReport($filter, $range = false, $status = false);
    public function topOnline($day);
    public function orderMovements($filter, $noTime, $range, $fromTime, $toTime, $by);
    public function storesCharge($from, $to);
    public function storesOrders($from, $to, $status = false);
    public function generalOrdersReport($from, $to, $status = false);
}
