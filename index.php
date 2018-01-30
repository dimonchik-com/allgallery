<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 4.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" class="opa">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7"/>
    <link rel="stylesheet" type="text/css" media="screen" href="http://ageent.ru/templates/ageent/css/style.css"  />

    <?php
        if (class_exists("All_gallery")) {
            echo $all_gallery->get_gallery(); 
        }
    ?>
</head>
<body>

<div id="too"> 
<table width="100%" height="100%" border="0" cellpadding="0" cellspacing="0">
<tr>
    <td valign="top">
        <div class="cont_one"></div>
    </td>
</tr>
<tr>
    <td valign="top">
    <div class="max_width">
    <table cellpadding="0" cellspacing="0" border="0" width="100%" >
      <tr>
        <td valign="top" width="82%" class="sreda">
            <div class="allfix"></div>
            <div id="ja-content">             
            <table class="blog" cellpadding="0" cellspacing="0">
            <tr>
                <td valign="top">
                <div class="contentpaneopen">
                <div class="article-tools">
                <div class="article-meta"><span class="cread_too front">All gallery. All galleries are in one php script.</span></div>
                    <div class="buttonheading">
                        <span><img src="/images/M_images/emailButton.png" alt="E-mail" /></span>
                        <span><img src="/images/M_images/printButton.png" alt="Print" /></span>
                        <span><img src="/images/M_images/pdf_button.png" alt="PDF" /></span>
                    </div>
                </div>
                
                <style type="text/css">
                    .article-content img{ margin: 0 0 0px 0;}
                </style>
                <div class="article-content">
                    <center>
                        <img src="img/big_images/1.jpg" class="border_tracings" alt="Россия!" />
                        <img src="img/big_images/2.jpg" alt="Георгиевская Ленточка" />
                        <img src="img/big_images/4.jpg" />
                        <img src="img/big_images/6.JPG" alt="Часики в Москве" />
                        <img src="img/big_images/7.jpg" alt="Князь Александр Васильевич Италийский, граф Суворов-Рымникский" />
                        <img src="img/big_images/8.jpg" />
                        <br><br>
                        <img src="img/big_images/9.jpg" />
                        <img src="img/big_images/10.jpg" alt="СССР" />
                        <img src="img/big_images/11.jpg" alt="Пётр I" />
                        <img src="img/big_images/Admiral.jpg" alt="Адмирал" />
                        <img src="img/big_images/13.jpg" alt="я русский" />
                        <img src="img/big_images/14.jpg" />
                    </center>
                </div>
            </div>
        </div>
        </td>
      </tr>
   </table>
</div>  
    </td>
      </tr>
    </table>
    </div>
</td>
</tr>
  <tr>
        <td class="footer" valign="top"><div class="footer_agent"></div></td>
  </tr>
</table>
</div>
</body>
</html>