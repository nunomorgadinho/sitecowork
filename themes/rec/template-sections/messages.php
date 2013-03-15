<?php
/**
 * This is the template for displaying the message section.
 *
 * @package rec
 * @since rec 1.0
 */

?>

<!-- Messages Section -->

<section class="messages-section">
	<nav class="tabs messages-container">
		<ul class="messages-menu">
			<li><a href="#tab-1"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/messages-inbox.png"/><?php _e ('Inbox','rec'); ?></a></li>
			<li><a href="#tab-2"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/messages-sent.png"/><?php _e ('Sent','rec'); ?></a></li>
			<li><a href="#tab-3"><img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/messages-write.png"/><?php _e ('Write','rec'); ?></a></li>
		</ul>
	
		<section id="tab-1">

		<!-- Message List -->
			<ul class="messages-list-container">
			<!-- Single Message -->	
				<li class="message-preview">
					<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/>
					<div class="message-firstblock">
						<h1><a href="#">Christopher Walken</a></h1>
						<p>18-12-2012 at 12:45</p>
					</div>
					<div class="message-secondblock">
						<h1><a href="#">My hair was famous before I was.<a/></h1>
						<ul>
							<li><a href="#">View</a> |</li>
							<li><a href="#">Delete</a> |</li>
							<li><a href="#">Reply</a></li>
						</ul>
					</div>
				</li>
			<!-- end of Single Message -->
			<!-- Single Message -->	
				<li class="message-preview unread">
					<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/>
					<div class="message-firstblock">
						<h1><a href="#">Christopher Walken</a></h1>
						<p>18-12-2012 at 12:45</p>
					</div>
					<div class="message-secondblock">
						<h1><a href="#">My hair was famous before I was.<a/></h1>
						<ul>
							<li><a href="#">View</a> |</li>
							<li><a href="#">Delete</a> |</li>
							<li><a href="#">Reply</a></li>
						</ul>
					</div>
				</li>
			<!-- end of Single Message -->
			</ul>
		<!-- end of Message List -->

		</section>

		<section id="tab-2">
			<!-- Message List -->
			<ul class="messages-list-container">
			<!-- Single Message -->	
				<li class="message-preview">
					<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/>
					<div class="message-firstblock">
						<h1><a href="#">Dylan Morais</a></h1>
						<p>18-12-2012 at 12:45</p>
					</div>
					<div class="message-secondblock">
						<h1><a href="#">My hair was famous before I was.<a/></h1>
						<ul>
							<li><a href="#">View</a> |</li>
							<li><a href="#">Delete</a> |</li>
							<li><a href="#">Reply</a></li>
						</ul>
					</div>
				</li>
			<!-- end of Single Message -->
			<!-- Single Message -->	
				<li class="message-preview">
					<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/>
					<div class="message-firstblock">
						<h1><a href="#">Bruno Cartaxeiro</a></h1>
						<p>18-12-2012 at 12:45</p>
					</div>
					<div class="message-secondblock">
						<h1><a href="#">My hair was famous before I was.<a/></h1>
						<ul>
							<li><a href="#">View</a> |</li>
							<li><a href="#">Delete</a> |</li>
							<li><a href="#">Reply</a></li>
						</ul>
					</div>
				</li>
			<!-- end of Single Message -->

			<!-- Single Message -->	
				<li class="message-preview">
					<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/>
					<div class="message-firstblock">
						<h1><a href="#">Bruno Cartaxeiro</a></h1>
						<p>18-12-2012 at 12:45</p>
					</div>
					<div class="message-secondblock">
						<h1><a href="#">My hair was famous before I was.<a/></h1>
						<ul>
							<li><a href="#">View</a> |</li>
							<li><a href="#">Delete</a> |</li>
							<li><a href="#">Reply</a></li>
						</ul>
					</div>
				</li>
			<!-- end of Single Message -->
			<!-- Single Message -->	
				<li class="message-preview">
					<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/>
					<div class="message-firstblock">
						<h1><a href="#">Bruno Cartaxeiro</a></h1>
						<p>18-12-2012 at 12:45</p>
					</div>
					<div class="message-secondblock">
						<h1><a href="#">My hair was famous before I was.<a/></h1>
						<ul>
							<li><a href="#">View</a> |</li>
							<li><a href="#">Delete</a> |</li>
							<li><a href="#">Reply</a></li>
						</ul>
					</div>
				</li>
			<!-- end of Single Message -->
			<!-- Single Message -->	
				<li class="message-preview">
					<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/>
					<div class="message-firstblock">
						<h1><a href="#">Bruno Cartaxeiro</a></h1>
						<p>18-12-2012 at 12:45</p>
					</div>
					<div class="message-secondblock">
						<h1><a href="#">My hair was famous before I was.<a/></h1>
						<ul>
							<li><a href="#">View</a> |</li>
							<li><a href="#">Delete</a> |</li>
							<li><a href="#">Reply</a></li>
						</ul>
					</div>
				</li>
			<!-- end of Single Message -->
					<nav class="messages-pagination">
						<ul>
							<li class="current-page"><a href="#">1</a></li>
							<li><a href="#">2</a></li>
							<li><a href="#">3</a></li>
						</ul>
					</nav>
			</ul>
		<!-- end of Message List -->
		</section>

		<section id="tab-3" class="write-section">
			<img src="<?php echo get_bloginfo('stylesheet_directory'); ?>/images/user-thumb.png"/>
			<form class="write-message">
				<h1>Dylan Morais</h1>
				<div class="write-input"><label>To</label><input class="write-name" type="text"></input></div>
				<div class="write-input"><label>Subject</label><input class="write-subject" type="text"></input></div>
				<div class="write-input"><label>Message</label><input class="write-text" type="text"></input></div>
				<button>send</button>
			</form>
		</section>
	
	<aside class="messages-close">
		<a href="#">X</a>
	</aside>

	</nav>
	
</section>
