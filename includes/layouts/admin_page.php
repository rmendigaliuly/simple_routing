<?php

function admin_page ($content = "") {
	$output = <<< HTML
	<h1>Welcome to Admin Panel</h1>
	<section>
		<h1>Here will be shortcuts and dashboards</h1>
		<div>
			<h1>First Shorcut</h1>
		</div>
	</section>
HTML;

	return $output;
} // end of function admin_page ($content = "")
