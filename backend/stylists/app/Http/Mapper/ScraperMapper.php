<?php
namespace App\Http\Mapper;

use App\Models\Scraper\Jobs;
use App\Models\Scraper\Spiders;
class ScraperMapper
{
    protected $api_key = 'ae67de7e514046eb9995ecd497181dd1:';
    protected $base_file_path = 'C:\Scraper\\';
    protected $with_array = ['merchant', 'project'];

    public function getContent($url, $file_name = ''){

        $headers = array(
            "Accept: application/x-jsonlines",
        );

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_USERPWD, $this->api_key);
        curl_setopt($ch, CURLOPT_HTTPGET, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        if (!empty($file_name)){
            $file_name_with_path = $this->base_file_path.$file_name.'.jsonlines';
            $fp = fopen($file_name_with_path, "w");
            if ($fp == false) {
                return array('status' => false);
            }
            curl_setopt($ch, CURLOPT_FILE, $fp);
            $result = curl_exec($ch);

            if (!fwrite($fp, $result)) {
                return array('status' => false);
            }
            curl_close($ch);
            fclose($fp);

            return array('status' => true, 'file_path' => $this->base_file_path);
        }

        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

    public function getSpiders(){
        return Spiders::with($this->with_array)->get();
    }

    public function noJobExists($job_id){
        $job = Jobs::where('id', $job_id)->first();
        if (empty($job)){
            return true;
        }
        else{
            return false;
        }
    }

    public function updateJob($data){
        if (empty($data)){
            return false;
        }

        if (!Jobs::insert($data)){
            return false;
        }
        return true;
    }
}