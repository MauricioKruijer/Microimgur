<?php
define('MAX_IMAGE_WIDTH', 1920);
define('MAX_IMAGE_HEIGHT', 1080);
define('MAX_FILE_SIZE', 2048 * 1024);

function showResult($messages)
{
  header('Content-Type: application/json');
  echo json_encode($messages, true);
  exit;
}

function randomKeys($length, $patternType = 'mix')
{
  if ($patternType === 'int')
  {
    $pattern = "1234567890";
  }
  elseif ($patternType === 'str')
  {
    $pattern = "abcdefghijklmnopqrstuvwxyz";
  }
  else
  {
    $pattern = "1234567890abcdefghijklmnopqrstuvwxyz";
  }
  $count = strlen($pattern) - 1;
  for ($i = 0; $i < $length; $i++)
  {
    if (isset($key))
    {
      $key .= $pattern{rand(0, $count)};
    }
    else
    {
      $key = $pattern{rand(0, $count)};
    }
  }

  return $key;
}

function randomFilename()
{
  return sprintf('./uploads/%s.jpg', randomKeys(1, 'int'));
}

/*
 * Borrowed from http://jeffreysambells.com/2012/10/25/human-readable-filesize-php
 */
function human_filesize($bytes, $decimals = 2)
{
  $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
  $factor = floor((strlen($bytes) - 1) / 3);

  return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . @$size[ $factor ];
}

if (!isset($_FILES['image']))
{
  showResult([
    'error' => [
      'message' => _('No file found'),
    ],
  ]);
}

$image = $_FILES['image'];

if (isset($image['size']) && $image['size'] > MAX_FILE_SIZE)
{
  showResult([
    'error' => _(sprintf('This file is too big (%s), max file size is: %s', human_filesize($image['size']) ,human_filesize(MAX_FILE_SIZE))),
  ]);
}

if (!isset($image['type']) || !in_array($image['type'], ['image/jpeg', 'image/jpg']))
{
  showResult([
    'error' => [
      'message' => gettext('Type not supported'),
    ],
  ]);
}

if (!empty($image['error']))
{
  showResult([
    'error' => [
      'message' => _('Something went wrong while uploading your file'),
    ],
  ]);
}

if (empty($image['name']))
{
  showResult([
    'error' => [
      'message' => _('Image must have a name'),
    ],
  ]);
}

do
{
  $filename = randomFilename();
}
while (file_exists($filename));

if (move_uploaded_file($image['tmp_name'], $filename))
{
  showResult([
    'image' => [
      'src' => str_replace('.', '', $filename),
    ],
  ]);
}
