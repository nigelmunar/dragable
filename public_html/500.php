<?php
    http_response_code(500);
?>

<link href="/style.css?v=0.0.1" rel="stylesheet" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0" />

<div class="error-page">
	<img src="/images/logo.png" alt="VegTrug" class="error-page__logo-img" />
	<h1 class="error-page__title">500</h1>
	<div class="error-page__text">Sorry, this page has errored, please try again or click one of the links below.</div>
</div>

<?php
    exit();