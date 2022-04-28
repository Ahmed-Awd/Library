<?php

/**
 * Created by PhpStorm.
 * User: ahmed
 * Date: 4/7/2021
 * Time: 4:45 PM
 */

namespace App\Repositories;

interface PackageCodeRepositoryInterface
{
    public function get($package_id, $type = false, $range = false);
    public function all($type = "all", $range = false, $userType = "all");
    public function store($package_id);
    public function delete($package);
    public function charge($code);
    public function failChargeAttempt();
    public function charging($chargeCode);
    public function chargeBalance($value);
    public function invalidCode($code): bool;
    public function history($range = false, $user = false);
    public function getTotalIngoing($range = false, $user = false);
}
