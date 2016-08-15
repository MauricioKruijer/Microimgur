<?php
namespace Microimgur;

error_reporting(E_ALL | E_STRICT);
ini_set('display_errors', true);

require_once __DIR__ . '/../bootstrap/autoload.php';

function move_uploaded_file($filename, $destination)
{
  return copy($filename, $destination);
}
