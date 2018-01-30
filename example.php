<?php
    require_once('allgallery/allgallery.php'); 

    $options = array(
        'base_url'=>base(), // site address
        'gallery' => "highslide", // selected gallery: lightwindow, highslide, slimbox2, jquery lightbox, prettyPhoto, Lightbox, Jsbox, darkbox, fancyzoom, fboxbot.
        'path_to_full_img' => dirname(__FILE__), // absolute path to large images, if link in image shown as relatively
        'path_to_small_img' => dirname(__FILE__)."/img/thumbnail/", // path to the folder where the images will be stored
        'path_to_small_url' => base()."img/thumbnail/", // path to the folder where the images will be stored  
        'width'   => "100", // width default
        'height'  => "100", // height default
        'percentage' => 20, // percentage of positives. If the original width and height of the image 100X100 pixels, the image will be processed unless the specified width or height of the image will be <= 80
        'use_risaze_img' => "yes", // compress images with php or not?
        'include_jquery' => "yes",
        'i_want_img' => "not", // if yes, will only be processed image with the class - class="i_want_img"
        'link_highslide' => "yes",
        'border_tracings' => "not" // show whether or not a frame in the image?
    );
    $all_gallery = new All_gallery($options);
        ob_start();
            include("index.php");     
        $ageent = ob_get_contents();
        ob_end_clean();
    //  $all_gallery->get_clear_content($ageent,"1");  // function to delete image
    echo $all_gallery->get_content($ageent,"1"); 
    

    function base() {
            $full_url='http://'.$_SERVER['HTTP_HOST'].$_SERVER['SCRIPT_NAME'];
            $full_url=str_replace(basename($full_url),"",$full_url);
            return $full_url;
    }
?>