<?php
namespace App\Models\Enums;
class BookingStatus{
    const Confirm = "1";
    const Pending = "2";
    const Canceled_by_stylist = "3";
    const Canceled_by_client = "4";
    const Conducted = "5";
    const Missed = "6";
}

