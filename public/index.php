<?php

require_once '../includes/lib/routing.php';
require_once '../includes/lib/content_displaying.php';

$routing_inf = router();

$route = $routing_inf["route"];
$module = $routing_inf["module"];
$module_rank = $routing_inf["module_rank"];

if (isset( $routing_inf["parent_module"])) $parent_module = $routing_inf["parent_module"];
else $parent_module = null;

if ($content = get_content($route, $module, $module_rank, $parent_module))
{
	show_page($content["page"], $content["context"], $content["content"]);
}