<?php
define('MAX_IMAGE_WIDTH', 1920);
define('MAX_IMAGE_HEIGHT', 1080);
define('MAX_FILE_SIZE', 2048 * 1024);

require_once 'helpers.php';
require_once 'firebase.php';

if (!isset($_FILES['image']))
{
  showResult([
    'error' => [
      'message' => _('No file found'),
    ],
  ]);
}

$image = $_FILES['image'];

if (($fileInfo = @getimagesize($image['tmp_name'])) == false)
{
  showResult([
    'error' => [
      'message' => _('File info is not available, could not check image'),
    ],
  ]);
}

if ($fileInfo[0] > MAX_IMAGE_WIDTH && $fileInfo[1] > MAX_IMAGE_HEIGHT)
{
  showResult([
    'error' => [
      'message' => _('Image resolution is to large'),
    ],
  ]);
}

if (!isset($fileInfo['mime']) || !in_array($fileInfo['mime'], ['image/jpeg', 'image/jpg']))
{
  showResult([
    'error' => [
      'message' => gettext('Type not supported'),
    ],
  ]);
}

if (isset($image['size']) && $image['size'] > MAX_FILE_SIZE)
{
  showResult([
    'error' => _(sprintf(
      'This file is too big (%s), max file size is: %s',
      human_filesize($image['size']),
      human_filesize(MAX_FILE_SIZE))),
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
  $imageUrl = str_replace('./', '/', $filename);

  if ($response = firebase([
    'title'      => isset($_POST['title']) ? htmlentities($_POST['title']) : '',
    'url'        => $imageUrl,
    'timestamp'  => time(),
    'created_at' => date('c'),
  ])
  )
  {
    if (isset($response['name']) && !empty($response['name']))
    {
      $postCount = firebase([], 'analytics/post_count', 'GET');
      firebase(['post_count' => ++$postCount], 'analytics', 'PATCH');
    }
    else
    {
      $postCount = 'leeeeegggg';
    }
    showResult([
      'image'     => [
        'src' => $imageUrl,
      ],
      'firebase'  => $response,
      'postcount' => $postCount,
    ]);
  }
  else
  {
    showResult([
      'error' => [
        'message' => _('Something went wrong g!'),
      ],
    ]);
  }
}
