<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Music Player</title>
<link rel="stylesheet" href="98.css">
<link rel="stylesheet" href="style.css">
<script src="script.js" defer></script>
<script src="htmx.min.js"></script>
</head>
<body>
<div class="window-body">
<menu role="tablist" main="">
<li role="tab"><a href="#library">Library</a></li>
<li role="tab"><a href="#playlists">Playlists</a></li>
<li role="tab"><a href="#playing">Currently Playing</a></li>
<li role="tab"><a href="#queue">Queue</a></li>
</menu>
<div class="window" role="tabpanel" main="">
<div class="window-body library">
<?php include("library.php"); ?>
</div>
<div class="window-body playlists">
<p>Playlists</p>
</div>
<div class="window-body playing">
<p>Currently Playing</p>
</div>
<div class="window-body queue">
<p>Queue</p>
</div>
</div>
</div>
</body>
</html>