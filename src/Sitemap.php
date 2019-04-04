<?php

namespace andrewdanilov\sitemap;

use yii\base\Component;
use yii\db\ActiveRecord;
use yii\helpers\Url;

/**
 * Sitemap generator class
 */

class Sitemap extends Component
{
	public $urls = [];
	public $cache_key = 'sitemap';
	public $cache_time = 3600;

	protected $items = array();

	/**
	 * @param string|array $url
	 * @param LocParams $locParams
	 */
	protected function addUrl($url, LocParams $locParams)
	{
		$item = [
			'loc' => Url::to($url, true),
			'changefreq' => $locParams->changeFreq,
			'priority' => $locParams->priority
		];
		if ($locParams->lastMod) {
			$item['lastmod'] = $this->dateToW3C($locParams->lastMod);
		}

		$this->items[] = $item;
	}

	/**
	 * @param string|ActiveRecord $class
	 * @param string|array $url
	 * @param string $attribute
	 * @param LocParams $locParams
	 */
	protected function addClass($class, $url, $attribute, LocParams $locParams)
	{
		if (!is_array($url)) {
			$url = [$url];
		}

		foreach ($class::find()->all() as $model)
		{
			$url[$attribute] = $model->id;

			if ($model->hasAttribute('updated_at')) {
				$locParams->lastMod = $this->dateToW3C($model->updated_at);
			} elseif ($model->hasAttribute('published_at')) {
				$locParams->lastMod = $this->dateToW3C($model->published_at);
			} elseif ($model->hasAttribute('created_at')) {
				$locParams->lastMod = $this->dateToW3C($model->created_at);
			}

			$this->addUrl($url, $locParams);
		}
	}

	/**
	 * Prepares links to render
	 */
	public function prepare()
	{
		foreach ($this->urls as $url) {

			$locParams = new LocParams();

			if (is_array($url) && array_key_exists('url', $url)) {

				$locParams->changeFreq = $url['changeFreq'] ?: $locParams->changeFreq;
				$locParams->priority = $url['priority'] ?: $locParams->priority;
				$locParams->lastMod = $url['lastMod'] ?: $locParams->lastMod;

				if (array_key_exists('class', $url)) {

					if (!isset($url['attribute'])) {
						$url['attribute'] = 'id';
					}
					$this->addClass($url['class'], $url['url'], $url['attribute'], $locParams);

				} else {

					$this->addUrl($url['url'], $locParams);

				}

			} else {

				$this->addUrl($url, $locParams);

			}
		}
	}

	/**
	 * @return string XML code
	 */
	public function render()
	{
		$dom = new \DOMDocument('1.0', 'utf-8');
		$urlset = $dom->createElement('urlset');
		$urlset->setAttribute('xmlns','http://www.sitemaps.org/schemas/sitemap/0.9');
		foreach ($this->items as $item)
		{
			$url = $dom->createElement('url');

			foreach ($item as $key=>$value)
			{
				$elem = $dom->createElement($key);
				$elem->appendChild($dom->createTextNode($value));
				$url->appendChild($elem);
			}

			$urlset->appendChild($url);
		}
		$dom->appendChild($urlset);

		return $dom->saveXML();
	}

	protected function dateToW3C($date)
	{
		if (is_int($date)) {
			return date(DATE_W3C, $date);
		} else {
			return date(DATE_W3C, strtotime($date));
		}
	}
}
