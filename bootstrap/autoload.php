<?php
ini_set('post_max_size', '20M');
ini_set('upload_max_filesize', '208M');
ini_set('zlib.output_compression', 'On');

define('MAX_IMAGE_WIDTH', 1920);
define('MAX_IMAGE_HEIGHT', 1080);
define('MAX_FILE_SIZE', 2048 * 1024);

require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/firebase.php';
