<?php
namespace App;
class CombineImages
{

    protected $mapWidth = 630;
    protected $mapHeight = 880;
    protected $bgColor;
    public $targetImage;

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
            $src_image_path = $images_folder . $src_image_path;

            list ($x, $y) = $this->indexToCoords($index);
            list($tileWidth, $tileHeight) = getimagesize($src_image_path);

            $tileImg = $this->compress_image($src_image_path, 99);

            imagecopy($mapImage, $tileImg, $x, $y, 0, 0, $tileWidth, $tileHeight);
            imagedestroy($tileImg);
        }

        $filename =  $look_name . '-' . uniqid() . '-' . time(). '.jpeg';

        $this->targetImage = "uploadfile1/$filename";

        return imagejpeg($mapImage, $images_folder . $this->targetImage);
    }

    protected function indexToCoords($index)
    {
        $x = ($index % 2) * floor($this->mapWidth / 2);
        $y = floor($index / 2) * floor($this->mapHeight / 2);

        return Array($x, $y);
    }

    protected function compress_image($src, $quality)
    {
        $dst_width = floor($this->mapWidth/2);
        $dst_height = floor($this->mapHeight/2);

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
