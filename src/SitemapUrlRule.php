<?php

namespace andrewdanilov\sitemap;

use yii\web\UrlRuleInterface;
use yii\base\BaseObject;

class SitemapUrlRule extends BaseObject implements UrlRuleInterface
{
	/**
	 * @param \yii\web\UrlManager $manager
	 * @param \yii\web\Request $request
	 * @return array|bool
	 * @throws \yii\base\InvalidConfigException
	 */
	public function parseRequest($manager, $request)
	{
		$pathInfo = $request->getPathInfo();
		if ($pathInfo == 'sitemap.xml') {
			return ['sitemap/index', []];
		}
		return false;
	}

	/**
	 * @param \yii\web\UrlManager $manager
	 * @param string $route
	 * @param array $params
	 * @return bool|string
	 */
	public function createUrl($manager, $route, $params)
	{
		if ($route === 'sitemap/index') {
			return 'sitemap.xml';
		}
		return false;
	}
}
