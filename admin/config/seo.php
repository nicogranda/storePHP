<!-- Google tag (gtag.js) 19/08/2023 Ikusa-->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-ZFZ19G8C6Z"></script>
<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
    gtag('config', 'G-ZFZ19G8C6Z');
</script>

<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";

// Construir la URL actual
$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">


      