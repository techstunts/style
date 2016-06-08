<?php
namespace App\Models\Enums;

class ProductError
{
    const ERROR_IMPORT_INSERT = "1";
    const REJECTED_PRODUCTS = "2";
    const ERROR_IMPORT_UPDATE = "3";
    const PRODUCT_STATUS_WRONG = "4";
    const PRODUCT_NOT_FOUND = "5";
    const IMPORT_VALIDATION_FAIL = "6";
}