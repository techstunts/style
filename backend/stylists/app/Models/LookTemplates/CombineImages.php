<?php
namespace App\Models\LookTemplates;
abstract class CombineImages
{

    protected $mapWidth = 630;
    protected $mapHeight = 880;
    protected $bgColor;
    public $targetImage;

    abstract public function createLook($src_image_paths, $look_name);
}
?>