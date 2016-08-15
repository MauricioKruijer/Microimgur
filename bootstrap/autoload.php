<?php
ini_set('post_max_size', '20M');
ini_set('upload_max_filesize', '208M');
ini_set('zlib.output_compression', 'On');

define('URL', 'http://0x7.nl');

require __DIR__ . '/../src/helpers.php';
require __DIR__ . '/../src/firebase.php';
require __DIR__ . '/../src/FileUpload.php';
