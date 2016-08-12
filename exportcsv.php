<?php
require_once 'helpers.php';
require_once 'firebase.php';

$file  = sprintf('./exports/%s.csv', randomKeys(10, 'int'));
$fp    = fopen($file, 'w');
$posts = firebase([], 'files', 'GET', ['orderBy' => '"timestamp"']);

if (!empty($posts))
{
  $posts = array_reverse($posts);

  fputcsv($fp, ['id', 'url', 'title'], ';');

  foreach ($posts as $key => $post)
  {
    $fields = [$key,$post['url'],$post['title']];
    fputcsv($fp, $fields, ';');
  }

  fclose($fp);
}
