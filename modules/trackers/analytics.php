<?php
if (!defined('ABSPATH')) exit;

/**
 * --------------------------------------------------
 * Google Analytics, Microsoft Clarity, Webmaster tools
 * --------------------------------------------------
 */

add_action('wp_head', function() {
	$code = <<<TRACKERS
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-E9VQJBKBVF"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', 'G-E9VQJBKBVF');
</script>
<meta name="msvalidate.01" content="C77B3CCDDF10DB6FFF08A72D4026C71A" />
<script type="text/javascript">
(function(c,l,a,r,i,t,y){
    c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
    t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
    y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
})(window, document, "clarity", "script", "nn8vf4vx3v");
</script>
TRACKERS;
    if (!is_user_logged_in()) {
        echo $code;
    }
});