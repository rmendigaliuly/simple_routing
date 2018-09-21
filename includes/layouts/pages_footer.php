<?php

function pages_footer ($context = "public") {

	// Подготавливаем footer для публичной области
	$output = <<<HTML

				<footer>
					<p><small>&copy; 2018 Blog</small></p>
				</footer>
			</body>
		</html>
HTML;
	

	if ($context === "admin")
	{
		// Подготавливаем footer для административной области
		$output = "";
	} // endif($context === "admin")
	
	// Возвращаем разметку
	return $output;
} // end of function pages_footer ()