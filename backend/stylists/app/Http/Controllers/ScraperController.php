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

        $scraperMapperObj = new ScraperMapper();

        $spiders = $scraperMapperObj->getSpiders();

        foreach ($spiders as $spider) {
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
                    continue;
                }
            }
        }
    }

    public function getImport()
    {
        $scraperMapperObj = new ScraperMapper();

        $jobs = $scraperMapperObj->getIncompleteJobs();
        foreach ($jobs as $job) {
            $import = $scraperMapperObj->getImportByJobId($job->id);
            if (count($import) == 0) {
                $import = $scraperMapperObj->createImport($job->id);
                if ($import == false) {
                    continue;
                }
            }

            if($scraperMapperObj->fetchAndSaveProducts($job, $import)){
                $scraperMapperObj->updateJobStatus($job->id);
            }

        }
        return true;
    }
}
