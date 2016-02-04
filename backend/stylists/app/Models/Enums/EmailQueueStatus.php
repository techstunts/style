<?php
namespace App\Models\Enums;
class EmailQueueStatus{
    const Waiting = "1";
    const DeliveryAttempted = "2";
    const Delivered = "3";
    const DeliveryFailed = "4";
}

