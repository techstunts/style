<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use Validator;
use App\Collection;

class UploadMapper extends Controller
{
    public function inputValidator($request)
    {
        return Validator::make($request->all(), [
            'entity_type_id' => 'required|in:2,4,5',
            'image' => 'required|image',
        ]);
    }

    public function getEntityTypeName($entity_type_id)
    {
        if (EntityType::COLLECTION == $entity_type_id) {
            return EntityTypeName::COLLECTION;
        }
    }

    public function entityExists($entity_id, $entity_type_id)
    {
        if (EntityType::COLLECTION == $entity_type_id) {
            $entityObj = Collection::where('id', $entity_id)->first();
        }

        if ($entityObj && $entityObj->exists()) {
            return $entityObj;
        } else {
            return false;
        }
    }

    public function saveImage($request, $entity_obj, $entity_name)
    {
        $image_path = env(strtoupper($entity_name) . '_IMAGE_PATH');

        $entity_image_folder_name = strtolower($entity_name) . 's';

        if ($request->file('image')->isValid()) {
            $destinationPath = public_path() . '/' . $image_path;
            $filename = preg_replace('/[^a-zA-Z0-9_.]/', '_', $request->file('image')->getClientOriginalName());
            $request->file('image')->move($destinationPath, $filename);
            $entity_obj->image = $entity_image_folder_name . '/' . $filename;

            $message = '';
            try {
                $entity_obj->save();
                $status = true;
            } catch (\Exception $e) {
                $status = false;
                $message = 'Exception: ' . $e->getMessage();
            }
        } else {
            $status = false;
            $message = 'Invalid image file';
        }

        return array(
            'status' => $status,
            'message' => $message,
        );
    }
}