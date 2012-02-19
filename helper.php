<?php
/**
 * @package      ITPrism Modules
 * @subpackage   ITPSocialSubscribe
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2010 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * ITPSocialSubscribe is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// no direct access
defined('_JEXEC') or die;

class ItpSocialSubscribeHelper{
    
    /**
     * Generate a code for the extra buttons
     */
    public static function getExtraButtons($params) {
        
        $html  = "";
        // Extra buttons
        for($i=1; $i < 6;$i++) {
            $btnName = "ebuttons" . $i;
            $extraButton = $params->get($btnName, "");
            if(!empty($extraButton)) {
                $html  .= $extraButton;
            }
        }
        
        return $html;
    }
    
    public static function getTwitter($params){
        
        $html = "";
        if($params->get("twitterButton")) {
            
            $counter = (!$params->get("twitterCounter")) ? "false" : "true";
            
             $html = '
             <div class="itp_social_sidebar itp_twitter">
             	<a href="https://twitter.com/' . $params->get("twitterName") . '" class="twitter-follow-button" data-show-count="' . $counter . '" data-lang="' . $params->get("twitterLanguage") . '" data-size="' . $params->get("twitterSize") . '" >Follow @' . $params->get("twitterName") . '</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </div>
            ';
        }
         
        return $html;
    }
    
    public static function getGoogleBadge($params){
        
        $html = "";
        
        if($params->get("badgeWidget")) {
            $url = $params->get("badgeAddress");
            
            $html .= '<div class="itp_google_badge">';
            
            $html .= '<!-- Place this tag in the <head> of your document -->
<link href="' . $url . '" rel="publisher" /><script type="text/javascript">
window.___gcfg = {lang: "' . $params->get("badgeLocale") . '"};
(function() 
{var po = document.createElement("script");
po.type = "text/javascript"; po.async = true;po.src = "https://apis.google.com/js/plusone.js";
var s = document.getElementsByTagName("script")[0];
s.parentNode.insertBefore(po, s);
})();</script>';
            
            switch($params->get("badgeRenderer")) {
                
                case 1:
                    $html .= self::genGoogleBadge($params, $url);
                    break;
                    
                default:
                    $html .= self::genGoogleBadgeHTML5($params, $url);
                    break;
            }
            
          
            $html .= '</div>';
        }
        
        return $html;
    }
    
    /**
     * 
     * Render the Google badge in standart syntax
     * 
     * @param array $params
     * @param string $url
     * @param string $language
     */
    public static function genGoogleBadge($params, $url) {
        
        $html = '<!-- Place this tag where you want the badge to render -->
<g:plus href="'.$url.'" width="'.$params->get("badgeWidth").'" height="'.$params->get("badgeType").'" theme="'.$params->get("badgeTheme").'"></g:plus>';
				
        return $html;
    }
    
    /**
     * 
     * Render the Google badge in HTML5 syntax
     * 
     * @param array $params
     * @param string $url
     * @param string $language
     */
    public static function genGoogleBadgeHTML5($params, $url) {
        
        $html = '<!-- Place this tag where you want the badge to render -->
<div class="g-plus" data-href="'.$url.'" data-width="'.$params->get("badgeWidth").'" data-height="'.$params->get("badgeType").'" data-theme="'.$params->get("badgeTheme").'"></div>';
        return $html;
    }
    
    
    public static function getFacebookLike($params){
        
        $html = "";
        if($params->get("facebookLikeButton")) {
            
            $url = $params->get("facebookLikePageAddress");
            
            if($params->get("fbDynamicLocale", 0)) {
                $fbLocale = JFactory::getLanguage();
                $fbLocale = $fbLocale->getTag();
                $fbLocale = str_replace("-","_",$fbLocale);
            } else {
                $fbLocale = $params->get("fbLocale", "en_US");
            }
            
            $faces = (!$params->get("facebookLikeFaces")) ? "false" : "true";
            
            $layout = $params->get("facebookLikeType", "button_count");
            if(strcmp("box_count", $layout)==0){
                $height = "80";
            } else {
                $height = "25";
            }
            
            $html = '<div class="itp_socialsubscribe_fbl">';
            
            switch($params->get("facebookLikeRenderer")) {
                
                case 0: // iframe
                    $html .= self::genFacebookLikeIframe($params, $url, $faces, $fbLocale);
                break;
                    
                case 1: // XFBML
                    $html .= self::genFacebookLikeXfbml($params, $url, $faces, $fbLocale);
                break;
             
                default: // HTML5
                   $html .= self::genFacebookLikeHtml5($params, $url, $faces, $fbLocale);
                break;
            }
            
            $html .="</div>";
        }
        
        return $html;
    }
    
    public static function genFacebookLikeIframe($params, $url, $faces, $fbLocale) {
        
        $html = '
            <div class="itp-socialsubscribe-fbl">
            <iframe src="http://www.facebook.com/plugins/like.php?';
            
            if($params->get("facebookLikeAppId")) {
                $html .= 'app_id=' . $params->get("facebookLikeAppId"). '&amp;';
            }
            
            $html .= 'href=' . rawurlencode($url) . '&amp;send=false&amp;locale=' . $fbLocale . '&amp;layout=standart&amp;show_faces=' . $faces . '&amp;width=' . $params->get("facebookLikeWidth","450") . '&amp;action=' . $params->get("facebookLikeAction",'like') . '&amp;colorscheme=' . $params->get("facebookLikeColor",'light') . '&amp;height=80';
            if($params->get("facebookLikeFont")){
                $html .= "&amp;font=" . $params->get("facebookLikeFont");
            }
            if($params->get("facebookLikeAppId")){
                $html .= "&amp;appId=" . $params->get("facebookLikeAppId");
            }
            $html .= '" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:' . $params->get("facebookLikeWidth", "450") . 'px; height:80px;" allowTransparency="true"></iframe>
            </div>
        ';
            
        return $html;
    }
    
    public static function genFacebookLikeXfbml($params, $url, $faces, $fbLocale) {
        
        $html = "";
                
        if($params->get("facebookRootDiv",1)) {
            $html .= '<div id="fb-root"></div>';
        }
        
       if($params->get("facebookLoadJsLib", 1)) {
            $html .= '<script type="text/javascript" src="http://connect.facebook.net/' . $fbLocale . '/all.js#xfbml=1';
            if($params->get("facebookLikeAppId")){
                $html .= '&amp;appId=' . $params->get("facebookLikeAppId"); 
            }
            $html .= '"></script>';
        }
        
        $html .= '
        <fb:like 
        href="' . $url . '" 
        layout="standart" 
        show_faces="' . $faces . '" 
        width="' . $params->get("facebookLikeWidth","450") . '" 
        colorscheme="' . $params->get("facebookLikeColor","light") . '"
        send="' . $params->get("facebookLikeSend",0). '" 
        action="' . $params->get("facebookLikeAction",'like') . '" ';

        if($params->get("facebookLikeFont")){
            $html .= 'font="' . $params->get("facebookLikeFont") . '"';
        }
        $html .= '></fb:like>
        ';
        
        return $html;
    }
    
    public static function genFacebookLikeHtml5($params, $url, $faces, $fbLocale) {
        
         $html = '';
                
        if($params->get("facebookRootDiv",1)) {
            $html .= '<div id="fb-root"></div>';
        }
                
       if($params->get("facebookLoadJsLib", 1)) {
                   
       $html .='
<script type="text/javascript">(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/' . $fbLocale . '/all.js#xfbml=1';
               if($params->get("facebookLikeAppId")){
                    $html .= '&amp;appId=' . $params->get("facebookLikeAppId"); 
                }
$html .= '"
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>
                   ';
                }
        $html .= '
                <div 
                class="fb-like" 
                data-href="' . $url . '" 
                data-send="' . $params->get("facebookLikeSend",0). '" 
                data-layout="standart" 
                data-width="' . $params->get("facebookLikeWidth","450") . '" 
                data-show-faces="' . $faces . '" 
                data-colorscheme="' . $params->get("facebookLikeColor","light") . '" 
                data-action="' . $params->get("facebookLikeAction",'like') . '"';
                
                
        if($params->get("facebookLikeFont")){
            $html .= ' data-font="' . $params->get("facebookLikeFont") . '" ';
        }
        
        $html .= '></div>';
        
        return $html;
        
    }
    
    
    public static function getLinkedInAndPinterest($params){
        
        $html = "";
        if($params->get("linkedInButton") OR $params->get("pinterestButton")) {
            
            $html = '
            <div class="itp_social_sidebar itp_linkedin_pinterest">
            	<span>' . htmlspecialchars($params->get("pinlinkText")) . '</span>';
            
            if($params->get("linkedInButton")) {
            	$html .= '
            	<a class="itp_external" href="'.$params->get("linkedInAddress") .'" target="_blank" style="margin: 0 10px;"><img width="70" height="25" alt="LinkedIn Today" src="http://c759930.r30.cf2.rackcdn.com/wp-content/themes/b2c/images/linkedin-today.png"></a>';
            }
            
            if($params->get("pinterestButton")) {
            	$html .= '
            	<a class="itp_external" href="'.$params->get("pinterestAddress") .'" target="_blank"><img width="78" height="26" alt="Follow Me on Pinterest" src="http://passets-cdn.pinterest.com/images/pinterest-button.png"></a>
            	';
            }
            
            $html .= '</div>';

        }
        
        return $html;
    }
    

    public static function getPinterest($params){
        
        $html = "";
        if($params->get("pinterestFollowButton")) {
            
            $html = '
            <div class="itp_social_sidebar itp_pinterest">';
            	$html .= '
            	<a href="'.$params->get("pinterestFollowAddress") .'">
            	<img src="http://passets-cdn.pinterest.com/images/follow-on-pinterest-button.png" width="156" height="26" alt="Follow Me on Pinterest" />
            	</a>
            	';
            
            $html .= '</div>';

        }
        
        return $html;
    }
    
    
}