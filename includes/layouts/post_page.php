<?php

function post_page ($content = "") {
	$output = <<< HTML
		<main>
			<article>
				<h1>Posts Title</h1>
				<p>Here will be posts content</p>
			</article>
			<aside>
				<h1>Related posts</h1>
				<ul>
					<li>
						<figure>
							<img src="/img/0.jpg" alt="Illustration for related post">
							<figcaption>Related post title</figcaption>
						</figure>
					</li>
				</ul>
			</aside>
		</main>
HTML;
	
	return $output;
} // end of function post_page ()
