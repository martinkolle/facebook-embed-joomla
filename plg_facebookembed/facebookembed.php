<?php
/**
* @author Martin Kollerup
* @package Facebook Embed
* @copyright Copyright (C) www.kmweb.dk. All rights reserved.
* @license http://www.gnu.org, see LICENSE.php
*
*/
defined( '_JEXEC' ) or die;

jimport( 'joomla.plugin.plugin' );
jimport( 'joomla.html.parameter' );
class plgContentFacebookEmbed extends JPlugin
{
    public function plgContentFacebookEmbed( &$subject, $params ){
        parent::__construct( $subject, $params );
    }

	public function onContentPrepare( $context, &$article, &$params, $limitstart=0 ) {
    
        if (strstr($article->text, 'https://www.facebook.com/photo.php?v') === false && strstr( $article->text, 'https://www.facebook.com/photo.php?v') === false) {
            return false;
        }
        $urls = array('/https:\/\/www.facebook.com\/photo.php\?v=([a-zA-Z0-9_-]+)(.+?)*/');
        $article->text = preg_replace($urls, $this->facebookEmbed('$1'), $article->text);

        return true;
    }
    
    public function facebookEmbed($facebookVideo, $embed = false){
        echo $facebookVideo;
        if($embed):
            $plugin     = JPluginHelper::getPlugin('content', 'facebookEmbed');
            $params     = new JParameter( $plugin->params );
            $width      = $this->params->get('width', 600);
            $height     = $this->params->get('height', 350);
            $ratio      = $this->params->get('ratio', "1.6");
            $responsive = $this->params->get('responsive', 1);
            $fullscr    = $this->params->get('fullscr',1);
            $fullscreen = ($fullscr == 1) ? "true" : "false";

            $embed      = '<div id="facebookEmbed"><object width="'.$width.'" height="'.$height.'" class="VombieFacebookEmbed"><param name="allowfullscreen" value="'.$fullscreen.'"></param><param name="movie" value="https://www.facebook.com/v/'.$facebookVideo.'"></param><embed src="https://www.facebook.com/v/'.$facebookVideo.'" type="application/x-shockwave-flash" allowfullscreen="'.$fullscr.'" width="'.$width.'" height="'.$height.'"></embed></object></div>';
            
            if($responsive == 1) :
                $document = JFactory::getDocument();
                $js = ' /*Facebook embed - responsive*/
                        window.addEvent(\'load\',function() {
                            var vombieFacebookEmbed = document.id("facebookEmbed").parentNode.getSize().x;
                            var VombieFacebookEmbedHeight = vombieFacebookEmbed / '.$ratio.';
                            /*alert(vombieFacebookEmbed);*/
                            $$(".VombieFacebookEmbed").set("width", vombieFacebookEmbed);
                            $$(".VombieFacebookEmbed embed").set("width", vombieFacebookEmbed);
                            $$(".VombieFacebookEmbed").set("height", VombieFacebookEmbedHeight);
                            $$(".VombieFacebookEmbed embed").set("height", VombieFacebookEmbedHeight);
                        });
                    ';
                $js = preg_replace('/\s+/', ' ', $js);
                $document->addScriptDeclaration($js);
            endif;
        endif;
     return $embed;
	}
}
