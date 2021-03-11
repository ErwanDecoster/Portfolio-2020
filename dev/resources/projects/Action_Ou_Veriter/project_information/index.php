<?php
// indiqué le chemin de votre fichier JSON, il peut s'agir d'une URL
$json = file_get_contents("info.json");

var_dump(json_decode($json));

echo "<br>-----------------------";

$parsed_json = json_decode($json);
$language_type = $parsed_json->{'site_info'}->{'language_type'};

echo "<br> ",$parsed_json->site_info->name;
echo "<br> ",$parsed_json->site_info->desciption;


echo "<br> ",$language_type->html;
echo "<br> ",$language_type->css;
echo "<br> ",$language_type->js;
echo "<br> ",$language_type->php;
echo "<br> ",$language_type->sql;
?>
