<?php
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
  return sprintf('./uploads/%s.jpg', randomKeys(10, 'int'));
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
