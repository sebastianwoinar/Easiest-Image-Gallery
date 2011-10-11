<?php
/* file: gallery.php
*  date: 9th October 2011
*  author: sebastian@woinar.de
*
* This file includes some helper methods for returning and printing html and JavaScript sources.
*
*
*
*  function printGallerySource()
        Prints html code enhanced with dynamic content from texts.php file.
                
*  function getGalleryItems($subdir)
        Returns html code for galleryitems of one particular subfolder
        
*  function printScriptForSlider()
        Prints JavaScript code for starting the easyslider.
*
*/


function printGallerySource($id) {
    echo '
    <div id="page-' . $id . '" class="page">
		<div class="container">' .
			         getHead($id) . 
    			     getSubhead($id) . 
    			     getDescription($id) . 
    			'
			<div id="gallery-' . $id . '" class="content gallery">
				<div id="controls-' . $id . '" class="controls"></div>
				<div class="slideshow-container">
					<div id="loading-' . $id . '" class="loader"></div>
					<div id="slideshow-' . $id . '" class="slideshow"></div>
				</div>
				<div id="caption-' . $id . '" class="caption-container"></div>
			</div>
			<div id="thumbs-' . $id . '" class="navigation">
				<ul class="thumbs noscript">';
	
	getGalleryItems("/" . $id);
					
    echo '</ul>
			</div>
			<div style="clear: both;"></div>
		</div>
	</div>
	<script type="text/javascript">
		jQuery(document).ready(function($) {
			// We only want these styles applied when javascript is enabled
			$("div.navigation").css({"width" : "300px", "float" : "left"});
			$("div.content").css("display", "block");

			// Initially set opacity on thumbs and add
			// additional styling for hover effect on thumbs
			var onMouseOutOpacity = 0.67;
			$("#thumbs-' . $id . ' ul.thumbs li").opacityrollover({
				mouseOutOpacity:   onMouseOutOpacity,
				mouseOverOpacity:  1.0,
				fadeSpeed:         "fast",
				exemptionSelector: ".selected"
			});
			
			// Initialize Advanced Galleriffic Gallery
			var gallery = $("#thumbs-' . $id . '").galleriffic({
				delay:                     2500,
				numThumbs:                 18,
				preloadAhead:              10,
				enableTopPager:            true,
				enableBottomPager:         true,
				maxPagesToShow:            70,
				imageContainerSel:         "#slideshow-' . $id . '",
				controlsContainerSel:      "#controls-' . $id . '",
				captionContainerSel:       "#caption-' . $id . '",
				loadingContainerSel:       "#loading-' . $id . '",
				renderSSControls:          true,
				renderNavControls:         true,
				playLinkText:              "Play Slideshow",
				pauseLinkText:             "Pause Slideshow",
				prevLinkText:              "&lsaquo; Previous Photo",
				nextLinkText:              "Next Photo &rsaquo;",
				nextPageLinkText:          "Next &rsaquo;",
				prevPageLinkText:          "&lsaquo; Prev",
				enableHistory:             false,
				autoStart:                 false,
				syncTransitions:           true,
				defaultTransitionDuration: 900,
				onSlideChange:             function(prevIndex, nextIndex) {
					// "this" refers to the gallery, which is an extension of $("#thumbs-' . $id . '")
					this.find("ul.thumbs").children()
						.eq(prevIndex).fadeTo("fast", onMouseOutOpacity).end()
						.eq(nextIndex).fadeTo("fast", 1.0);
				},
				onPageTransitionOut:       function(callback) {
					this.fadeTo("fast", 0.0, callback);
				},
				onPageTransitionIn:        function() {
					this.fadeTo("fast", 1.0);
				}
			});

			/**** Functions to support integration of galleriffic with the jquery.history plugin ****/

			// PageLoad function
			// This function is called when:
			// 1. after calling $.historyInit();
			// 2. after calling $.historyLoad();
			// 3. after pushing "Go Back" button of a browser
			function pageload(hash) {
				// alert("pageload: " + hash);
				// hash doesnt contain the first # character.
				if(hash) {
					$.galleriffic.gotoImage(hash);
				} else {
					gallery.gotoIndex(0);
				}
			}

			// Initialize history plugin.
			// The callback is called at once by present location.hash. 
			$.historyInit(pageload, "advanced.html");

			// set onlick event for buttons using the jQuery 1.3 live method
			$("a[rel=\'histor\']").live("click", function(e) {
				if (e.button != 0) return true;
				
				var hash = this.href;
				hash = hash.replace(/^.*#/, "");

				// moves to a new page. 
				// pageload is called at once. 
				// hash dont contain "#", "?"
				$.historyLoad(hash);

				return false;
			});
			
                
			
			

			/****************************************************************************************/
		});
	</script>';
}


function getGalleryItems($subdir) {
    $manual_subpath = $subdir;
    include('pixlie.php');
	$result ="";
    foreach ($pixlie_table_file as $picture){
        @ini_set('default_charset','utf-8');
        $name =  $picture[iptc_object_name] == null ? $picture[name] : utf8_decode($picture[iptc_object_name]);
    	$item =  '
    	    <li>
                <a class="thumb" name="optionalCustomIdentifier" href="' . 
                            $pixlie_table_env['path_pixlie'].$picture['link_get'] . "_b" . $picture['extension'] . "&bildpfad=" . $subdir
                        .'" title="'.
                            $name
                        .'">
                  <img src="' .
                            $pixlie_table_env['path_pixlie'].$picture['link_get'] . "_t" . $picture['extension'] . "&bildpfad=" . $subdir                    
                            .'" alt="'.
                                $name
                            .'" />
              </a>
              <div class="caption">
                    <div class="image-title">' .
                             $name
                      . '</div>
       			    <div class="image-desc">' .
                             $picture[iptc_copyright]
                      . '</div>
                       <div class="image-copy">' .
                                $picture[iptc_caption]
                     . '</div>
              </div>
          </li>';	
            $result .= $item;
  }   
  	echo $result;
}
function printScriptForSlider(){
    /* Should be executed after the insertion of the galleries. So the ready Function will be executed after the ready functions of the galleries as well */
    
    echo 
    '<script type="text/javascript">
		jQuery(document).ready(function($) {
		    $("#slider").myEasySlider({ 
			    numeric: true,
			    controlsFade : false
		    });    			   
        });
	</script>';
}



if(isset($_GET[id])){
    include("../galleries/texts.php");
    $id =  $_GET[id];
    $pathToRessources = "../";

?>

<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>My Gallery</title>
		<link rel="stylesheet"        href="<?php echo $pathToRessources; ?>css/basic.css" type="text/css" />
		<link rel="stylesheet"        href="<?php echo $pathToRessources; ?>css/galleriffic-3.css" type="text/css" />
		<script type="text/javascript" src="<?php echo $pathToRessources; ?>js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="<?php echo $pathToRessources; ?>js/jquery.history.js"></script>
		<script type="text/javascript" src="<?php echo $pathToRessources; ?>js/jquery.galleriffic.js"></script>
		<script type="text/javascript" src="<?php echo $pathToRessources; ?>js/jquery.opacityrollover.js"></script>
		<!-- We only want the thunbnails to display when javascript is disabled -->
		<script type="text/javascript">
			document.write('<style>.noscript { display: none; }</style>');
		</script>
	</head>
	<body>
		<?php
		    printGallerySource($id);
		    printScriptForSlider();	
		?>	
	</body>
</html>

<?php
}else {
    include("galleries/texts.php");
}
?>
