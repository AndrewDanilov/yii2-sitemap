<?php

namespace andrewdanilov\sitemap;

class LocParams
{
	const ALWAYS = 'always';
	const HOURLY = 'hourly';
	const DAILY = 'daily';
	const WEEKLY = 'weekly';
	const MONTHLY = 'monthly';
	const YEARLY = 'yearly';
	const NEVER = 'never';

	public $changeFreq=self::DAILY;
	public $priority=0.5;
	public $lastMod=0;
}