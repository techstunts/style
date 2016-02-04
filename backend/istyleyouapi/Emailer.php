<?php

class Emailer
{

    public function enqueue($email_template_id, $user_id)
    {
        $sql = "SELECT subject, content, from_email_address, to_email_address, reply_to_email_address
                FROM email_templates et
                WHERE et.id = '$email_template_id'";
        $result = mysql_query($sql);

        if (mysql_num_rows($result) == 1) {

            $sql = "INSERT INTO email_queue(email_template_id, status_id, delivery_attempts, created_by, created_at)
                VALUES('$email_template_id', '1', 0, '33', '" . date('Y-m-d H:i:s') . "')";

            mysql_query($sql);
            $lastid = mysql_insert_id();

            if ($lastid) {
                $sql = "INSERT INTO email_queue_parameter VALUES ('$lastid', 'user_id', $user_id)";
                mysql_query($sql);
            }

            $email_job_url = "http://stylist.{$_SERVER['HTTP_HOST']}/job/email/$lastid";
            $ch = curl_init();
            curl_setopt($ch,CURLOPT_URL, $email_job_url);
            curl_setopt($ch, CURLOPT_REFERER, 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
            curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            $output=curl_exec($ch);
            curl_close($ch);
        }
        return '';
    }


}