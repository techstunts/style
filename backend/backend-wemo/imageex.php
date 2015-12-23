<?php

$img1_path = 'uploadfile/'.$filename4;
$img2_path = 'uploadfile/'.$filename1;
$img3_path = 'uploadfile/'.$filename2;
$img4_path = 'uploadfile/'.$filename3;
$timestamp=date('Y-m-d H:i:s');
/*list($img1_width, $img1_height) = getimagesize($img1_path);
list($img2_width, $img2_height) = getimagesize($img2_path);
list($img3_width, $img3_height) = getimagesize($img3_path);*/
//$merged_width  = $img1_width + $img2_width;

//get highest
//$merged_height = $img1_height >= $img2_height ? $img1_height : $img2_height;

$merged_image = imagecreatetruecolor(630, 880);
$white = imagecolorallocate($merged_image, 255, 255, 255);
imagefill($merged_image, 0, 0, $white);
$im = imagecreatetruecolor(128, 128);
imagealphablending($merged_image, false);
imagesavealpha($merged_image, true);
/*
$img1 = imagecreatefromjpeg($img1_path);
$img2 = imagecreatefromjpeg($img2_path);
$img3=imagecreatefromjpeg($img3_path);
imagecopyresampled($im, $img3, 0, 0, 0, 0, 128,128 ,$img3_width, $img3_height);
$im11=imagejpeg($im,"uploadfile1/demo2.jpeg",99);*/
$filename =  uniqid().time().".jpeg";
$image1_path=compress_image($img1_path,"uploadfile/$filename",99);
$filename =  uniqid().time().".jpeg";
$image2_path=compress_image($img2_path,"uploadfile/$filename",99);
$filename =  uniqid().time().".jpeg";
$image3_path=compress_image($img3_path,"uploadfile/$filename",99);
$filename =  uniqid().time().".jpeg";
$image4_path=compress_image($img4_path,"uploadfile/$filename",99);
list($image1_width, $image1_height) = getimagesize($image1_path);
list($image2_width, $image2_height) = getimagesize($image2_path);
list($image3_width, $image3_height) = getimagesize($image3_path);
list($image4_width, $image4_height) = getimagesize($image4_path);
$image1_resource=resource_image($image1_path,$im);
$image2_resource=resource_image($image2_path,$im);
$image3_resource=resource_image($image3_path,$im);
$image4_resource=resource_image($image4_path,$im);

imagecopy($merged_image, $image1_resource, 40, 50, 0, 0, $image1_width, $image1_height);
//place at right side of $img1
imagecopy($merged_image, $image2_resource,355, 50, 0, 0, $image2_width, $image2_height);
//place below the image1
imagecopy($merged_image, $image3_resource, 40, 470, 0, 0, $image3_width, $image3_height);
//place below the image2
imagecopy($merged_image, $image4_resource, 355, 470, 0, 0, $image4_width, $image4_height);
//save file or output to broswer
$SAVE_AS_FILE = TRUE;
if( $SAVE_AS_FILE ){
	$filename =  uniqid().time().".jpeg";
	
    $mergedimage=imagejpeg($merged_image,"uploadfile1/$filename");
	
	 
$sql = "INSERT INTO looks (look_image,look_name,look_description,product_id1,product_id2,product_id3,product_id4,body_type,budget,age,occasion,gender,stylish_id,date,lookprice) VALUES ('uploadfile1/$filename','$lookname','$lookdescription','$productid1','$productid2','$productid3','$productid4','$body_type','$budget','$age','$occasion','$gender','$stylishid','$timestamp','$lookprice')";

mysql_query($sql,$conn);
$looklastid=mysql_insert_id();


$sql="INSERT INTO looks_products(look_id,product_id) VALUES('$looklastid','$productid1')";
mysql_query($sql);
$sql="INSERT INTO looks_products(look_id,product_id) VALUES('$looklastid','$productid2')";
mysql_query($sql);
$sql="INSERT INTO looks_products(look_id,product_id) VALUES('$looklastid','$productid3')";
mysql_query($sql);
$sql="INSERT INTO looks_products(look_id,product_id) VALUES('$looklastid','$productid4')";
mysql_query($sql);

$lastid1=mysql_insert_id();

}else{
    header('Content-Type: image/jpeg');
    imagejpeg($merged_image);
}

//release memory
imagedestroy($merged_image);

//------------function of Resource Image--------

function resource_image($src,$image)
{
$info = getimagesize($src);
  
    if ($info['mime'] == 'image/jpeg') 
    {
        $image = imagecreatefromjpeg($src);
		return $image;
    }
    elseif ($info['mime'] == 'image/gif') 
    {
        $image = imagecreatefromgif($src);
		return $image;
    }
    elseif ($info['mime'] == 'image/png') 
    {
        $image = imagecreatefrompng($src);
		return $image;
    }
    else
    {
        die('Unknown dsfsasimage file format');
    }
}

//--------function for compress images--------

function compress_image($src, $dest , $quality) 
{
list($src_width, $src_height) = getimagesize($src);
    $info = getimagesize($src);
  
    if ($info['mime'] == 'image/jpeg') 
    {
	$img=imagecreatetruecolor(255, 370);
        $image = imagecreatefromjpeg($src);
		imagecopyresampled($img, $image, 0, 0, 0, 0, 255,370 ,$src_width, $src_height);
		//compress and save file to jpg
    imagejpeg($img, $dest, $quality); 
    //return destination file
    return $dest;
    }
    elseif ($info['mime'] == 'image/gif') 
    {
       $img=imagecreatetruecolor(255, 370);
        $image = imagecreatefromgif($src);
		imagecopyresampled($img, $image, 0, 0, 0, 0, 255,370 ,$src_width, $src_height);
		//compress and save file to jpg
    imagejpeg($img, $dest, $quality);  
    //return destination file
    return $dest;
    }
    elseif ($info['mime'] == 'image/png') 
    {
       $img=imagecreatetruecolor(255, 370);
        $image = imagecreatefrompng($src);
		imagecopyresampled($img, $image, 0, 0, 0, 0, 255,370 ,$src_width, $src_height);
		//compress and save file to jpg
    imagejpeg($img, $dest, $quality);  
    //return destination file
    return $dest;
    }
	 elseif ($info['mime'] == 'image/jpg') 
    {
       $img=imagecreatetruecolor(255, 370);
        $image = imagecreatefromjpeg($src);
		imagecopyresampled($img, $image, 0, 0, 0, 0, 255,370 ,$src_width, $src_height);
		//compress and save file to jpg
    imagejpeg($img, $dest, $quality);  
    //return destination file
    return $dest;
    }
    else
    {
        die('Unknown image file format');
    }
  
    
}

?>
