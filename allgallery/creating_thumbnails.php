<?php
/**
    * Class for creating thumbnails
    * 
    * @author Ildar Shaymordanov
    * @email  phpmrtgadmin@user.sourceforge.net
    * @url    http://phpmrtgadmin.sourceforge.net
*/

define('THUMBNAIL_METHOD_SCALE_MAX', 0);
define('THUMBNAIL_METHOD_SCALE_MIN', 1);
define('THUMBNAIL_METHOD_CROP',      2);
define('THUMBNAIL_ALIGN_CENTER', 0);
define('THUMBNAIL_ALIGN_LEFT',   -1);
define('THUMBNAIL_ALIGN_RIGHT',  +1);
define('THUMBNAIL_ALIGN_TOP',    -1);
define('THUMBNAIL_ALIGN_BOTTOM', +1);

class Thumbnail
{
    var $option;
    var $notprocessed=false;
    
    function __construct($option) {
        $this->option=$option;
    }
    
    function createdImg($array_img,$article_id) {
        foreach($array_img as $key=>$val) {
           $name_file = basename($val->src); 
           $not_url = ini_get('allow_url_fopen');
           if(empty($not_url)) {
               $fix_path_one = preg_replace("/http:\/\/(.*?)\//i","",$val->src);
               $input = $this->option["path_to_full_img"]."/".$fix_path_one;
           }
           else if(substr($val->src,0,4)=="http") {
                $input = $val->src;    
           } else {
               if(substr($val->src,0,1)=="/") {
                    $input = $this->option["path_to_full_img"].$val->src;
               } else if(substr($val->src,0,2)=="..") {
                    $fix_path_one = str_replace("..","",$val->src);
                    $input = $this->option["path_to_full_img"].$fix_path_one;
               } else {
                    $input = $this->option["path_to_full_img"]."/".$val->src;
               }
           }
           $name_file_time = $this->option["path_to_small_img"].$article_id."_".$name_file;
           
           
           if($this->option["use_risaze_img"]=="yes") {
               if(Thumbnail::output($input, $name_file_time, $this->option, $val)) {
                   $array_img[$key]->created=$this->option["path_to_small_url"].$article_id."_".$name_file;
               }
               $array_img[$key]->notprocessed=$this->notprocessed;
           } else {
               $array_img[$key]->default_height=$this->option["height"];
               $array_img[$key]->default_width=$this->option["width"];
               $array_img[$key]->notprocessed=true;
           }
        }
        return $array_img;
    }
    
    /**
    * The method for creating images
    * 
    * @param mixed $input  string    path to the image
    * @return              resource
    */
    function imageCreate($input) {
        $type =  strtolower(substr(basename($input),-3,3));
        switch ($type) {
        case "jpg":
            return @imagecreatefromjpeg($input); 
            break;
        case "peg":
            return @imagecreatefromjpeg($input); 
            break;
        case "png":
            return @imagecreatefrompng($input); 
            break;
        case "gif":
            return @imagecreatefromgif($input);
            break;
        }

        return false;
    }

    /**
    * A method for recording images in a file.
    * 
    * @param mixed $input    srting  the path to the image you want to compress
    * @param mixed $output   string  path where to save the image
    * @param mixed $options  array   option plug
    * @param mixed $val      object  information about the picture  
    * @return                bool
    */
    function output($input, $output=null, $options=array(), $val=array()) {
        if(!is_file($output)) {
            $renderImage = $this->render($input, $options, $val);
            if (!$renderImage) {
                return false;
            }

            $type = isset($options['type']) ? $options['type'] : IMAGETYPE_JPEG; 
            
            switch ($type) {
                case IMAGETYPE_PNG:
                    $result = empty($output) ? imagepng($renderImage) : imagepng($renderImage, $output);
                    break;
                case IMAGETYPE_JPEG:
                    $result = empty($output) ? imagejpeg($renderImage) : imagejpeg($renderImage, $output);
                    break;
                default:
                    user_error('Image type ' . $content_type . ' not supported by PHP', E_USER_NOTICE);
                    return false;
            }
            imagedestroy($renderImage);
        } else {
            $this->notprocessed=true;
        }
        return true;
    }
    
    /**
    * The method for creating an image
    * 
    * @param mixed $input     srting  the path to the image you want to compress
    * @param mixed $options   array   option plug
    * @param mixed $val       object  information about the picture  
    * @return                 resource
    */
    function render($input, $options=array(), $val=null) {
        $sourceImage = $this->imageCreate($input);  
        if ( ! is_resource($sourceImage) ) {
            return false;
        }
        $sourceWidth  = imagesx($sourceImage); 
        $sourceHeight = imagesy($sourceImage);
        
        if($this->test_input($val,$sourceWidth,$sourceHeight,$options,$sourceWidth,$sourceHeight)) {
            $this->notprocessed=false;
            return false;
        } 
            $this->notprocessed=true;
            
        if(!empty($val->width)) {
            $options['width'] = $val->width;
        }
        
        if(!empty($val->height)) {
            $options['height'] = $val->height;
        }

        if ( $options['method'] =="THUMBNAIL_METHOD_CROP" ) {
            $width  = $W = $options['width'];
            $height = $H = $options['height'];

            if(empty($W)) {
                $width  = $W  = $options['height'];
            }
            if(empty($H)) {
                $height = $H = $options['width'];
            }       

            $Y = $this->_coord($options['valign'], $sourceHeight, $H);
            $X = $this->_coord($options['halign'], $sourceWidth,  $W);
        } else {
            $X = 0;
            $Y = 0;
            $W = $sourceWidth;
            $H = $sourceHeight;
            
                $width  = $options['width'];
                $height = $options['height'];

                if ( $options['method'] == "THUMBNAIL_METHOD_SCALE_MIN" ) {
                    if(empty($width)) {
                        $width = $options['height'];
                    }
                    
                    if(empty($height)) {
                        $height = $options['width'];
                    }

                    $Ww = $W / $width;
                    $Hh = $H / $height;
                    
                    if ( $Ww > $Hh ) {
                        $W = floor($width * $Hh);
                        $X = $this->_coord($options['halign'], $sourceWidth,  $W);
                    } else {
                        $H = floor($height * $Ww);
                        $Y = $this->_coord($options['valign'], $sourceHeight, $H);
                    }
                } else {
                    if(empty($width)) {
                        $width = $options['height'];
                    }
                    if(empty($height)) {
                        $height = $options['width'];
                    }

                    if ( $H > $W ) {
                        $width  = floor($height / $H * $W);
                    } else {
                        $height = floor($width / $W * $H);
                    }
                }
        }


        if($options["method"] == "THUMBNAIL_METHOD_SCALE_MIN_AGEENT") {
            $height = empty($val->height) ? $options['height'] : $val->height;
            $width = empty($val->width) ? $options['width'] : $val->width;
            if(empty($width)) {
                $width = $options['height'];
            }
            
            if(empty($height)) {
                $height = $options['width'];
            }
        }
        
        if ( function_exists('imagecreatetruecolor') ) { 
            $targetImage = imagecreatetruecolor($width, $height);
        } else {
            $targetImage = imagecreate($width, $height);
        }

         if ( ! is_resource($targetImage) ) {
            user_error('Cannot initialize new GD image stream', E_USER_NOTICE);
            return false;
        } 
        
        if ( $options['method'] == THUMBNAIL_METHOD_CROP ) {
            $result = imagecopy($targetImage, $sourceImage, 0, 0, $X, $Y, $W, $H);
        } elseif ( function_exists('imagecopyresampled') ) {
            $result = imagecopyresampled($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
        } else {
            $result = imagecopyresized($targetImage, $sourceImage, 0, 0, $X, $Y, $width, $height, $W, $H);
        }
        
        if ( ! $result ) {
            user_error('Cannot resize image', E_USER_NOTICE);
            return false;
        }
        
        imagedestroy($sourceImage);
        return $targetImage;
    }

    /**
    * The method for checking options
    * 
    * @param mixed $val           object  information about the picture
    * @param mixed $sourceWidth   int     actual width of the image
    * @param mixed $sourceHeight  int     actual height of the image
    * @param mixed $options       array   опции плагина
    * @return                     boo
    */
    function test_input($val, $sourceWidth, $sourceHeight, $options, $sourceWidth="", $sourceHeight="") {
        $percentage = 100 - $options["percentage"];
        $age_width = $sourceWidth/100*$percentage;
        $age_height = $sourceHeight/100*$percentage;
        
        if(!empty($val->width)) {
            if($age_width>(int)$val->width) {
                $time="1";
            }
        }

        if(!empty($val->height)) {
            if($age_height>(int)$val->height) {
                $time="1";
            }
        }

        if(empty($val->width) && empty($val->height)) {
            $time="";
            if(!empty($options["width"]) || !empty($options["height"])) {
                $time="1";
            }
        }

        if(empty($time)) { 
                return true;
        } 
    }

    /**
    * The method for calculating the coordinates
    * 
    * @param mixed $align int
    * @param mixed $param int
    * @param mixed $src   int
    * @return             int
    */
    function _coord($align, $param, $src) {
        if ( $align < THUMBNAIL_ALIGN_CENTER ) {
            $result = 0;
        } elseif ( $align > THUMBNAIL_ALIGN_CENTER ) {
            $result = $param - $src;
        } else {
            $result = ($param - $src) >> 1;
        }
        return $result;
    }
    
    function deletecreatedImg($array_img,$article_id) {
        foreach($array_img as $key=>$val) {
           $name_file = basename($val->src);
           $name_file_time = $this->option["path_to_small_img"].$article_id."_".$name_file;
           if (file_exists($name_file_time)) {
                @chmod($name_file_time, 0777);
                unlink($name_file_time);
           }
        }
    }
}
?>