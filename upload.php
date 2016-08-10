<?php
function showResult($messages)
{
  header('Content-Type: application/json');
  echo json_encode($messages, true);
  exit;
}

if (isset($_FILES['image']))
{
  $image = $_FILES['image'];
  if (!isset($image['type']) || !in_array($image['type'], ['image/jpeg', 'image/jpg']))
  {
    showResult([
      'error' => [
        'message' => gettext('Type not supported')
      ]
    ]);
  }

  if (!empty($image['error']))
  {
    showResult([
      'error' => [
        'message' => _('Something went wrong while uploading your file')
      ]
    ]);
  }

  if (empty($image['name']))
  {
    showResult([
      'error' => [
        'message' => _('Image must have a name')
      ]
    ]);
  }

  if (move_uploaded_file($image['tmp_name'], './uploads/' . $image['name']))
  {
    showResult([
      'image'=> [
        'src' => 'url'
      ]
    ]);
  }
}

showResult([
  'error' => [
    'message' => _('No file found')
  ]
]);
