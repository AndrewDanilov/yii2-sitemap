Sitemap Generator
===================
Component generates sitemap.xml for your controllers/actions or random pages.

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require andrewdanilov/yii2-sitemap "dev-master"
```

or add

```
"andrewdanilov/yii2-sitemap": "dev-master"
```

to the require section of your `composer.json` file.


Usage
-----

Add component Sitemap to main config:

```php
return [
	...
	'components' => [
		...
		'sitemap' => [
			'class' => andrewdanilov\sitemap\Sitemap,
			'urls' => [
				// Full notation for ActiveRecord model.
				// All found records will be collected for building sitemap.
				[
					'class' => 'frontend\models\Products',
					'url' => ['catalog/product'],
					'attribute' => 'id', // optional
					'changeFreq' => andrewdanilov\sitemap\LocParams::WEEKLY, // optional
					'priority' => 0.1, // optional
					'lastMod' => 0, // optional
				],
				// Full notation for single url/action.
				[
					'url' => ['catalog/index'],
					'changeFreq' => andrewdanilov\sitemap\LocParams::WEEKLY, // optional
					'priority' => 0.1, // optional
					'lastMod' => 0, // optional
				],
				// Short notation for single url/action
				['catalog/index'],
				// Short notation for single random url
				'category1/product123?page=2',
			],
		],
	],
];
```