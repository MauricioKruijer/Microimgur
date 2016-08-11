<?php
if (!extension_loaded('curl'))
{
  trigger_error('Extension CURL is not loaded.', E_USER_ERROR);
}
function firebase($data, $resource = 'files', $mode = 'POST')
{
  $url      = sprintf('https://woepla-727f7.firebaseio.com/%s.json', $resource);
  $timeout  = 10;
  $ch       = curl_init();
  $jsonData = json_encode($data);

  $header = array(
    'Content-Type: application/json',
    'Content-Length: ' . strlen($jsonData),
  );

  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);

  if ($mode !== 'GET')
  {
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $mode);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
  }
  try
  {
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $return = curl_exec($ch);
    $return = json_decode($return, true);
  } catch (Exception $e)
  {
    $return = null;
    throw  $e;
  }

  curl_close($ch);

  return $return;
}
