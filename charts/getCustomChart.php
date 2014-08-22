<?php

define("IN_ACE", true);

header("Content-type: text/javascript");

require_once "../includes/init.inc.php";

echo $aceman->output_chart();

?>