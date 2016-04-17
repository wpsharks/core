<?php
declare (strict_types = 1);
namespace WebSharks\Core;

use WebSharks\Core\Classes\CoreFacades as c;

require_once dirname(__FILE__, 2).'/includes/local.php';

/* ------------------------------------------------------------------------------------------------------------------ */

$string = 'https://www.youtube.com/watch?v=IUDTlvagjJA'."\n\n";
$string .= 'See: http://s2member.com/'."\n\n";
$string .= 'https://www.youtube.com/watch?v=IUDTlvagjJA'."\n\n";
$string .= 'https://www.youtube.com/watch?v=IUDTlvagjJA'."\n\n";
$string .= 'http://www.amazon.com/gp/product/B017NL5EHW/ref=s9_hps_bw_g405_i1?pf_rd_m=ATVPDKIKX0DER&pf_rd_s=merchandised-search-2&pf_rd_r=0RE0XBR4242M97JJMW7D&pf_rd_t=101&pf_rd_p=2291488682&pf_rd_i=11350978011'."\n\n";
$string .= 'https://www.flickr.com/photos/shutterjack/22665160864/in/explore-2015-11-25/'."\n\n";
$string .= 'https://www.dailymotion.com/video/x2o4wou_cortoons-tv-big-buck-bunny_fun';

echo c::oEmbed($string);
