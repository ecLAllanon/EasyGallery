<?php
	/*
	 * EasyGallery
	 * http://github.com/romaricdrigon/
	 */
	
	/* Config */
	// do you want to use thumbnails? 60px images, galleries will load faster (big pictures are not loaded at the same time than the page)
	// it'll degrade smartly if no thumb is found - but disabling it may allow you to save a little time if you don't have any thumb
	$use_thumbs = TRUE;
	
	require('lister.php');
	
	$subdir = get_folder(); // get only subdirectory
	$dir = ($subdir=='')?'photos':'photos/'.$subdir; // full path - check if there's subdir to avoid ending /

	// scan folder
	$list = lister($dir, $use_thumbs);
?>
<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>EasyGallery</title>
	<link type="text/css" href="style.css" rel="stylesheet" />
	<!-- jQuery -->
	<script type="text/javascript" src="lib/jquery.min.js"></script>
	<!-- Galleria (jQuery plugin) -->
	<script type="text/javascript" src="galleria/galleria-1.2.5.min.js"></script>
	<script type="text/javascript" src="galleria/themes/classic/galleria.classic.min.js"></script>
	<script type="text/javascript" src="galleria/plugins/history/galleria.history.min.js"></script>
	<!-- our custom script -->
	<script type="text/javascript" src="easygallery.js"></script>
</head>
<body>
	<div class="main" id="main">
		<div class="header"><h2>EasyGallery</h2></div>
		<div class="path"><?php show_path('photos', $subdir); ?></div>
		<div class="gallery" id="gallery">
			<!-- link to images - will be displayed if Javascript is disabled -->
			<?php
				// show link to images
				foreach ($list['picture'] as $pic) {
					if (isset($pic['big']) && ($pic['big'] !== 'thumbnail.jpg')) { // we check if the big picture exists
						if (($use_thumbs === TRUE) && (isset($pic['thumb']))) {
							echo '<a href="'.$dir.'/'.$pic['big'].'" rel="'.$dir.'/'.$pic['thumb'].'">'.$pic['big'].'</a><br />'."\n";
						} else {
							// if no thumb is provided, Galleria will use the big picture automatically
							echo '<a href="'.$dir.'/'.$pic['big'].'">'.$pic['big'].'</a><br />'."\n";
						}
					}
				}
			?>
		</div>
		<?php 
			if (isset($list['folder']) && sizeof($list['folder']) !== 0):
		?>
			<div class="gallery_list">
				<h3>Sous-galeries</h3>
				<?php
					// show link to images
					foreach ($list['folder'] as $fol) {
						// test to avoid one more slash	
						$path = ($subdir=='')?$fol:$subdir.'/'.$fol;
						
						echo    '<a href="?gallery='.gallery_link($path).'" title="'.$fol.'">'.
                                '<div class="thumb" style="background-image: url(\''.$dir.'/'.$fol.'/'.get_thumbnail($dir.'/'.$fol).'\');">'.
						        '<div class="text">'.$fol.'</div></div></a>'."\n";
					}
				?>
				<br clear="all" />
			</div>
		<?php 
			endif;
		?>
	<div class="footer">
		<?php 
			// display the comment only if there's a gallery
			if (isset($list['picture']) && sizeof($list['picture']) !== 0):
		?>
			<span class="white">Appuyer sur la touche "Entr&eacute;e" du clavier pour d&eacute;marrer le diaporama, "Esc" pour le quitter.</span><br />
		<?php 
			endif;
		?>
		EasyGallery, 2012, <a href="http://github.com/romaricdrigon/" target="_blank">http://github.com/romaricdrigon/</a>
	</div>
	</div> <!-- end main -->
	<!-- finally, we load galleria -->
	<script>
		$('#gallery').galleria({
		    //data_source: data,
		    height: 700,
		    width: 960,
		    debug: false,
		    // we use a custom source, because img will all load on startup it's crappy, and json may not work if javascript is disabled
		    dataSelector: "a",
		    dataConfig: function(a) {
		        return {
		            <?php
		            	if ($use_thumbs === TRUE) {
							echo 'thumb: $(a).attr(\'rel\'),'."\n";
						}
		            ?>
		            image: $(a).attr('href') // tell Galleria that the href is the main image,
					// we have no title and so on at the moment
		        };
		    }
		});
	</script>
</body>
</html>