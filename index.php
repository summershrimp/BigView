<?php

define("IN_ACE", true);

require_once "includes/init.inc.php";

function is_mobile() {
	$regex_match="/(nokia|iphone|ipad|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|"
		. "htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|"
		. "blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|"
		. "symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|"
		. "jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220"
		. ")/i";
	return isset($_SERVER['HTTP_X_WAP_PROFILE']) || isset($_SERVER['HTTP_PROFILE']) || preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}
if (is_mobile()) {
	header("Location: ./mobile/index.php");
}

function alertAndBack($content) {
	echo "<script>alert('" . $content . "');window.top.history.go(-1);</script>";
	die();

}

$page = isset($_GET['page']) ? $_GET['page'] : "entrance";
$pages = array("admin", "ajax", "delfile", "logout", "manchart", "manfile", "run", "transfer", "upload", "users");

if (
	(in_array($page, $pages) && $aceman->is_login()) ||
	($page == "entrance" || $page =="login")
)
	require_once $page . ".php";
else
	echo "Forbidden.";

?>