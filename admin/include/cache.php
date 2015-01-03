<?php
/*	CSS & JS CACHE							*/
/*	License: Revised BSD					*/
/*	Based on the work by Michael Brown 		*/
require_once('constants.php');


$config = array();


// where to store combine files; needs to be writable to use (recommended) or set to false to not use
$config['cache_path'] = '../cache'; // false, /tmp, 'cache', etc - no trailing slash

// secs to keep files in cache
$config['cache_time'] = CACHER; // 86400 1 day; 604800 7 days; 2592000 30 days

// public (shared cache ok; proxy); private (non-shared caches)
$config['cache_control_access'] = 'public';

// compression method based on browser support; gzip, deflate, or none
if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
	$config['compress_method'] = 'none';
} else if (stristr($_SERVER['HTTP_ACCEPT_ENCODING'], 'gzip')) {
	$config['compress_method'] = 'gzip';
} else if (stristr($_SERVER['HTTP_ACCEPT_ENCODING'], 'deflate')) {
	$config['compress_method'] = 'deflate';
} else {
	$config['compress_method'] = 'none';
}
// $config['compress_method'] = 'gzip'; // override



// $config['sources'] - list your files 
if (isset($_REQUEST['js'])) {
		$config['type'] = 'js';

	// CORE JS Files
	if ($_REQUEST['js'] == 'library') {
	    $asset_dir = '../js/';
		$config['sources'] = array(
            $asset_dir.'ga.js',
		    $asset_dir.'vendor/modernizr-2.6.2-respond-1.1.0.min.js',
            $asset_dir.'vendor/jquery.min.js',
            $asset_dir.'vendor/bootstrap.min.js',
			$asset_dir.'featherlight.js',
            $asset_dir.'plugins.js',
            $asset_dir.'main.js'
		);
}
     else if (strpos('|', $_REQUEST['js']) !== false) {
		$asset_dir = '../js/';
		$srcs = explode('|', $_REQUEST['js']);
		$config['sources'] = array();
		foreach ($srcs as $src) {
			$config['sources'][] = $asset_dir.$src;
		}

	} else {
		header($_SERVER['SERVER_PROTOCOL'].' 503 Unknown type of js');
		exit();
	}


} else if (isset($_REQUEST['css'])) {
	$config['type'] = 'css';

	if ($_REQUEST['css'] == 'styles') {
		$asset_dir = '../css/';

		$config['sources'] = array(
			$asset_dir.'font-awesome.css',
			$asset_dir.'animate.css',
            $asset_dir.'bootstrap.min.css',
            $asset_dir.'slate.min.css',
            $asset_dir.'editor.css',
			$asset_dir.'admin.css'
		);

}
	else if ($_REQUEST['css'] == 'frontcss') {
		$asset_dir = '../css/';

		$config['sources'] = array(
			$asset_dir.'font-awesome.css',
			$asset_dir.'animate.css',
            $asset_dir.'bootstrap.min.css',
            $asset_dir.'cosmo.min.css',
            $asset_dir.'editor.css',
			$asset_dir.'main.css'
		);

}


else if (strpos('|', $_REQUEST['css']) !== false) {
		$asset_dir = '../css/';
		$srcs = explode('|', $_REQUEST['css']);
		$config['sources'] = array();
		foreach ($srcs as $src) {
			$config['sources'][] = $asset_dir.$src;
		}

	} else {
		header($_SERVER['SERVER_PROTOCOL'].' 503 Unknown type of js');
		exit();
	}


} else {
	header($_SERVER['SERVER_PROTOCOL'].' 503 Neither css nor js');
	exit();
}


// set content type; must match what your webserver serves; defaults should be ok
if ($config['type'] == 'css') {
	$config['content_type'] = 'text/css';
} else if ($config['type'] == 'js') {
	$config['content_type'] = 'text/javascript';
} else {
	$config['content_type'] = 'text/plain';
}

//
// end config
//





//
// some basic validation of config/parms
//

// try to make sure directory for storing cache exists, if possible
if (!is_dir($config['cache_path'])) {
	@mkdir($config['cache_path']);
	if (!is_dir($config['cache_path'])) {
		// could not find nor create cache dir, so do not use
		// even w/o cache, still reduces bandwidth and requests, just more i/o
		$config['cache_path'] = false;
	}
}
$using_cache = !empty($config['cache_path']);

// only combine js and css flair
if (!in_array($config['type'], array('js', 'css'))) {
	header($_SERVER['SERVER_PROTOCOL'].' 503 Type not implemented');
	exit();
}

// only gzip, deflate and none are valid compresions
if (!in_array($config['compress_method'], array('gzip', 'deflate', 'none'))) {
	header($_SERVER['SERVER_PROTOCOL'].' 503 Compress method not implemented');
	exit();
}

// sources required
if (empty($config['sources'])) {
	header($_SERVER['SERVER_PROTOCOL'].' 503 No sources to cache');
	exit();
}




//
// start
//



$now = time();
$usable_files = array();
$last_modifieds = array();
$file_sizes = array();

// get info about flair
foreach ($config['sources'] as $file) {
	if (is_file($file)) {
		$usable_files[] = $file;
		$last_modifieds[] = filemtime($file);
		$file_sizes[] = filesize($file);
	}
}
unset($config['sources']);
$nbr_files = count($usable_files);

// check if actually found any flair to combine
if ($nbr_files == 0) {
	header($_SERVER['SERVER_PROTOCOL'].' 503 Nothing to cache');
	exit();
}

$last_modified = max($last_modifieds);
$file_size = array_sum($file_sizes);
unset($last_modifieds);
unset($file_sizes);


// build a filename based on flair
$combined_file = $config['cache_path'].'/redicon_'.$config['type'].'_'.$nbr_files.'_'.$file_size.'_'.$last_modified;
if ($config['compress_method'] == 'gzip') {
	$combined_file .= '.gz';
} else if ($config['compress_method'] == 'deflate') {
	$combined_file .= '.df';
} else {
	$combined_file .= '.txt';
}

$combined_exists = file_exists($combined_file);
if ($combined_exists) {
	$combined_modified = filemtime($combined_file);
} else {
	$combined_modified = 0;
}


// generate etag for header
$etag = md5($combined_file.$_SERVER['HTTP_HOST']);
// create the last modified timestamp for header
$last_modified_gmt = gmdate('D, d M Y H:i:s', $last_modified).' GMT';



// if using server side cache,
// and not in server side cache or cache is old, then do not use browser cache
// allows server side to force browser refetch of js/css
if ($using_cache && (!$combined_exists || $combined_modified < $last_modified)) {
	$try_browser_cache = false;
} else {
	$try_browser_cache = true;
}

// see if the user has a current copy in browser cache
if ($try_browser_cache) {
	// check etag against browser
	if (isset($_SERVER['HTTP_IF_NONE_MATCH']) && str_replace('"', '', stripslashes($_SERVER['HTTP_IF_NONE_MATCH'])) == $etag) {
		header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
		exit();
	}

	// check modified time against browser
	if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
		$modified_since = $_SERVER['HTTP_IF_MODIFIED_SINCE'];
		if (false !== ($semicolon = strrpos($modified_since, ';'))) {
			// ie has tacked on extra data to this header, strip it
			$modified_since = substr($modified_since, 0, $semicolon);
		}
		if ($modified_since == $last_modified_gmt) {
			header($_SERVER['SERVER_PROTOCOL'].' 304 Not Modified');
			exit();
		}
	}
}


//
// build and/or use cache
//

// get code from cache if it exists,
// otherwise grab latest flair, merge and save in cache
if (0 && $using_cache && $combined_exists && $combined_modified >= $last_modified) {
	$combined_contents = file_get_contents($combined_file);
	$combined_filesize = filesize($combined_file);

} else {

	// build cache
	$combined_contents = '';
	foreach ($usable_files as $file_path) {
		// to make debugging combined file somewhat easier, add file tags
		$combined_contents .= "\n".'/*===start '.$file_path.' start===*/'."\n";
		$file_contents = file_get_contents($file_path);
		if ($file_contents !== false) {
			$combined_contents .= $file_contents."\n";
		} else {
			$combined_contents .= '/*===error reading '.$file_path.' ===*/'."\n";
		}
		$combined_contents .= "\n".'/*===end '.$file_path.' end===*/'."\n\n";
	}
	unset($usable_files);

	// compress contents
	// higher compression means smaller file, but longer process time; 0 none; 9 max
	if ($config['compress_method'] == 'gzip') {
		$combined_contents = gzencode($combined_contents, 9, FORCE_GZIP);
	} else if ($config['compress_method'] == 'deflate') {
		$combined_contents = gzencode($combined_contents, 9, FORCE_DEFLATE);
	}

	if ($using_cache) {

		// keep cache dir clean
		if ($dh = opendir($config['cache_path'])) {
			while (($file = readdir($dh)) !== false) {
				if (strlen($file) < 8 || substr($file, 0, 8) != 'phpflair') {
					continue;
				}
				if (@filemtime($config['cache_path'].'/'.$file) < $now - $config['cache_time']) {
					@unlink($config['cache_path'].'/'.$file);
				}
			}
			closedir($dh);
		}

		// write new cache
		$cache_created = false;
		if ($fh = fopen($combined_file, 'w')) {
			if (flock($fh, LOCK_EX)) {
				$cache_created = fwrite($fh, $combined_contents);
				flock($fh, LOCK_UN);
			}
			fclose($fh);
		}

		if ($cache_created) {
			$combined_filesize = $cache_created;
		} else {
			$combined_filesize = strlen($combined_contents);
		}
	} else {
		$combined_filesize = strlen($combined_contents);
	}
}


//
// send headers for combined flair
//
header('Expires: '.gmdate('D, d M Y H:i:s', $now + $config['cache_time']).' GMT');
header('Cache-Control: max-age='.$config['cache_time'].', '.$config['cache_control_access'].', must-revalidate');
header('Content-Type: '.$config['content_type']);
header('Content-Length: '.$combined_filesize);
if ($config['compress_method'] == 'gzip') {
	header('Content-Encoding: gzip');
} else if ($config['compress_method'] == 'deflate') {
	header('Content-Encoding: deflate');
}
header('Last-Modified: '.$last_modified_gmt);
header('ETag: "'.$etag.'"');
header('Vary: Accept-Encoding');

echo $combined_contents;

exit();
?>
