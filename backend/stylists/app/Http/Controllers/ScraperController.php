<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use App\Http\Mapper\ScraperMapper;

class ScraperController extends Controller
{

    protected $start = 0;

    public function index(Request $request, $action, $id = null)
    {
        $method = strtolower($request->method()) . strtoupper(substr($action, 0, 1)) . substr($action, 1);
        if ($id) {
            $this->resource_id = $id;
        }
        return $this->$method($request);
    }

    public function getFetchLatest()
    {

        if(!$fileObj = fopen(env('LOG_FILE_BASE_PATH').'scraper_log.txt', 'a')){
            return false;
        }
        $scraperMapperObj = new ScraperMapper();

        $spiders = $scraperMapperObj->getSpiders();

        fwrite($fileObj, PHP_EOL.'Initializing... '.PHP_EOL.PHP_EOL);

        foreach ($spiders as $spider) {
            fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Started fetching data for '. $spider->name.PHP_EOL);

            $url = env('SCRAPER_DASH') . 'api/jobs/list.json?project=' . $spider->project->id . '&spider=' . $spider->name . '&state=finished&count=1';
            $latest_finished_job = json_decode($scraperMapperObj->getContent($url));

            if (empty($latest_finished_job) || $latest_finished_job->status != 'ok' || empty($latest_finished_job->jobs)) {
                continue;
            }

            $scraped_items = $latest_finished_job->jobs[0]->items_scraped;
            $job_id = $latest_finished_job->jobs[0]->id;

            $response = $scraperMapperObj->noJobExists($job_id);
            if ($response == true) {
                $job_id_with_dash = preg_replace('/\//', '-', $job_id);
                $file_name = $spider->name . '-' . $job_id_with_dash . '.jsonlines';
                $new_url = env('SCRAPER_STORAGE') . 'items/' . $job_id . '?meta=_key';
                $write_json_lines = $scraperMapperObj->getContent($new_url, $file_name);

                if ($write_json_lines['status'] == false) {
                    fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Error fetching data for '. $spider->name.PHP_EOL);
                    continue;
                }

                $data = array(
                    'id' => $job_id,
                    'spider_id' => $spider->id,
                    'items_scraped' => $scraped_items,
                    'items_file_path' => $write_json_lines['status'] == true ? $write_json_lines['file_path'] : '',
                    'items_file_name' => $file_name,
                    'import_completed' => false,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $result = $scraperMapperObj->updateJob($data);
                if ($result == false) {
                    fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Error updating job '. $job_id.' of '.$spider->name.PHP_EOL);
                    continue;
                }
            }
            fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Fetch completed for '. $spider->name.PHP_EOL);
        }
        fwrite($fileObj, PHP_EOL.'Complete '.PHP_EOL);

        fclose($fileObj);
        return true;
    }

    public function getImport()
    {
        if(!$fileObj = fopen(env('LOG_FILE_BASE_PATH').'import_log.txt', 'a')){
            return false;
        }
        $scraperMapperObj = new ScraperMapper();

        $jobs = $scraperMapperObj->getIncompleteJobs();

        fwrite($fileObj, PHP_EOL.'Initializing import... '.PHP_EOL.PHP_EOL);

        foreach ($jobs as $job) {
            fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Import started for job '. $job->id.PHP_EOL);

            $import = $scraperMapperObj->getImportByJobId($job->id);
            if (count($import) == 0) {
                $import = $scraperMapperObj->createImport($job->id);
                if ($import == false) {
                    fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Error creating import for job '. $job->id.PHP_EOL);
                    continue;
                }
            }

            if ($job->spider_id == 13) {
                $result = $scraperMapperObj->fetchAndSaveNicobarProducts($job, $import, $fileObj);
            } else {
                $result = $scraperMapperObj->fetchAndSaveProducts($job, $import, $fileObj);
            }
            if($result){
                $scraperMapperObj->updateJobStatus($job->id);
                fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Import complete for job '. $job->id.PHP_EOL);
            }
        }
        fwrite($fileObj, PHP_EOL.'Successfully imported all items to merchant products'.PHP_EOL);

        fclose($fileObj);
        return true;
    }

    public function getFetchNicobar()
    {
        if(!$fileObj = fopen(env('LOG_FILE_BASE_PATH').'scraper_log.txt', 'a')){
            return false;
        }
        $scraperMapperObj = new ScraperMapper();

        $spiders = $scraperMapperObj->getSpiders();

        fwrite($fileObj, PHP_EOL.'Initializing... '.PHP_EOL.PHP_EOL);

        foreach ($spiders as $spider) {
            fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Started fetching data for '. $spider->name.PHP_EOL);

            $url = env('NICOBAR_API');

                $job_id_with_dash = preg_replace('/\//', '-', '46');
                $file_name = $spider->name . '-' . $job_id_with_dash. '-'.strtotime(date("Y-m-d H:i:s")) . '.txt';
                $write_json_lines = $scraperMapperObj->getContent($url, $file_name);

                if ($write_json_lines['status'] == false) {
                    fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Error fetching data for '. $spider->name.PHP_EOL);
                    continue;
                }

                $data = array(
                    'id' => $spider->merchant_id . '/'.strtotime(date("Y-m-d H:i:s")),
                    'spider_id' => $spider->id,
                    'items_scraped' => 10000,
                    'items_file_path' => $write_json_lines['status'] == true ? $write_json_lines['file_path'] : '',
                    'items_file_name' => $file_name,
                    'import_completed' => false,
                    'created_at' => date('Y-m-d H:i:s'),
                );
                $result = $scraperMapperObj->updateJob($data);
                if ($result == false) {
                    fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Error updating job 46 of '.$spider->name.PHP_EOL);
                    continue;
                }
            fwrite($fileObj, date("Y-m-d H:i:s"). ' ' .'Fetch completed for '. $spider->name.PHP_EOL);
        }
        fwrite($fileObj, PHP_EOL.'Complete '.PHP_EOL);

        fclose($fileObj);
        return true;
    }
}
