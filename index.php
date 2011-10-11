<html>
	<head>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8">
		<title>My Gallery</title>
		<link rel="stylesheet"        href="css/basic.css" type="text/css" />
		<link rel="stylesheet"        href="css/galleriffic-3.css" type="text/css" />
		<link rel="stylesheet"        href="css/easyslider.css" type="text/css" />
		<script type="text/javascript" src="js/jquery-1.3.2.js"></script>
		<script type="text/javascript" src="js/jquery.history.js"></script>
		<script type="text/javascript" src="js/jquery.galleriffic.js"></script>
		<script type="text/javascript" src="js/jquery.opacityrollover.js"></script>
        <script type="text/javascript" src="js/easySlider1.7.js"></script>		
		<!-- We only want the thunbnails to display when javascript is disabled -->
		<script type="text/javascript">
			document.write('<style>.noscript { display: none; }</style>');
		</script>
	</head>
	<body>
        <div id="header"> 
            <h1>Easiest Image Gallery</h1>
            <h2>&uuml;berhaupt</h2>
            <div id="navigation">
                <ul>
                   
                </ul>
            </div>
            </div>

	    <div id="slider">
            <ul class="slides">
                <?php
                $response_type = 'php';   //Ausgabe auf php umstellen for Pixlie

                include('php/pixlie.php');
                require("php/gallery.php");

                foreach( $pixlie_table_dir as $dir) {
                    echo "<li class='slide'>";
                    printGallerySource($dir[name]);		
                    echo "</li>";
                }
                ?>
           </ul>
         </div>
         <?php
            printScriptForSlider();		         
         ?>
	</body>
</html>