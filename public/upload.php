<?php
require __DIR__ . '/../bootstrap/autoload.php';
try
{
  $imageUpload = new FileUpload('image');
  if ($imageUpload->validateErrorCode())
  {

    $imageUpload->validateSize();
    $imageUpload->validateName();

    $imageUpload->readFileInfo();

    $imageUpload->validateMimeType();
    $imageUpload->validateResolution();

    do
    {
      $filename = randomFilename();
    }
    while (file_exists($filename));

    if ($imageUpload->saveFile($filename))
    {
      $title = isset($_POST['title']) ? htmlentities($_POST['title']) : '';

      if (list($postCount, $imageUrl, $response) = saveImageToFirebase($filename, $title))
      {
        header('Location: ' . URL);
        exit;
//        showResult([
//          'image'     => [
//            'src' => $imageUrl,
//          ],
//          'firebase'  => $response,
//          'postcount' => $postCount,
//        ]);
      }

      showResult([
        'error' => [
          'message' => _('Something went wrong g!'),
        ],
      ]);
    }
  }
}
catch (RuntimeException $e)
{
  showResult(['error' => $e->getMessage()]);
}

if (!function_exists('_'))
{
  function _($v)
  {
    return $v;
  }
}

function saveImageToFirebase($filename, $title)
{
  $imageUrl  = str_replace('./', '/', $filename);
  $image     = [
    'title'      => $title,
    'url'       => $imageUrl,
    'timestamp' => time(),
    'created_at' => date('c'),
  ];

  if ($response = firebase($image))
  {
    if (isset($response['name']) && !empty($response['name']))
    {
      $postCount = firebase([], 'analytics/post_count', 'GET');
      firebase(['post_count' => ++$postCount], 'analytics', 'PATCH');
      return [$postCount, $imageUrl, $response];
    }
  }
  else
  {
    return false;
  }
}
