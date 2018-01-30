<?php
/**
 * JoomThumbnail -  all gallery in one plugin.
 *
 * @version 2.0
 * @author Dmitriy Kupriyanov (ageent.ua@gmail.com)
 * @copyright (C) 2010 by Dmitriy Kupriyanov (http://ageent.ru)
 * @license GNU/GPL: http://www.gnu.org/copyleft/gpl.html
 **/
 
 /**
 * The Class need for connecting JavaScriipt libraries
 * 
 */
 
 class LibraryGallery {
     
    var $option=array();
    var $jquery="";
    
    function __construct($option) {
        $this->option=$option;
        if($option["include_jquery"]=="yes") {
            $this->jquery='  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>'."\n";
        }
    }
    
    function selectGallery($id="") {
        $selectgallery=preg_replace("/:(.*)/is","",$this->option['gallery']);
        $selectgallery=empty($selectgallery)?"highslide":$selectgallery;
        $result=$this->$selectgallery($id);
        $result[0]=$result[0]."  <!-- Copyright JoomThumbnail  http://ageent.ru -->\n";
        return $result;
    } 
    
    function lightwindow($id) {
        $lightwindow= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/lightwindow/lightwindow.css" type="text/css" />'."\n";
        $lightwindow.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $lightwindow.= '  <script type="text/javascript">var url_home = "'.$this->option["base_url"].'";</script>'."\n";
        $lightwindow.= '  <script type="text/javascript" src="'.$this->option["base_url"].'allgallery/gallery/lightwindow/prototype.js"></script>'."\n";
        $lightwindow.= '  <script type="text/javascript" src="'.$this->option["base_url"].'allgallery/gallery/lightwindow/effects.js"></script>'."\n";
        $lightwindow.= '  <script type="text/javascript" src="'.$this->option["base_url"].'allgallery/gallery/lightwindow/lightwindow.js"></script>'."\n";
        
        $config = array(
                'before_tag' =>  '',
                'end_tag'    =>  '',
                'onclick'    =>  '',
                'class'      =>  'class="lightwindow ageent-ru"',
                'rel'        =>  'rel="Random[Sample Images '.$id.']"',
                'target'     =>  'target="_blank"',
                'before_tag_img' => '',
                'eng_tag_img' => ''
        );
        
        return array($lightwindow,$config);
    }
    
    function highslide($id) {

        if($this->option["link_highslide"]=="yes") {
            $viisible_link = "";
        } else {
            $viisible_link = "hs.showCredits = false;";
        }
        
        $highslide = '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $highslide.= '  <script type="text/javascript" src="'.$this->option["base_url"].'allgallery/gallery/highslide/highslide-with-gallery.js"></script>'."\n";
        $highslide.= '  <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/swfobject/2.1/swfobject.js"></script>'."\n";
        $highslide.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/highslide/highslide.css" type="text/css" />'."\n";

        
        switch ($this->option["gallery"]) {
            case "highslide":
                    $highslide.= "  <script type=\"text/javascript\">
                    hs.graphicsDir = '".$this->option["base_url"]."allgallery/gallery/highslide/graphics/';
                    hs.align = 'center';
                    hs.transitions = ['expand', 'crossfade'];
                    hs.outlineType = 'rounded-white';
                    hs.fadeInOut = true;
                    hs.wrapperClassName = 'draggable-header';
                    $viisible_link
                    hs.addSlideshow({
                        //slideshowGroup: 'group1',
                        interval: 5000,
                        repeat: false,
                        useControls: true,
                        fixedControls: 'fit',
                        overlayOptions: {
                            opacity: .75,
                            position: 'bottom center',
                            hideOnMouseOut: true
                        }
                    });
  </script>\n";
                break;
            case "highslide:gallery":
                    $highslide.= '  <!--[if lt IE 7]>
                    <link rel="stylesheet" type="text/css" href="'.$this->option["base_url"].'allgallery/gallery/highslide/graphics/highslide-ie6.css" />
  <![endif]-->'."\n";
                    $highslide.="  <script type=\"text/javascript\">
                    hs.graphicsDir = '".$this->option["base_url"]."allgallery/gallery/highslide/graphics/';
                    hs.align = 'center';
                    hs.transitions = ['expand', 'crossfade'];
                    hs.fadeInOut = true;
                    hs.dimmingOpacity = 0.8;
                    $viisible_link
                    hs.outlineType = 'rounded-white';
                    hs.captionEval = 'this.thumb.alt';
                    hs.marginBottom = 105; 
                    hs.numberPosition = 'caption';
                    hs.wrapperClassName = 'draggable-header';
                    hs.addSlideshow({
                        interval: 5000,
                        repeat: false,
                        useControls: true,
                        overlayOptions: {
                            className: 'text-controls',
                            position: 'bottom center',
                            relativeTo: 'viewport',
                            offsetY: -60
                        },
                        thumbstrip: {
                            position: 'bottom center',
                            mode: 'horizontal',
                            relativeTo: 'viewport'
                        }
                    });
  </script>\n";
                break;
            case "highslide:black":
                    $highslide.= "  <script type=\"text/javascript\">
                        hs.graphicsDir = '".$this->option["base_url"]."allgallery/gallery/highslide/graphics/';
                        hs.align = 'center';
                        hs.transitions = ['expand', 'crossfade'];
                        hs.outlineType = 'rounded-white';
                        hs.dimmingOpacity = 0.8;
                        hs.wrapperClassName = 'draggable-header';
                        $viisible_link
                        hs.fadeInOut = true;
                        hs.addSlideshow({
                            //slideshowGroup: 'group1',
                            interval: 5000,
                            repeat: false,
                            useControls: true,
                            fixedControls: 'fit',
                            overlayOptions: {
                                opacity: .75,
                                position: 'bottom center',
                                hideOnMouseOut: true
                            }
                        });
  </script>\n";
                break;
            case "highslide:template_black_white":
                    $highslide.= "  <script type=\"text/javascript\">
                        hs.graphicsDir = '".$this->option["base_url"]."allgallery/gallery/highslide/graphics/';
                        hs.align = 'center';
                        hs.transitions = ['expand', 'crossfade'];
                        hs.outlineType = 'glossy-dark';
                        hs.wrapperClassName = 'dark';
                        hs.wrapperClassName = 'draggable-header';
                        $viisible_link
                        hs.fadeInOut = true;
                        if (hs.addSlideshow) hs.addSlideshow({
                            //slideshowGroup: 'group1',
                            interval: 5000,
                            repeat: false,
                            useControls: true,
                            fixedControls: 'fit',
                            overlayOptions: {
                                opacity: .6,
                                position: 'bottom center',
                                hideOnMouseOut: true
                            }
                        });
  </script>\n";
                break;
            case "highslide:template_black_black":
                    $highslide.= "  <script type=\"text/javascript\">
                        hs.graphicsDir = '".$this->option["base_url"]."allgallery/gallery/highslide/graphics/';
                        hs.align = 'center';
                        hs.transitions = ['expand', 'crossfade'];
                        hs.outlineType = 'glossy-dark';
                        hs.wrapperClassName = 'dark';
                        hs.wrapperClassName = 'draggable-header';
                        hs.dimmingOpacity = 0.8;
                        $viisible_link
                        hs.fadeInOut = true;
                        if (hs.addSlideshow) hs.addSlideshow({
                            //slideshowGroup: 'group1',
                            interval: 5000,
                            repeat: false,
                            useControls: true,
                            fixedControls: 'fit',
                            overlayOptions: {
                                opacity: .6,
                                position: 'bottom center',
                                hideOnMouseOut: true
                            }
                        });
  </script>\n";
                break;
        }

        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  'onclick="return hs.expand(this,{captionText: this.getAttribute(\'caption\')})"',
            'class'      =>  'class="highslide ageent-ru"',
            'rel'        =>  '',
            'after_link' => '',
            'end_link'   => '',
            'target'     =>  'target="_blank"',
            'before_tag_img' => '',
            'eng_tag_img' => ''
        );
        return array($highslide,$config);
    }
    
    function slimbox2($id) {
        $slimbox2= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/slimbox2/css/slimbox2.css" type="text/css" />'."\n";
        $slimbox2.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $slimbox2.= $this->jquery;
        $slimbox2.= '  <script type="text/javascript" src="'.$this->option["base_url"].'allgallery/gallery/slimbox2/js/slimbox2.js"></script>'."\n";

        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  '',
            'class'      =>  'class="ageent-ru"',
            'rel'        =>  'rel="lightbox-atomium-'.$id.'"',
            'target'     =>  'target="_blank"',
            'before_tag_img' => '',
            'eng_tag_img' => ''
        );
        return array($slimbox2,$config);
    }
    
    function jquery_lightbox($id) {
        $jquery_lightbox= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/jquery_lightbox/css/jquery.lightbox-0.5.css" type="text/css" />'."\n";
        $jquery_lightbox.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $jquery_lightbox.= $this->jquery;
        $jquery_lightbox.= '  <script type="text/javascript" src="'.$this->option["base_url"].'allgallery/gallery/jquery_lightbox/js/jquery.lightbox-0.5.js"></script>'."\n";
        $jquery_lightbox.= "  <script type=\"text/javascript\">
         jQuery(function($) {
            $('a[rel=\"only_one\"],a[rel=\"lightbox\"]').lightBox({
                overlayOpacity: 0.6,
                imageLoading:  '".$this->option["base_url"]."allgallery/gallery/jquery_lightbox/images/lightbox-ico-loading.gif',
                imageBtnClose: '".$this->option["base_url"]."allgallery/gallery/jquery_lightbox/images/lightbox-btn-close.gif',
                imageBtnPrev:  '".$this->option["base_url"]."allgallery/gallery/jquery_lightbox/images/lightbox-btn-prev.gif',
                imageBtnNext:  '".$this->option["base_url"]."allgallery/gallery/jquery_lightbox/images/lightbox-btn-next.gif'
            });
        });
  </script>\n";

        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  '',
            'class'      =>  'class="ageent-ru"',
            'rel'        =>  'rel="only_one"',
            'target'     =>  'target="_blank"',
            'before_tag_img' => '',
            'eng_tag_img' => ''
        );
        return array($jquery_lightbox,$config);
    }
    
    function prettyPhoto($id) {  
        $prettyPhoto= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/prettyPhoto/css/prettyPhoto.css" type="text/css" />'."\n";
        $prettyPhoto.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $prettyPhoto.= $this->jquery;
        $prettyPhoto.=  '  <script type="text/javascript" src="'.$this->option["base_url"].'allgallery/gallery/prettyPhoto/js/jquery.prettyPhoto.js"></script>'."\n";
        $prettyPhoto.= "  <script type=\"text/javascript\">
        jQuery(function($) {
              $(\"a[rel^='prettyPhoto']\").prettyPhoto({theme:'dark_rounded'});
        });
  </script>";
  
        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  '',
            'class'      =>  'class="ageent-ru"',
            'rel'        =>  'rel="prettyPhoto[gallery1'.$id.']"',
            'target'     =>  'target="_blank"',
            'before_tag_img' => '',
            'eng_tag_img' => ''
        );
        return array($prettyPhoto,$config);
    }
    
    function lightbox($id) {
        $lightbox= '  <script src="'.$this->option["base_url"].'allgallery/gallery/lightbox/js/prototype.js" type="text/javascript"></script>'."\n";
        $lightbox.= '  <script src="'.$this->option["base_url"].'allgallery/gallery/lightbox/js/scriptaculous.js?load=effects,builder" type="text/javascript"></script>'."\n";
        $lightbox.= '  <script type="text/javascript">var url_home = "'.$this->option["base_url"].'";</script>'."\n";
        $lightbox.= '  <script src="'.$this->option["base_url"].'allgallery/gallery/lightbox/js/lightbox.js" type="text/javascript"></script>'."\n";
        $lightbox.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $lightbox.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/lightbox/css/lightbox.css" type="text/css" />'."\n";

        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  '',
            'class'      =>  'class="ageent-ru"',
            'rel'        =>  'rel="lightbox[roadtrip'.$id.']"',
            'target'     =>  'target="_blank"',
            'before_tag_img' => '',
            'eng_tag_img' => ''
        );
        return array($lightbox,$config);
    }
    
    function jsbox($id) {
        $jsbox= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $jsbox.= '  <script src="'.$this->option["base_url"].'allgallery/gallery/jsibox/jsibox_basic.js" type="text/javascript"></script>'."\n";

        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  'onclick="return jsiBoxOpen(this)"',
            'class'      =>  'class="ageent-ru"',
            'rel'        =>  'rel="rr"',
            'target'     =>  'target="_blank"',
            'before_tag_img' => '',
            'eng_tag_img' => ''
        );
        return array($jsbox,$config);
    }
    
    function darkbox($id) {
        $darkbox= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $darkbox.= $this->jquery;
        $darkbox.= '  <script src="'.$this->option["base_url"].'allgallery/gallery/darkbox/darkbox.js" type="text/javascript"></script>'."\n";   
        $darkbox.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/darkbox/darkbox.css" type="text/css" />'."\n";
        $darkbox.= '  <!--[if lt IE 7]>
        <link rel="stylesheet" type="text/css" href="'.$this->option["base_url"].'allgallery/gallery/darkbox/darkbox_ie.css" />
  <![endif]-->'."\n";
        $darkbox.= "  <script type=\"text/javascript\">
            jQuery(function($) {
                $(\"a.darkbox\").click(function () {
                    $(this).darkbox_one();
                    return false;
                });
            })
  </script>\n";
        
        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  '',
            'class'      =>  'class="darkbox ageent-ru"',
            'rel'        =>  '',
            'target'     =>  'target="_blank"',
            'before_tag_img' => '',
            'eng_tag_img' => ''
        );

        return array($darkbox,$config);
    }
    
    function fboxbot($id) {
        $fboxbot= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $fboxbot.= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/fboxbot/fbox.css" type="text/css" />'."\n";
        $fboxbot.= '  <script type="text/javascript">var url_home = "'.$this->option["base_url"].'";</script>'."\n";
        $fboxbot.= '  <script src="'.$this->option["base_url"].'allgallery/gallery/fboxbot/fbox_conf.js" type="text/javascript"></script>'."\n";   
        $fboxbot.= '  <script src="'.$this->option["base_url"].'allgallery/gallery/fboxbot/fbox_engine-min.js" type="text/javascript"></script>'."\n";  
        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  '',
            'class'      =>  'class="ageent-ru"',
            'rel'        =>  '',
            'target'     =>  'target="_blank"',
            'before_tag_img' => '<span class="frontbox"  name="fbox_'.$id.'"></span>',
            'eng_tag_img' => ''
        );

        return array($fboxbot,$config);
    }
    
    function fancyzoom($id) {
        $fancyzoom= '  <link rel="stylesheet" href="'.$this->option["base_url"].'allgallery/gallery/joomthumbnail.css" type="text/css" />'."\n";
        $fancyzoom.= '  <script src="'.$this->option["base_url"].'allgallery/gallery/fancyzoom/FancyZoomHTML.js" type="text/javascript"></script>'."\n";  
        $fancyzoom.= '  <script type="text/javascript">var url_home = "'.$this->option["base_url"].'";</script>'."\n";
        $fancyzoom.= '  <script src="'.$this->option["base_url"].'allgallery/gallery/fancyzoom/FancyZoom.js" type="text/javascript"></script>'."\n";  
        $fancyzoom.= "  <script type=\"text/javascript\">
                window.onload = function() { setupZoom() };
            </script>";

        $config = array(
            'before_tag' =>  '',
            'end_tag'    =>  '',
            'onclick'    =>  '',
            'class'      =>  'class="ageent-ru"',
            'rel'        =>  '',
            'target'     =>  '',
            'before_tag_img' => '',
            'eng_tag_img' => ''
        );
        
        return array($fancyzoom,$config);
    }
    
 }
?>