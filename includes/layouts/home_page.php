<?php

function home_page ($content = "") {
	$output = <<< HTML
	<main>
		<section>
			<h1>Latest posts</h1>
			<article>
				<h1>Latest Post Title</h1>
				<footer><small>Author</small> | <small>05.04.2018</small></footer>
				<p>Here will be posts description...</p>
				<p><a href="#">Read more</a></p>
			</article>
		</section>
		<aside>
			<h1>Popular Posts</h1>
			<ul>
				<li>
					<figure>
						<img src="/img/1.jpg">
						<figcaption>It's Popular posts title</figcaption>
					</figure>
				</li>
			</ul>
		</aside>
	</main>
HTML;

	return $output;
} // end of function home_page ()
