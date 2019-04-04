<?php

namespace andrewdanilov\sitemap;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
	public function bootstrap($app)
	{
		$app->urlManager->addRules([
			[
				'class' => SitemapUrlRule::class,
			],
		], false);

		$components = $app->getComponents();
		if (!isset($components['sitemap'])) {
			$components['sitemap'] = [
				'class' => Sitemap::class,
			];
		}
		$app->setComponents($components);
	}
}