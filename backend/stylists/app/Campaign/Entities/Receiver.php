<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 05/06/16
 * Time: 6:28 PM
 */

namespace App\Campaign\Entities;

class Receiver
{

    const DEFAULT_CUSTOMER_NAME = "Customer";

    private $name;
    private $email;

    function __construct($email, $name)
    {
        $this->email = $email;
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return (!empty($this->name)?$this->name: self::DEFAULT_CUSTOMER_NAME);
    }
} 