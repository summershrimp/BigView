<?php

function is_mobile() {
	// returns true if one of the specified mobile browsers is detected
	$regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|"
		. "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|"
		. "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|"
		. "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|"
		. "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220"
		. ")/i";
	return isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}
if (!is_mobile()) {
	header("Location: ../index.php");
}

$page = isset($_GET['page']) ? $_GET['page'] : "entrance";
$pages = array("entrance", "manfile", "manchart", "admin", "login", "logout");

if (in_array($page, $pages))
	require_once $page . ".php";
else
	echo "Forbidden.";

?>