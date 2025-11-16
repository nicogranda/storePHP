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
$currentUrl = $protocol . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="robots" content="index, follow">
<link rel="canonical" href="<?php echo htmlspecialchars($currentUrl, ENT_QUOTES, 'UTF-8'); ?>">
<meta name="author" content="Ikusa">
<meta name="publisher" content="Ikusa">


<?php
$stmt = $mysqli->prepare("SELECT title, description, keywords, og_title, og_description, og_image, twitter_title, twitter_description, twitter_image FROM seo WHERE page = ?");
$stmt->bind_param("s", $page);
$stmt->execute();
$result = $stmt->get_result();
$seoData = $result->fetch_assoc() ?: [
    'title' => 'Página no encontrada',
    'description' => 'Lo sentimos, la página solicitada no está disponible.',
    'keywords' => '',
    'og_title' => '',
    'og_description' => '',
    'og_image' => '',
    'twitter_title' => '',
    'twitter_description' => '',
    'twitter_image' => ''
];
$stmt->close();
?>


      