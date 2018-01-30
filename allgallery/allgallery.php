<?php
/**
 * JoomThumbnail -  all gallery in one plugin.
 *
 * @version 2.0
 * @author Dmitriy Kupriyanov (ageent.ua@gmail.com)
 * @copyright (C) 2010 by Dmitriy Kupriyanov (http://ageent.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/
 
include_once("simple_html_dom.php");
include_once("librarygallery.php");
include_once("creating_thumbnails.php");

class All_gallery {
    var $option=array();
    
    function __construct($option) {
        $options_default = array(
            'base_url'=>"",
            'gallery' => "highslide",
            'path_to_full_img' => '',
            'path_to_small_img' => "",
            'path_to_small_url' => "",
            'width'   => "",
            'height'  => "",
            'method'  => "THUMBNAIL_METHOD_SCALE_MIN",
            'halign'  => "HUMBNAIL_ALIGN_CENTER",
            'valign'  => "HUMBNAIL_ALIGN_CENTER",
            'percentage' => 20,
            'use_risaze_img' => "yes",
            'include_jquery' => "yes",
            'i_want_img' => "not",
            'link_highslide' => "yes",
            'border_tracings' => "not"
        );
        foreach($options_default as $key=>$val) {
            if(!empty($option[$key])) {
                $options_default[$key]=$option[$key];
            } 
        }
        $this->option=$options_default;
    }
    
    function get_content($article,$id) {
        
        $variousGall=$this->various_galleries($article);
        $this->option["gallery"]=empty($variousGall[0])?$this->option["gallery"]:$variousGall[0];
        $article=$variousGall[1];
        
        $html = new simple_html_dom_not_conflict;
        $html->load($article, true);

        $array_img=array();
        $params=$this->get_gallery($id,true);
        $img_selector=($this->option["i_want_img"]=="yes")?"img.i_want_img":"img";
            
        foreach($html->find($img_selector) as $element)  {
            $time=array();
            $time["src"]=$element->src;
            $time["height"]=$element->height;
            $time["width"]=$element->width;
            $time["alt"]=$element->alt;
            $time["title"]=$element->title;
            $time["created"]='';
            $time["notprocessed"]=false;
            $array_img[]= (object) $time;
        }
        
        $processedimg=new Thumbnail($this->option);
        $array_img=$processedimg->createdImg($array_img,$id);

        foreach($html->find($img_selector) as $key=>$element)  {
            if($array_img[$key]->notprocessed!=true) continue;
            if(!empty($element->class)) {
                if(preg_match("/not_touch/i",$element->class)) continue;
            }
            $target=empty($params["target"])?"":$params["target"];
            $title=empty($element->title)?"":'title="'.$element->title.'"';
            $title=empty($title)?'title="'.$element->alt.'"':$title;
            $author=empty($element->author)?"":'author="'.$element->author.'"';
            $caption=empty($element->caption)?"":'caption="'.$element->caption.'"';
            $caption=empty($caption)?'caption="'.$element->alt.'"':$caption;
            $scr=$element->src;
            $onclick=empty($params["onclick"])?"":$params["onclick"];
            $element->src=empty($array_img[$key]->created)?$element->src:$array_img[$key]->created;
            if(isset($array_img[$key]->default_width) && isset($array_img[$key]->default_height)) {
                $element->height=$array_img[$key]->default_height;
                $element->width=$array_img[$key]->default_width;
            }
            
            if($this->option["border_tracings"]=="yes" || preg_match("/border_tracings/i",$element->class)) {
                $params["before_tag"]='<span class="joomthumbnail_joom">';
                $params["end_tag"]='<span class="joomthumbnail_discript" style="width:'.$element->width.'px"><span class="big_image"></span>'.$element->alt.'</span></span>';
            }
            
            $element->outertext=$params["before_tag"].'<a href="'.$scr.'" '.$onclick.' '.$params["class"].' '.$params["rel"].' '.$title.' '.$author.' '.$caption.' '.$params["target"].'>'.$params["before_tag_img"].$element->outertext.$params["eng_tag_img"].'</a>'.$params["end_tag"];
            $params["before_tag"]=$params["end_tag"]="";
        }
        $html->save();
        if(preg_match('/highslide/i',$this->option["gallery"])) $html='<div class="highslide-gallery">'.$html."</div>";
        return $html;
    }
    
    function various_galleries($article) {
        preg_match_all("/{ageent}(.*?){ageent}/i",$article,  $out, PREG_PATTERN_ORDER);
        $article=preg_replace("/\{ageent\}(.*?)\{ageent\}/i","",$article);
        return  array(array_pop($out[1]),$article);
    }
    
    function get_gallery($id="",$params=false) {
        if(empty($id)) $id=rand(0,100);
        $librarygallery=new LibraryGallery($this->option);
        $result=$librarygallery->selectGallery($id);
        if($params==true) {
            return $result[1];
        }
        return $result[0];
    }
    
    function get_clear_content($article,$id) {
        $html = new simple_html_dom_not_conflict;
        $html->load($article, true);
        $array_img=array();
            foreach($html->find("img") as $key=>$element)  {
                $time=array();
                $time["src"]=$element->src;
                $array_img[]= (object) $time;
            }
            $processedimg=new Thumbnail($this->option);
            $array_img=$processedimg->deletecreatedImg($array_img,$id);
        return $article;
    }
}
?>
