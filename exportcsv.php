<?php
require_once 'helpers.php';
require_once 'firebase.php';

$filename = randomKeys(10, 'int') . '.csv';
$file     = sprintf('./exports/%s', $filename);
$fp       = fopen($file, 'w');
$posts    = firebase([], 'files', 'GET', ['orderBy' => '"timestamp"']);

fputcsv($fp, ['id', 'url', 'title'], ';');

if (!empty($posts))
{
  $posts = array_reverse($posts);

  foreach ($posts as $key => $post)
  {
    $fields = [$key, $post['url'], $post['title']];
    fputcsv($fp, $fields, ';');
  }

  fclose($fp);
}
else
{
  fputcsv($fp, ['no', 'files', 'found'], ';');
}

header("Content-type: text/csv");
header("Content-Disposition: attachment; filename=" . $filename);
header("Pragma: no-cache");
header("Expires: 0");

echo file_get_contents($file);
