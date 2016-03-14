<?php
namespace App\Models\LookTemplates;
class SingleDressHorizontal extends CombineImages
{

    public function createLook($src_image_paths, $look_name){
        $mapImage = imagecreatetruecolor($this->mapWidth, $this->mapHeight);
        $this->bgColor = imagecolorallocate($mapImage, 255, 255, 255);
        imagefill($mapImage, 0, 0, $this->bgColor);

        $images_folder = $_SERVER['DOCUMENT_ROOT'] . '/images/';

        $dress = true;

        /*
         *  PUT SRC IMAGES ON BASE IMAGE
         */
        foreach ($src_image_paths as $index => $src_image_path)
        {
            if (filter_var($src_image_path, FILTER_VALIDATE_URL) === FALSE) {
                $src_image_path = $images_folder . $src_image_path;
            }

            list ($x, $y) = $this->indexToCoords($index, $dress);

            $tileImg = $this->compress_image($src_image_path, 99, $dress);

            $src_width = imagesx($tileImg);
            $src_height = imagesy($tileImg);

            imagecopy($mapImage, $tileImg, $x, $y, 0, 0, $src_width, $src_height);
            imagedestroy($tileImg);

            $dress = false;
        }

        $filename =  $look_name . '-' . uniqid() . '-' . time(). '.jpeg';

        $this->targetImage = "uploadfile1/$filename";

        return imagejpeg($mapImage, $images_folder . $this->targetImage);
    }

    protected function indexToCoords($index, $dress)
    {
        if($dress){
            $x = floor($this->mapWidth * 1 / 8);
            $y = 0;
        }
        else{
            $x = ($index * floor($this->mapWidth * 1 / 16)) + (($index - 1) * floor($this->mapWidth * 1 / 4));
            $y = floor($this->mapHeight * 3 / 4);
        }

        return Array($x, $y);
    }

    protected function compress_image($src, $quality, $dress)
    {
        list($src_width, $src_height) = getimagesize($src);

        if($dress){
            $factor = 3/4;
        }
        else{
            $factor = 1/4;
        }
        $dst_width =  floor($this->mapWidth * $factor);
        $max_height = floor($this->mapHeight * $factor);
        $ideal_height = ($src_height / $src_width) * $dst_width;
        $dst_height = $ideal_height <= $max_height ? $ideal_height : $max_height;


        $info = getimagesize($src);

        $img=imagecreatetruecolor($dst_width, $dst_height);
        imagefill($img, 0, 0, $this->bgColor);

        if ($info['mime'] == 'image/jpeg' || $info['mime'] == 'image/jpg')
        {
            $image = imagecreatefromjpeg($src);
        }
        elseif ($info['mime'] == 'image/gif')
        {
            $image = imagecreatefromgif($src);
        }
        elseif ($info['mime'] == 'image/png')
        {
            $image = imagecreatefrompng($src);
        }

        imagecopyresampled($img, $image, 0, 0, 0, 0, $dst_width, $dst_height ,$src_width, $src_height);
        return $img;
    }

}
