<?php

namespace Acme\TesttaskBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
	public function testIndex()
	{
		$client = static::createClient();

		$crawler = $client->request('GET','http://www.buycarparts.co.uk/bmw');

		//$link = $crawler->filter('a:contains("Greet")')->eq(1)->link();

		//Нахожу все div-блоки моделей BMW
		$array_of_nodes = $crawler->filter('div.ersatzteile_cars');

		//Получаю случайное число для блока из этого числа
		$random_model = rand(0, count($array_of_nodes));

		//Получаю id случайного блока
		$id_of_block = $crawler->filter('div.ersatzteile_cars')->eq($random_model)->attr('id');

		//Нахожу случайный блок и возвращаю линк
		$link = $crawler->filter('div.ersatzteile_cars')->eq($random_model)->link();

		//Кликаю по блоку со случайной моделью автомобиля
		$crawler = $client->click($link);

		//Нахожу все категории запчастей определенной модели
		$array_of_model_categories = $crawler->filter('#'.$id_of_block.'.popup_ers > li');

		//Получаю случайную категорию
		$random_category = rand(0, count($array_of_model_categories));

		//Ссылка категории
		$link = $crawler->filter('#'.$id_of_block.'.popup_ers > li')->eq($random_category)->link();

		//Кликаю по блоку со случайной категорией модели автомобиля
		$crawler = $client->click($link);

		$array_of_model_parts = $crawler->filter('#'.$id_of_block.'.popup_ers > li > td.title > a');

		//Получаю случайную ссылку на запчасти
		$random_model_part_link = rand(0, count($array_of_model_parts));

		//Ссылка запчастей
		$link = $crawler->filter('#'.$id_of_block.'.popup_ers > li > td.title > a')
						->eq($random_model_part_link)->link();

		print_r($link);

		//Кликаю по блоку со случайной категорией модели автомобиля
		//$crawler = $client->click($link);
	}
}