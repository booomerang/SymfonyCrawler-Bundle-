<!DOCTYPE html>
<html>
<head>
    <title>Тестовое задание</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
</head>
<body>
<div id="wrapper">
	<p>УРЛ товара: <b>{{ item.link|raw }}</b></p>
	<p>ID товара:  <b>{{ item.article_id }}</b></p>
	<p>УРЛ товара на странице Корзины:  <b>{{ item.basket_item_link }}</b></p>
	<p><b>Время выполнения скрипта: {{ item.time }}</b></p>
</div>
</body>
</html>