<?php

namespace App\Http\Controllers\Scripts;

use App\Http\Controllers\Controller;
use App\Look;
use App\Models\Enums\Gender;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Mockery\CountValidator\Exception;
use Validator;

class RegenerateImagesController extends Controller
{
    public function index(Request $request)
    {
        $redirectPath = 'product/list';
        if (!Auth::user()->hasRole('admin') || Auth::user()->id != 33) {
            return redirect($redirectPath)
                ->withErrors(['You do not have permission to regenerate images']);
        }

        if((strpos($_SERVER["HTTP_HOST"], "istyleyou.in") !== false)){
            return redirect($redirectPath)
                ->withErrors(['Images can be regenerated in dev machine only.']);
        }

        $looks =
            Look::where('image', 'like' , '%.png')
                ->where('status_id', '!=', \App\Models\Enums\Status::Deleted)
                ->orderBy('id', 'desc')
                //->limit(10)
                ->get();

        if(count($looks) == 0){
            echo "No looks found";
        }
        else{
            $this->process_looks($looks);
        }

    }

    protected function process_looks($looks){

        $images_folder = $_SERVER['DOCUMENT_ROOT'] . '/images';
        $look_images_folder_name = 'uploadfile1';

        $quality = 95;

        DB::connection()->enableQueryLog();

        $looks_converted = [];

        foreach($looks as $look){
            $current_image_path =  $images_folder . '/' . $look->image;
            $current_image_name = str_replace($look_images_folder_name . "/", "", $look->image);

            $info = pathinfo($current_image_name);
            $new_image_name =  $info['filename'] . '.jpg';
            $new_image_path = $images_folder . '/' . $look_images_folder_name . '/' . $new_image_name;

            try {
                if (file_exists($current_image_path)) {
                    if ($this->convert_to_jpg($current_image_path, $new_image_path, $quality)) {
                        if (file_exists($new_image_path)) {
                            $look->image = $look_images_folder_name . '/' . $new_image_name;
                            $look->save();
                            $looks_converted[] = [$look->id, $look->image];
                            echo "<br />" . $look->id . " {$current_image_name} converted successfully to " . $new_image_name;
                        } else {
                            echo "<br />" . $look->id . " new converted image not found at " . $new_image_path;
                        }
                    } else {
                        echo "<br />" . $look->id . " error in image conversion from {$current_image_path} to " . $new_image_path;
                    }
                } else {
                    echo "<br />" . $look->id . " old image not found at " . $current_image_path;
                }
            }
            catch(\Exception $e){
                echo "<br />" . $e->getTraceAsString();
            }
        }

        $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/images-converted.csv', 'a');

        foreach ($looks_converted as $fields) {
            fputcsv($fp, $fields);
        }

        fclose($fp);

    }

    protected function convert_to_jpg($current_image_path, $new_image_path, $quality){
        try{
            $image = imagecreatefrompng($current_image_path);
            imagejpeg($image, $new_image_path, $quality);
            imagedestroy($image);
            return true;
        }
        catch(Exception $e){
            echo "<br />" . $e->getTraceAsString();
        }
        return false;
    }

}
