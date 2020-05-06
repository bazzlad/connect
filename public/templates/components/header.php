<!DOCTYPE html>
<html lang="en">
	<head>
  <!-- Meta, title, CSS, favicons, etc. -->
  <meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="description" content="">
	<meta name="keywords" content="">


	<meta name="version" content="<?php echo $GLOBALS['version']; ?>">

	<!--<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png?v=<?php echo $GLOBALS['version']; ?>">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png?v=<?php echo $GLOBALS['version']; ?>">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png?v=<?php echo $GLOBALS['version']; ?>">
	<link rel="manifest" href="/site.webmanifest?v=<?php echo $GLOBALS['version']; ?>">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">-->

	<title>Connect</title>

<?php
  // new builder to help with caching
  $pageStyles = array(
    "/content/css/bootstrap.min.css",
    "/content/css/line-awesome.min.css",
    "/content/css/main.css"
  );

  buildStyles($pageStyles);
?>