<?php

namespace Acme\TesttaskBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\DomCrawler\Crawler;
//use Symfony\Component\BrowserKit\Client;
use Goutte\Client;

class TaskController extends Controller
{
	public function indexAction()
	{
		//$array_of_nodes - Все блоки для получения случайного из них
		//$random_item - Случайное значение из элементов

		$start_time = microtime(true);

		$client = new Client();
		$crawler = $client->request('GET','http://www.buycarparts.co.uk/bmw');

		//Нахожу все div-блоки моделей BMW
		$array_of_nodes = $crawler->filter('div.ersatzteile_cars')->children();

		//Получаю случайное значение для блока
		$random_item = rand(0, count($array_of_nodes)-2);

		/*//Получаю id случайного блока
		$id_of_block = $crawler->filter('div.ersatzteile_cars')
			->children()->eq($random_item)->attr('id');*/

		//Нахожу случайный блок и возвращаю объект - линк
		$link = $crawler->filter('div.ersatzteile_cars')->children()
			->eq($random_item)->filter('a')->link();

		//Производится клик
		$crawler = $client->click($link);

		$array_of_nodes = $crawler->filter('.vehicles')->first()->children();

		$random_item = rand(0, count($array_of_nodes)-1);

		$link = $crawler->filter('.vehicles')->first()->children()
			->eq($random_item)->filter('a')->link();
		$crawler = $client->click($link);

		$array_of_nodes = $crawler->filter('.vehicles')->first()->children();

		$random_item = rand(1, count($array_of_nodes)-1);

		$link = $crawler->filter('.vehicles')->first()->children()
			->eq($random_item)->filter('a')->link();

		$crawler = $client->click($link);

		//Страница с множеством деталей
		$array_of_nodes = $crawler->filter('#content .details')->children();

		$random_item = rand(4, count($array_of_nodes)-1);

		$link = $crawler->filter('#content .details')->children()
			->eq($random_item)->filter('a')->link();

		$crawler = $client->click($link);

		//Дот сих пор работает!

		/*$response = $client->getResponse();
		echo "<br>".$response;*/

		//Страница с товарами
		$array_of_nodes = $crawler->filter('#content .products')->children()->filter('.brand-products');

		$random_item = rand(0, count($array_of_nodes)-1);

		//id бренда для облегченного поиска
		$id_of_part = $crawler->filter('#content .products')->children()->eq($random_item)->attr('id');

		$array_of_nodes = $crawler->filter('#'.$id_of_part)->children();
		$random_item = rand(0, count($array_of_nodes)-1);

		//Ссылка на случайный товар
		$link_of_the_part = $crawler->filter('#'.$id_of_part)->children()->eq($random_item)
			->filter('.description .prod_link')->attr('href');

		//article_id Товара
		$article_id = $crawler->filter('#'.$id_of_part)->children()->eq($random_item)
			->selectLink('Add to basket')->parents()->attr('id');

		/*$client->request('POST',
			'http://www.buycarparts.co.uk/ajax/cart/update?article_id='.$article_id.'&qty=1');*/

		//Отправка запроса на добавление товара в корзину
		$client->request('POST',
			'http://www.buycarparts.co.uk/ajax/cart/update', array('article_id' => $article_id, 'qty' => 1));

		//Дот сих пор работает 2!

		/*$response = $client->getResponse();
		echo "<br>".$response;*/

		$crawler = $client->request('GET','https://www.buycarparts.co.uk/basket');
		$basket_item_link = $crawler->filter('a[href="'.$link_of_the_part.'"]')->attr('href');

		$time = microtime(true) - $start_time;

		$values = array(
			'link' => (string) $link_of_the_part,
			'article_id' => $article_id,
			'basket_item_link' => $basket_item_link,
			'time' => $time,
		);

		/*echo "<hr>";
		echo "LOL! FINAL!";
		echo "<hr>";

		//echo $final_link;
		echo "<br>".$link_of_the_part;
		echo "<br>".$final_link;

		$response = $client->getResponse();
		echo "<br>".$response;*/

		return $this->render('AcmeTesttaskBundle:Task:index.html.php',array('item' => $values));
	}
}