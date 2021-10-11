var clipboard = new ClipboardJS('.copy-cite');

clipboard.on('success', function (e) {
  console.log(e);
});

clipboard.on('error', function (e) {
  console.log(e);
});
