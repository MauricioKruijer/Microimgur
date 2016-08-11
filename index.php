<?php
require_once 'firebase.php';
$viewCount = firebase([], 'analytics/page_views', 'GET');
firebase(['page_views' => ++$viewCount], 'analytics', 'PATCH');
?>
<!doctype html>
<html class="no-js" lang="">
<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="css/main.css">
</head>
<body>
  <div id="topBar">
    <div class="wrapper">
      <span class="pt-m left">Posts: {{ posts }}</span>
      <button class="mt-m">export</button>
      <span class="pt-m right">Views: {{ views }}</span>
    </div>
  </div>

  <div class="wrapper">
    <div id="replyBox">
      <form action="/upload.php" method="post" enctype="multipart/form-data" name="superform">
        <p>
          <input name="title" type="text" placeholder="Maybe give it a title?"/>
        </p>
        <p>
          <input name="image" type="file" value="Upload image"/>
        </p>
      </form>
    </div>

    <section>
      <h2>{{ image.title }}</h2>
      <img src="{{ image.src }}" alt="{{ image.title }}"/>
    </section>
  </div>

  <script>
    var uploadElem = document.querySelector('input[type=file]');
    uploadElem.onchange = function () {
      uploadElem.form.submit();
    }
  </script>
  <script src="https://www.gstatic.com/firebasejs/3.2.1/firebase.js"></script>
  <script>
    // Initialize Firebase
    var config = {
      apiKey: "AIzaSyCtPjk3502PFHUW0-SvbUWE7-qZ56Nn6Qw",
      authDomain: "woepla-727f7.firebaseapp.com",
      databaseURL: "https://woepla-727f7.firebaseio.com",
      storageBucket: "woepla-727f7.appspot.com",
    };
    firebase.initializeApp(config);

    var database = firebase.database();
  </script>
</body>
</html>
