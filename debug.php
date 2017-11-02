<?php
$json = file_get_contents('petri.json');
$petri = json_decode($json, true);

echo json_last_error();
echo '<br>';
echo json_last_error_msg();

echo '<pre>'; print_r($petri); echo '</pre>';
?>