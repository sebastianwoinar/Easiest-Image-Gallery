<?php
// TODO Pixlie Copyright

	ob_start();
	clearstatcache();
	@ini_set("max_execution_time", 30000);

	$pixlie_imageconfig = array(
		't' => array('type'=>'cut','size'=>'75,75'),
		'b' => array('type'=>'bestfit','size'=>'840,570')	
	);

//[exif_exif_datetimeoriginal]

	$pixlie_sortconfig = array(
		// name, ctime, mtime, atime oder jedes iptc bzw. exif Feld
		'sort_file_first_row'=> "exif_exif_datetimeoriginal",
		// absteigend: SORT_DESC, aufsteigend: SORT_ASC
		'sort_file_first_order'=>SORT_ASC,
		// regulär: SORT_REGULAR, numerisch: SORT_NUMERIC, alphanumerisch: SORT_STRING
		// auf SORT_STRING setzen, wenn EXIF/IPTC Originaldatum (z.B. 'exif_exif_datetimeoriginal') verwendet wird
		'sort_file_first_type'=>SORT_REGULAR,
		// name, ctime, mtime, atime oder jedes iptc bzw. exif Feld
		'sort_file_second_row'=>'mtime',
		// absteigend: SORT_DESC, aufsteigend: SORT_ASC
		'sort_file_second_order'=>SORT_DESC,
		// SORT_REGULAR, SORT_NUMERIC, SORT_STRING
		'sort_file_second_type'=>SORT_NUMERIC,
		// name, ctime, mtime, atime oder jedes iptc bzw. exif Feld
		'sort_dir_first_row'=>'name',
		// absteigend: SORT_DESC, aufsteigend: SORT_ASC
		'sort_dir_first_order'=>SORT_ASC,
		// SORT_REGULAR, SORT_NUMERIC, SORT_STRING
		'sort_dir_first_type'=>SORT_STRING,
		// name, ctime, mtime, atime oder jedes iptc bzw. exif Feld
		'sort_dir_second_row'=>'ctime',
		// absteigend: SORT_DESC, aufsteigend: SORT_ASC
		'sort_dir_second_order'=>SORT_DESC,
		// SORT_REGULAR, SORT_NUMERIC, SORT_STRING
		'sort_dir_second_type'=>SORT_NUMERIC
	);

	$manual_subpath_var = 'bildpfad';							//Variable des manuellen Pfades
	$pixlie_userconfig = array(
		'overwrite_cache'						=> 'off',				//Cache bei jedem Aufruf neu erstellen: on = an, off = aus
		'pic_image_quality'					=> 90,					//Bildqualitaet: 0 = schlechteste , 100 = beste
		'show_metadata_iptc'				=> 'on',				//IPTC-Ausgabe: on = an, off = aus
		'show_metadata_exif'				=> 'on',				//EXIF-Ausgabe: on = an, off = aus
		'dir_cache'									=> dirname(__FILE__).'/cache',	//Pfad zum Cache Verzeichnis
		'dir_upload'								=> dirname(__FILE__).'/../galleries'.
			((isset($manual_subpath)&&$manual_subpath!='')?'/'.$manual_subpath:((isset($_GET[$manual_subpath_var])&&$_GET[$manual_subpath_var]!='')?'/'.$_GET[$manual_subpath_var]:'')),	//Pfad zum Upload Verzeichnis
		'http_method'								=> 'GET',				//Uebermittlungsmethode der Query-Variable: PATHINFO oder GET
		'http_var_name'							=> 'q',					//Name der Query-Variablen die per http_method uebergeben wird
		'response_type'							=> 'php',				//Art der Antwort: direkt = json, include = php
		'relative_path' 						=> '',					//Ist Strato Dein Hoster oder sollten die automatisch generierten Pfade nicht stimmen, trage hier den relativen Pfad ohne abschließenden Slash zur pixlie.php ein (z.B. '/pixlie')
		'use_dims'									=> false,				//Wenn Du die Bildabmessungen in der Ausgabe benötigst, stelle diesen Wert auf true [ true | false ]. Standard: false
		'use_origfilename_as_save'	=> false,				//Originaldateinamen beim Speichern anbieten? [ true | false ]. Auf false stellen, wenn keine Lightbox verwendet wird, da sonst das Bild als Download angeboten und nicht direkt angezeigt wird.
	);

	//Namen der IPTC Felder in der Tabelle "$pixlie_table_file"
	$pixlie_iptc_codes = array(
		'2#005'=>'iptc_object_name',								//Name des Objektes
		'2#007'=>'iptc_edit_status',								//Der Bearbeitungsstatus
		'2#010'=>'iptc_priority',										//Die Prioritaet
		'2#015'=>'iptc_category',										//Die Kategorie
		'2#020'=>'iptc_supplemental_category',			//Zusaetzliche Kategorien wenn vorhanden
		'2#025'=>'iptc_keywords',										//Keywoerter fuer die Suche
		'2#030'=>'iptc_release_date',								//Datum des Bildes
		'2#035'=>'iptc_release_time',								//Uhrzeit des Bildes
		'2#040'=>'iptc_special_instructions',				//Besondere Hinweise zu dem Bild
		'2#045'=>'iptc_reference_service',					//Referenzen auf den Bilderservice
		'2#047'=>'iptc_reference_date',							//Referenzen auf das Datum (Bildarchiv)
		'2#050'=>'iptc_reference_number',						//Referenznummer fuer die Identifikation
		'2#055'=>'iptc_created_date',								//Datum des Fotos
		'2#060'=>'iptc_created_time',								//Uhrzeit des Fotos
		'2#062'=>'iptc_digital_creation_date',			//Datum des Fotos
		'2#063'=>'iptc_digital_creation_time',			//Uhrzeit des Fotos
		'2#065'=>'iptc_originating_program',				//Programm mit dem das Foto erstellt wurde
		'2#070'=>'iptc_program_version',						//Version des Programms
		'2#080'=>'iptc_byline',											//Name des Autors (Fotografen)
		'2#085'=>'iptc_byline_title',								//Titel des Fotografen
		'2#090'=>'iptc_city',												//Stadt
		'2#092'=>'iptc_sublocation',								//Lokation oder Ort
		'2#095'=>'iptc_province_state',							//Bundesland
		'2#100'=>'iptc_country_code',								//Laendercode nach [ISO 3166-1]
		'2#101'=>'iptc_country',										//Laendername
		'2#105'=>'iptc_headline',										//Titel des Fotos
		'2#115'=>'iptc_source',											//Quelle
		'2#116'=>'iptc_copyright',									//Copyright Text
		'2#118'=>'iptc_contact',										//Internetadresse
		'2#120'=>'iptc_caption',										//Beschreibung
		'2#122'=>'iptc_caption_writer',							//Autor der Beschreibung
		'2#150'=>'iptc_content_preview',						//Vorschau
		'2#200'=>'iptc_custom_field_01',						//Frei verwendbare Textfelder
		'2#201'=>'iptc_custom_field_02',						//Frei verwendbare Textfelder
		'2#202'=>'iptc_custom_field_03',						//Frei verwendbare Textfelder
		'2#203'=>'iptc_custom_field_04',						//Frei verwendbare Textfelder
		'2#204'=>'iptc_custom_field_05',						//Frei verwendbare Textfelder
		'2#205'=>'iptc_custom_field_06',						//Frei verwendbare Textfelder
		'2#206'=>'iptc_custom_field_07',						//Frei verwendbare Textfelder
		'2#207'=>'iptc_custom_field_08',						//Frei verwendbare Textfelder
		'2#208'=>'iptc_custom_field_09',						//Frei verwendbare Textfelder
		'2#209'=>'iptc_custom_field_10',						//Frei verwendbare Textfelder
		'2#210'=>'iptc_custom_field_11',						//Frei verwendbare Textfelder
		'2#211'=>'iptc_custom_field_12',						//Frei verwendbare Textfelder
		'2#212'=>'iptc_custom_field_13',						//Frei verwendbare Textfelder
		'2#213'=>'iptc_custom_field_14',						//Frei verwendbare Textfelder
		'2#214'=>'iptc_custom_field_15',						//Frei verwendbare Textfelder
		'2#215'=>'iptc_custom_field_16',						//Frei verwendbare Textfelder
		'2#216'=>'iptc_custom_field_17',						//Frei verwendbare Textfelder
		'2#217'=>'iptc_custom_field_18',						//Frei verwendbare Textfelder
		'2#218'=>'iptc_custom_field_19',						//Frei verwendbare Textfelder
		'2#219'=>'iptc_custom_field_20',						//Frei verwendbare Textfelder
		'2#230'=>'iptc_document_notes',							//Hinweise zu dem Dokument
		'2#231'=>'iptc_document_history',						//Historie des Dokumentes
		'2#232'=>'iptc_exif_camera_info'						//Binaere EXIF Kameradaten (nicht editierbar)
	);


	/*********************************** 1.4 Systemkonfiguration*************************************/

	$pixlie_sysconfig = array(
		'php_req_vers' => '4.0.0',									//erforderliche PHP Version
		'manual_subpath_var' => $manual_subpath_var	//manuelle Pfad. Wird automatisch übernommen.
	);

	/* Bitte aender diese Standardtisierten Bildwerte nicht. Eigene koennen weiter oben unter dem
		 Punkt 1.1 (Bildgroessen) erstellt werden. */
	$pixlie_sys_imageconfig = array(
		'o' => array('type'=>'none', 'size'=>'original')
	);

	$pixlie_image_sizes = array_merge($pixlie_sys_imageconfig,$pixlie_imageconfig);
	$pixlie_config = array_merge($pixlie_userconfig,$pixlie_sortconfig,$pixlie_sysconfig);

	// PHP Remote Config
	if(isset($response_type)){
		switch($response_type){
				case 'php': $pixlie_config['response_type'] = 'php';
										 break;
				case 'json': $pixlie_config['response_type'] = 'json';
										 break;}}


	/*************************************** 2. Sprachausgaben **************************************/

	$pixlie_lang = array(
		'conf_cache_dir_read'			=> "Das Cache-Verzeichnis <b>".$pixlie_config['dir_cache']."</b> existiert nicht.",
		'conf_cache_dir_write'		=> "Cache-Verzeichnis <b>".$pixlie_config['dir_cache']."</b> ok. Pixlie benoetigt jedoch schreibenden Zugriff auf das Cache-Verzeichnis. Bitte das Recht 777 auf das Cache-Verzeichnis setzen.",
		'conf_upload_dir_read'		=> "Das Upload-Verzeichnis <b>".$pixlie_config['dir_upload']."</b> fuer die Bilder existiert nicht.",
		'conf_upload_dir_write'		=> "Upload-Verzeichnis <b>".$pixlie_config['dir_upload']."</b> ok. Pixlie benoetigt jedoch lesenden Zugriff auf das Upload-Verzeichnis. Bitte das Recht 755 auf das Upload-Verzeichnis setzen.",
		'conf_php_vers'						=> "Pixlie benoetigt mindestens PHP in der Version <b>".$pixlie_config['php_req_vers']."</b>.",
		'conf_http_method'				=> "Der Wert http_method <b>(".$pixlie_config['http_method'].")</b> in der Config hat einen falschen Wert.",
		'conf_xss_prot'						=> "Der XSS Schutz hat eine nicht gueltige Zeichenfolge in der Query erkannt.",
		'pic_fileexists_false'		=> "Das angeforderte Bild existiert nicht.",
		'pic_rendertype_false'		=> "Der hinterlegte Rendertyp existiert nicht.",
		'dir_opendir_false'				=> "Das angeforderte Verzeichnis kann nicht gelesen werden.",
		'exif_function_not_exist' => "Die Funktion <b>exif_read_data</b> konnte nicht gefunden werden. Bitte <b>show_metadata_exif</b> in der Pixlie-Konfiguration auf <b>'off'</b> stellen."
	);


	/************************************** 3. Fehlerbehandlung *************************************/

	if(function_exists('pixlie_error')==false){
		function pixlie_error($msg){
			global $pixlie_config;
			clearstatcache();
		if ($pixlie_config['response_type'] == 'json') {
				$pixlie_table_env['status'] = false;
				$pixlie_table_env['errormsg'] = $msg;
				die(json_encode(array('pixlie_table_env'=>$pixlie_table_env)));}
			else{
				die($msg);}}}


	/***************************** 4. Umgebungs- und Installationspruefung ***************************/

	// Windows oder *Nix
	$bIsNix = (preg_match("/^([\/\\\\]|[a-zA-Z]:[\/\\\\])/", __FILE__)) ? false : true;

	//cache check
	if(!(is_dir($pixlie_config['dir_cache']))) {
		pixlie_error($pixlie_lang['conf_cache_dir_read']);}
	elseif(!(is_readable($pixlie_config['dir_cache'])) || !(is_writable($pixlie_config['dir_cache'])) || ($bIsNix && !(is_executable($pixlie_config['dir_cache'])))) {
		pixlie_error($pixlie_lang['conf_cache_dir_write']);}

	//upload check
	if(!(is_dir($pixlie_config['dir_upload']))) {
		pixlie_error($pixlie_lang['conf_upload_dir_read']);}
	elseif(!(is_readable($pixlie_config['dir_upload'])) || ($bIsNix && !(is_executable($pixlie_config['dir_upload'])))) {
		pixlie_error($pixlie_lang['conf_upload_dir_write']);}

	//PHP version-check
	if( phpversion() < $pixlie_config['php_req_vers'] ){
		pixlie_error($pixlie_lang['conf_php_vers']);}


	/****************************** 5. Entgegennehmen der Query-Variable ****************************/

	switch ($pixlie_config['http_method']){
		case 'GET':			if(isset($_GET[$pixlie_config['http_var_name']])){
											 $pixlie_query = $_GET[$pixlie_config['http_var_name']];}
										 else{
											 $pixlie_query = '';}
										 break;
		case 'PATHINFO': if(isset($_SERVER['PATH_INFO'])){
											 $pixlie_query = $_SERVER['PATH_INFO'];}
										 else{
											 $pixlie_query = '';}
										 break;
		default:				 pixlie_error($pixlie_lang['conf_http_method']);
										 break;}


	/********************************* 6. Auf Hackerangriffe pruefen *********************************/

	//cross-site scripting (XSS-Schutz)
	$pixlie_query = preg_replace('/\.\./','',$pixlie_query);
	$pixlie_query = preg_replace('/\/\//','/',$pixlie_query);
	if ((preg_match("/<[^>]*script*\"?[^>]*>/", $pixlie_query)) ||
			(preg_match("/<[^>]*object*\"?[^>]*>/", $pixlie_query)) ||
			(preg_match("/<[^>]*iframe*\"?[^>]*>/", $pixlie_query)) ||
			(preg_match("/<[^>]*applet*\"?[^>]*>/", $pixlie_query)) ||
			(preg_match("/<[^>]*meta*\"?[^>]*>/", $pixlie_query))	 ||
			(preg_match("/<[^>]*style*\"?[^>]*>/", $pixlie_query))	||
			(preg_match("/<[^>]*form*\"?[^>]*>/", $pixlie_query))	 ||
//			(preg_match("/\([^>]*\"?[^)]*\)/", $pixlie_query))			|| // auskommentiert, um Dateinamen mit Klammern zu erlauben
			(preg_match("/\"/", $pixlie_query))) {
				pixlie_error($pixlie_lang['conf_xss_prot']);die();}


	/************************************ 7. Umwandlung in UTF-8 ************************************/

	$pixlie_query = utf8_decode($pixlie_query);


	/*************************************************************************************************
	 ********************************* 8. Verarbeitung eines Fotos ***********************************
	 ************************************************************************************************/

	if(preg_match('/(.jpg)$/i', $pixlie_query)){


	/********************** 8.1 Angeforderte Bildgroesse der Query Variable pruefen ********************/

		if(false !== preg_match('/.*_(.)(\....)/',$pixlie_query,$pixlie_reg_picsize)){
			$pixlie_item_picsize = $pixlie_reg_picsize[1];
			$pixlie_item_extension = $pixlie_reg_picsize[2];
			$pixlie_query = preg_replace('/_'.$pixlie_item_picsize.$pixlie_item_extension.'/' ,
				$pixlie_item_extension,$pixlie_query);}
		else{
			$pixlie_item_picsize = 'd';}


	/************************ 8.2 Cache Dateinamen und Dateipfade generieren ************************/

		$pixlie_item_path	= $pixlie_config['dir_upload'].$pixlie_query;
		$pixlie_cache_name = md5($pixlie_query).'_'.$pixlie_item_picsize.'.jpg';
		$pixlie_cache_path = $pixlie_config['dir_cache'].'/'.$pixlie_cache_name;


	/*************************** 8.3 Pruefen ob Datei schon im Cache liegt ***************************/

		if(file_exists($pixlie_item_path)){
			if((file_exists($pixlie_cache_path)==false)||($pixlie_config['overwrite_cache']=='on')){
				switch ($pixlie_image_sizes[$pixlie_item_picsize]['type']){


	/*************************** 8.3.1 Berechne Bilder nach dem Typ "cut" ***************************/

					case 'cut':
						$pixlie_render_size = explode(',',$pixlie_image_sizes[$pixlie_item_picsize]['size']);
						@ini_set('memory_limit', '500M');
						$src_img = imagecreatefromjpeg($pixlie_item_path);
						if((imagesy($src_img) / imagesx($src_img) * $pixlie_render_size[0]) >$pixlie_render_size[1] ){
							$src_w = imagesx($src_img);
							$src_h = round((imagesx($src_img)/$pixlie_render_size[0])*$pixlie_render_size[1]);
							$src_x = (imagesy($src_img)-$src_h) / 4;
							$src_y = 0;}
						else{
							$src_h = imagesy($src_img);
							$src_w = round((imagesy($src_img)/$pixlie_render_size[1])*$pixlie_render_size[0]);
							$src_y = (imagesx($src_img)-$src_w) / 2;
							$src_x = 0;}
						$dst_img = imagecreatetruecolor($pixlie_render_size[0],$pixlie_render_size[1]);
						imagecopyresampled($dst_img,$src_img,0,0,$src_y,$src_x,$pixlie_render_size[0],
							$pixlie_render_size[1],$src_w,$src_h);
						imagedestroy($src_img);
						imagejpeg($dst_img, $pixlie_cache_path, $pixlie_config['pic_image_quality']);
						imagedestroy($dst_img);
						break;


	/************************** 8.3.2 Berechne Bilder nach dem Typ "uncut" **************************/

					case 'uncut':
						$pixlie_render_size = $pixlie_image_sizes[$pixlie_item_picsize]['size'];
						@ini_set('memory_limit', '500M');
						$src_img = imagecreatefromjpeg($pixlie_item_path);
						if(imagesx($src_img)==imagesy($src_img)){
						    // quadratisch
							$dst_w = $pixlie_render_size;
							$dst_h = $pixlie_render_size;}
						elseif (imagesx($src_img) > imagesy($src_img)){
						    // querformat
							$dst_w = round( $pixlie_render_size / imagesx($src_img) * imagesy($src_img));
							$dst_h = $pixlie_render_size;}
						else{
						    // hochformat
							$dst_w = $pixlie_render_size;
							$dst_h = round($pixlie_render_size / imagesy($src_img) * imagesx($src_img));}
						$dst_img = imagecreatetruecolor($dst_h,$dst_w);
						imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_h,$dst_w,imagesx($src_img),imagesy($src_img));
						imagedestroy($src_img);
						imagejpeg($dst_img, $pixlie_cache_path, $pixlie_config['pic_image_quality']);
						imagedestroy($dst_img);
						break;


	/************************** 8.3.3 Berechne Bilder nach dem Typ "bestfit" ************************/

					case 'bestfit':
						if (strpos($pixlie_image_sizes[$pixlie_item_picsize]['size'],',')) {
							$pixlie_render_size = explode(',',$pixlie_image_sizes[$pixlie_item_picsize]['size']);
						} else {
							$pixlie_render_size = array($pixlie_image_sizes[$pixlie_item_picsize]['size'],$pixlie_image_sizes[$pixlie_item_picsize]['size']);
						}
						@ini_set('memory_limit', '50M');
						$src_img = imagecreatefromjpeg($pixlie_item_path);
						// Ziel-Verhältnis aus Höhe und Breite ermitteln
						$relation = $pixlie_render_size[0]/$pixlie_render_size[1];
						if(imagesx($src_img)==imagesy($src_img)){
							// quadratisches Format: Höhe und Breite auf den kleineren Wert setzen
							$dst_w = min($pixlie_render_size[0],$pixlie_render_size[1]);
							$dst_h = $dst_w;
						} elseif (imagesx($src_img)/imagesy($src_img) == $relation ){
							// Ziel-Verhältnis bereits vorhanden, Werte direkt übernehmen
							$dst_w = $pixlie_render_size[0];
							$dst_h = $pixlie_render_size[1];
						} elseif (imagesx($src_img)/imagesy($src_img) < $relation ){
							// "hochformatiger" als Ziel-Verhältnis, Höhe übernehmen, Breite berechnen
							$dst_w = round( $pixlie_render_size[1] * imagesx($src_img)/imagesy($src_img));
							$dst_h = $pixlie_render_size[1];
						} else {
							// "querformatiger" als Ziel-Verhältnis, Breite übernehmen, Höhe berechnen
							$dst_w = $pixlie_render_size[0];
							$dst_h = round($pixlie_render_size[0] * imagesy($src_img)/imagesx($src_img));
						}
						$dst_img = imagecreatetruecolor($dst_w,$dst_h);
						imagecopyresampled($dst_img,$src_img,0,0,0,0,$dst_w,$dst_h,imagesx($src_img),imagesy($src_img));
						imagedestroy($src_img);
						if ( isset( $plugins['watermark_uncut'] ) && $plugins['watermark_uncut'] ) {
							include( $global_config['pluginspath'].'/watermark/watermark.php' ); // Einbinden des Wasserzeichens
							$dst_img = watermark( $dst_img, 'uncut' ); // Wasserzeichen einbrennen, wenn eingestellt
						}
						if ( isset( $plugins['unsharpmask_uncut'] ) && $plugins['unsharpmask_uncut'] ) {
							include( $global_config['pluginspath'].'/unsharpmask/unsharpmask.php' ); // Einbinden des "Unscharf Maskieren" Filters
							$dst_img = UnsharpMask( $dst_img, 80, 0.7, 1 ); // image res, amount, radius, threshold
						}
						imagejpeg($dst_img, $pixlie_cache_path, $pixlie_config['pic_image_quality']);
						imagedestroy($dst_img);
						break;
					case 'none': $pixlie_cache_path = $pixlie_item_path;
						break;
					default: pixlie_error($pixlie_lang['pic_rendertype_false']);;
						break;}}


	/*********************************** 8.4 Ausgabe des Bildes *************************************/

			$xFilename = explode('/', $pixlie_query);
			$xFilesize = filesize($pixlie_cache_path);
			header("Pragma: public");
			header("Expires: " . gmdate('D, d M Y H:i:s', time()+ 30*24*60*60) . " GMT");
			header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
			header("Cache-Control: private",false);
			header("Content-Type: image/jpeg");
			if ($pixlie_config['use_origfilename_as_save'])
				header("Content-Disposition: attachment; filename=\"".utf8_decode($xFilename[count($xFilename)-1])."\";");
			header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".$xFilesize);
			ob_clean(); // Patch: http://pixlie.de/forum/index.php?page=Thread&postID=2344#post2344
			readfile($pixlie_cache_path);
			//imagejpeg(imagecreatefromjpeg($pixlie_cache_path)); //alternativ
		}
		else{
			pixlie_error($pixlie_lang['pic_fileexists_false']);}}
	else{


	/*************************************************************************************************
	 ******************************** 9. Verarbeitung eines Ordners **********************************
	 ************************************************************************************************/

		$pixlie_table_file = array();
		$pixlie_table_dir = array();
		$pixlie_table_env = array();
		$pixlie_key_counter_file = 0;
		$pixlie_key_counter_dir = 0;
		if($pixlie_dir_handle = @opendir($pixlie_config['dir_upload'].$pixlie_query)){


	/********************************** 9.1 Weiche für Ordner / Bild *********************************/

			while(false !== ($pixlie_item_name = readdir($pixlie_dir_handle))){
				if ($pixlie_item_name != "." && $pixlie_item_name != "..") {
					$pixlie_item_path = $pixlie_config['dir_upload'].$pixlie_query.'/'.$pixlie_item_name;
					if((filetype($pixlie_item_path)=='file')&&(preg_match('/(.jpg)$/i', $pixlie_item_name))){


	/************************************* 9.2 JPG Grunddaten lesen *********************************/

						$pixlie_item_key = $pixlie_key_counter_file;
						$pixlie_key_counter_file ++;

						$pixlie_table_file[$pixlie_item_key]['name'] =
							utf8_encode(preg_replace('/\.jpg/i','',$pixlie_item_name));

						$pixlie_link_file = urlencode($pixlie_table_file[$pixlie_item_key]['name']);
						$pixlie_link_query = preg_replace('/%2F/','/',urlencode(utf8_encode($pixlie_query)));

						if($pixlie_query==''){
							$pixlie_table_file[$pixlie_item_key]['link_get'] =
							'?'.$pixlie_config['http_var_name'].'=/'.$pixlie_link_file;
							$pixlie_table_file[$pixlie_item_key]['link_pathinfo'] =
							'/'.$pixlie_link_file;}
						else{
							$pixlie_table_file[$pixlie_item_key]['link_get'] =
							'?'.$pixlie_config['http_var_name'].'='.$pixlie_link_query.'/'.$pixlie_link_file;
							$pixlie_table_file[$pixlie_item_key]['link_pathinfo'] =
							$pixlie_link_query.'/'.$pixlie_link_file;}

						$pixlie_table_file[$pixlie_item_key]['atime'] = fileatime($pixlie_item_path);
						$pixlie_table_file[$pixlie_item_key]['ctime'] = filectime($pixlie_item_path);
						$pixlie_table_file[$pixlie_item_key]['mtime'] = filemtime($pixlie_item_path);
						$pixlie_table_file[$pixlie_item_key]['size']	= filesize($pixlie_item_path);
						preg_match('/.*(\....)/',$pixlie_item_name,$pixlie_reg_extension);
						$pixlie_table_file[$pixlie_item_key]['extension'] = $pixlie_reg_extension[1];
						if ($pixlie_config['use_dims']) {
							$aDims = getimagesize($pixlie_item_path, $iptc_info);
							$pixlie_table_file[$pixlie_item_key]['width'] = $aDims[0];
							$pixlie_table_file[$pixlie_item_key]['height'] = $aDims[1];
							$pixlie_table_file[$pixlie_item_key]['html'] = $aDims[3];
						}


	/*********************************** 9.3 IPTC Metadaten lesen ***********************************/

						if($pixlie_config['show_metadata_iptc']=='on'){
							if (!$pixlie_config['use_dims'])
								getimagesize($pixlie_item_path, $iptc_info);
							if(isset($iptc_info["APP13"])){
								$iptc_data = iptcparse($iptc_info["APP13"]);
								if(is_array($iptc_data)) {
									foreach ($iptc_data as $iptc_key => $iptc_value){
										if($iptc_key != '2#000'){
											if(count($iptc_value)>1){
												$pixlie_table_file[$pixlie_item_key][$pixlie_iptc_codes[$iptc_key]] =
												utf8_encode(implode(',',$iptc_value));}
											else{
												@$pixlie_table_file[$pixlie_item_key][$pixlie_iptc_codes[$iptc_key]] =
												utf8_encode($iptc_value[0]); }}}}}}


	/*********************************** 9.4 EXIF Metadaten lesen ***********************************/

						if($pixlie_config['show_metadata_exif']=='on') {
							if (function_exists('exif_read_data')) {
								$exif_data = exif_read_data($pixlie_item_path ,1, true);
								if($exif_data!=false){
									foreach ($exif_data as $exif_key => $exif_section) {
										if ((stristr($exif_key,'makernote')== false)) {
											foreach ($exif_section as $exif_name => $exif_value) {
												if((stristr($exif_name,'makernote')== false)&&(stristr($exif_name,'usercomment')== false)&&
													(stristr($exif_name,'undefined')== false)){
														$pixlie_table_file[$pixlie_item_key]['exif_'.strtolower($exif_key).'_'.
															strtolower($exif_name)] = ((is_array($exif_value))?$exif_value:utf8_encode($exif_value)); }}}}} //$exif_value could be array
							} else
								pixlie_error($pixlie_lang['exif_function_not_exist']);
					}} elseif(filetype($pixlie_item_path)=='dir'){


	/*********************************** 9.5 Ordner Grunddaten lesen ********************************/

						$pixlie_item_key = $pixlie_key_counter_dir;
						$pixlie_key_counter_dir ++;

						$pixlie_link_file = urlencode(utf8_encode($pixlie_item_name));
						$pixlie_link_query = preg_replace('/%2F/','/',urlencode(utf8_encode($pixlie_query)));

						if($pixlie_query==''){
							$pixlie_table_dir[$pixlie_item_key]['link_get'] =
							'?'.$pixlie_config['http_var_name'].'=/'.$pixlie_link_file;
							$pixlie_table_dir[$pixlie_item_key]['link_pathinfo'] = '/'.$pixlie_link_file;}
						else{
							$pixlie_table_dir[$pixlie_item_key]['link_get'] =
							'?'.$pixlie_config['http_var_name'].'='.$pixlie_link_query.'/'.$pixlie_link_file;
							$pixlie_table_dir[$pixlie_item_key]['link_pathinfo'] =
								$pixlie_link_query.'/'.$pixlie_link_file;}
						$pixlie_table_dir[$pixlie_item_key]['name'] = utf8_encode($pixlie_item_name);
						$pixlie_table_dir[$pixlie_item_key]['atime'] = fileatime($pixlie_item_path);
						$pixlie_table_dir[$pixlie_item_key]['ctime'] = filectime($pixlie_item_path);
						$pixlie_table_dir[$pixlie_item_key]['mtime'] = filemtime($pixlie_item_path);}}}

			closedir($pixlie_dir_handle);
			$pixlie_table_env['status'] = true;
			$pixlie_table_env['numberof_file'] = count($pixlie_table_file);
			$pixlie_table_env['numberof_dir']	= count($pixlie_table_dir);
			$pixlie_table_env['query']	= utf8_encode($pixlie_query);

			$__FILE__ = (substr($_SERVER["DOCUMENT_ROOT"], 0, strlen('/kunden')) == '/kunden') ? '/kunden'.__FILE__ : __FILE__; // 1&1 workaround
			if ( $pixlie_config['relative_path'] != '' ) {
				$relpath = $pixlie_config['relative_path'].((substr($pixlie_config['relative_path'], -1) != '/') ? '/' : '');
			} else {
				$fullpath = str_replace('\\', '/', dirname($__FILE__));
				$fullpath = $fullpath.((substr($fullpath, -1) != '/') ? '/' : '');
				$istartpos = strpos($fullpath, $_SERVER["DOCUMENT_ROOT"]);
				$iendpos = $istartpos + strlen(str_replace('\\', '/', $_SERVER["DOCUMENT_ROOT"]));
				$relpath = substr($fullpath, $iendpos);
				$relpath .= (substr($relpath, -1) != '/') ? '/' : '';
			}
			$pixlie_table_env['path_pixlie'] = 'http://'.$_SERVER['HTTP_HOST'].
				((substr($_SERVER['HTTP_HOST'],-1) != '/' && substr($relpath,0,1) != '/')?'/':'').
				$relpath.basename($__FILE__);
		}
		else{
				pixlie_error($pixlie_lang['dir_opendir_false']);}


	/******************************* 9.6 Sortierung der Datentabellen *******************************/

		if(count($pixlie_table_file)>1){
			foreach ($pixlie_table_file as $pixlie_sort_key => $pixlie_sort_row){
				$pixlie_first_sort_row[$pixlie_sort_key] = @$pixlie_sort_row[$pixlie_config['sort_file_first_row']];
				$pixlie_second_sort_row[$pixlie_sort_key] = @$pixlie_sort_row[$pixlie_config['sort_file_second_row']];}
			array_multisort($pixlie_first_sort_row,	$pixlie_config['sort_file_first_order'],
											$pixlie_config['sort_file_first_type'], $pixlie_second_sort_row,
											$pixlie_config['sort_file_second_order'], $pixlie_config['sort_file_second_type'],
											$pixlie_table_file);
			unset($pixlie_first_sort_row,$pixlie_second_sort_row);}
		if(count($pixlie_table_dir)>1){
			foreach ($pixlie_table_dir as $pixlie_sort_key => $pixlie_sort_row){
				$pixlie_first_sort_row[$pixlie_sort_key] = @$pixlie_sort_row[$pixlie_config['sort_dir_first_row']];
				$pixlie_second_sort_row[$pixlie_sort_key] = @$pixlie_sort_row[$pixlie_config['sort_dir_second_row']];}
			array_multisort($pixlie_first_sort_row,	$pixlie_config['sort_dir_first_order'],
											$pixlie_config['sort_dir_first_type'], $pixlie_second_sort_row,
											$pixlie_config['sort_dir_second_order'], $pixlie_config['sort_dir_second_type'],
											$pixlie_table_dir);
			unset($pixlie_first_sort_row,$pixlie_second_sort_row);}


	/************************************* 9.7 Ausgabe via JSON *************************************/

	 switch($pixlie_config['response_type']){
		 case 'php':
				 //no content output. use the var $pixlie_table_file and $pixlie_table_dir in your php file
			 break;
		 case 'json':
			 header("Content-Type: text/html; charset=utf-8");
			 $json = array(
				 'pixlie_table_env'=> $pixlie_table_env,
				 'pixlie_table_dir'=> $pixlie_table_dir,
				 'pixlie_table_file'=> $pixlie_table_file);
			 echo json_encode($json);
			 break;}

	}
	ob_flush(); //Ausgabe des Puffers
?>