<?php
namespace App\Models\LookTemplates;
class DoubleClothes extends CombineImages
{

    public function createLook($src_image_paths, $look_name){
        $mapImage = imagecreatetruecolor($this->mapWidth, $this->mapHeight);
        $this->bgColor = imagecolorallocate($mapImage, 255, 255, 255);
        imagefill($mapImage, 0, 0, $this->bgColor);

        $images_folder = $_SERVER['DOCUMENT_ROOT'] . '/images/';

        /*
         *  PUT SRC IMAGES ON BASE IMAGE
         */
        foreach ($src_image_paths as $index => $src_image_path)
        {
            if (filter_var($src_image_path, FILTER_VALIDATE_URL) === FALSE) {
                $src_image_path = $images_folder . $src_image_path;
            }

            list ($x, $y, $w, $h) = $this->indexToCoords($src_image_path, $index);

            $tileImg = $this->compress_image($src_image_path, $w, $h);

            imagecopy($mapImage, $tileImg, $x, $y, 0, 0, $w, $h);
            imagedestroy($tileImg);
        }

        $filename =  $look_name . '-' . uniqid() . '-' . time(). '.jpeg';

        $this->targetImage = "uploadfile1/$filename";

        return imagejpeg($mapImage, $images_folder . $this->targetImage);
    }

    protected $mapCoordinates = array(
                                    0 => [0, 0, 1/2, 13/20],
                                    1 => [1/2, 0, 1/2, 7/20],
                                    2 => [0, 13/20, 1/2, 7/20],
                                    3 => [1/2, 7/20, 1/2, 13/20],
                                );

    protected $img0Coordinates = array();

    protected $img1Coordinates = array();

    protected function indexToCoords($src, $index)
    {

        $x = floor($this->mapCoordinates[$index][0] * $this->mapWidth);
        $y = floor($this->mapCoordinates[$index][1] * $this->mapHeight);

        list($src_width, $src_height) = getimagesize($src);

        $max_width =  floor($this->mapWidth * $this->mapCoordinates[$index][2]);
        $max_height = floor($this->mapHeight * $this->mapCoordinates[$index][3]);
        $ideal_width = ($src_width / $src_height) * $max_height;
        $ideal_height = ($src_height / $src_width) * $max_width;


        $dst_width = $max_width;
        if($ideal_height <= $max_height){
            $dst_height = $ideal_height;
        }
        else{
            $dst_height = $max_height;
        }

        if($index == 1 || $index == 2){
            $dst_height = $max_height;
            if($ideal_width <= $max_width){
                $dst_width = $ideal_width;
            }
            else{
                $dst_width = $max_width;
            }
        }

        if($index == 0){
            $this->img0Coordinates = [$x, $y, $dst_width, $dst_height];
        }

        if($index == 1){
            $this->img1Coordinates = [$x, $y, $dst_width, $dst_height];
        }

        if($index == 2){
            $x = $this->img0Coordinates[2] - $dst_width;
            $y = $this->img0Coordinates[3];
        }

        if($index == 3){
            $y = $this->img1Coordinates[3];
        }

        return Array($x, $y, $dst_width, $dst_height);
    }

    protected function compress_image($src, $dst_width, $dst_height)
    {
        list($src_width, $src_height) = getimagesize($src);
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
