<?php
$dirIncluded = "class";
foreach(getListDir($dirIncluded) as $f) {
	require_once($dirIncluded."/".$f);
}
?>