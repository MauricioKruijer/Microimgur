var Excel    = require('exceljs');
var workbook = new Excel.Workbook();
var firebase = require('firebase');

workbook.creator        = 'MauTech Industries';
workbook.lastModifiedBy = 'TurboApp';
workbook.created        = new Date();
workbook.modified       = new Date();

var worksheet     = workbook.getWorksheet('Microimgur');
worksheet.columns = [
  {header: 'Id', key: 'id', width: 16},
  {header: 'Url', key: 'url', width: 32},
  {header: 'Title', key: 'title', width: 10, outlineLevel: 1}
];

firebase.initializeApp({
  apiKey:        "AIzaSyCtPjk3502PFHUW0-SvbUWE7-qZ56Nn6Qw",
  authDomain:    "woepla-727f7.firebaseapp.com",
  databaseURL:   "https://woepla-727f7.firebaseio.com",
  storageBucket: "woepla-727f7.appspot.com"
});

var timestamp = +new Date();
timestamp     = Math.floor(timestamp / 1000);
timestamp     = timestamp - 24 * 60 * 60;

var filesRef = firebase.database().ref('files');
filesRef.orderByChild('-timestamp');
filesRef.once('value').then(function (snapshot) {
  var posts = snapshot.val();

  var postIds = Object.keys(posts).reverse();
  var counter = 0;
  postIds.forEach(function (postId) {
    var post = posts[postId];
    if (counter <= 10) {
      worksheet.addRow({id: postId, url: 'http://0x7.nl/' + post.url, title: post.title});
    } else {
      worksheet.addRow({id: 'XX', url: 'FREE', title: 'Please pay to see more $$$'});
    }
  });

  workbook.xlsx.writeFile('../public/exports/lalal.xlsx')
    .then(function () {
      process.exit();
    });
  // process.exit();
});
