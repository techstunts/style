<?php
namespace App\Http\Mapper;

use App\Http\Controllers\Controller;
use App\Look;
use App\Models\Enums\EntityType;
use App\Models\Enums\EntityTypeName;
use Validator;
use App\Collection;
use App\Tip;

class UploadMapper extends Controller
{
    public function inputValidator($request)
    {
        return Validator::make($request->all(), [
            'entity_type_id' => 'required|in:2,4,5',
            'image' => 'image',
        ]);
    }

    public function getEntityTypeName($entity_type_id)
    {
        if (EntityType::COLLECTION == $entity_type_id) {
            return EntityTypeName::COLLECTION;
        } elseif (EntityType::TIP == $entity_type_id) {
            return EntityTypeName::TIP;
        } elseif (EntityType::LOOK == $entity_type_id) {
            return EntityTypeName::LOOK;
        }
    }

    public function entityExists($entity_id, $entity_type_id)
    {
        if (EntityType::COLLECTION == $entity_type_id) {
            $entityObj = Collection::where('id', $entity_id)->first();
        } elseif (EntityType::TIP == $entity_type_id) {
            $entityObj = Tip::where('id', $entity_id)->first();
        } elseif (EntityType::LOOK == $entity_type_id) {
            $entityObj = Look::where('id', $entity_id)->first();
        }

        if ($entityObj && $entityObj->exists()) {
            return $entityObj;
        } else {
            return false;
        }
    }

    public function moveImageInFolder($request)
    {
        $entity_type_id = $request->input('entity_type_id');
        $entity_name = $this->getEntityTypeName($entity_type_id);

        $image_path = env(strtoupper($entity_name) . '_IMAGE_PATH');

        $destinationPath = public_path() . '/' . $image_path;
        $filename = preg_replace('/[^a-zA-Z0-9_.]/', '_', $request->file('image')->getClientOriginalName());
        $request->file('image')->move($destinationPath, $filename);
        return $filename;
    }

    public function getImageFolderName($entity_name)
    {
        if (EntityTypeName::COLLECTION == $entity_name) {
            $entity_image_folder_name = strtolower(EntityTypeName::COLLECTION) . 's';
//        } elseif (EntityTypeName::LOOK == $entity_name) {
//            $entity_image_folder_name = 'uploadfile1';
        } else {
            $entity_image_folder_name = strtolower($entity_name);
        }
        return $entity_image_folder_name;
    }

    public function saveImage($request, $entity_obj, $entity_name)
    {
        if ($request->file('image')->isValid()) {
            $entity_obj->image = $this->moveImageInFolder($request, $entity_name);
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