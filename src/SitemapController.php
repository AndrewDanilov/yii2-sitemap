<?php

namespace andrewdanilov\sitemap;

use Yii;
use yii\web\Controller;
use yii\web\Response;

class SitemapController extends Controller
{
	public function actionIndex()
	{
		$components = Yii::$app->getComponents();
		/* @var $sitemap Sitemap */
		$sitemap = $components['sitemap'];

		if (!$xml = Yii::$app->cache->get($sitemap->cache_key))
		{
			$sitemap->prepare();
			$xml = $sitemap->render();
			Yii::$app->cache->set($sitemap->cache_key, $xml, $sitemap->cache_time);
		}

		Yii::$app->response->format = Response::FORMAT_RAW;
		$headers = Yii::$app->response->headers;
		$headers->add('Content-Type', 'application/xml');
		return $xml;
	}
}