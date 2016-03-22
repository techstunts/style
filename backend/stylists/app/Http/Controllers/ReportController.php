<?php
/**
 * Created by IntelliJ IDEA.
 * User: hrishikesh.mishra
 * Date: 07/03/16
 * Time: 9:11 PM
 */

namespace App\Http\Controllers;


use App\Report\Reporter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Input;

class ReportController extends Controller {

    private $reporter;

    /**
     * ReportController constructor.
     */
    public function __construct(Reporter $reporter) {
        $this->reporter = $reporter;
    }

    public function index(Request $request, $report_id){
        if(!Auth::user()->hasRole('admin')){
            return redirect('look/list')->withError('Report access denied!');
        }
        $reportEntity = $this->reporter->getReportEntity($report_id);
        return view('report.index', array('reportEntity' => $reportEntity));
    }


    public function query(Request $request, $report_id){
        $report = $this->reporter->report($report_id,  $request->all());
        return response()->json($report);
    }
}
