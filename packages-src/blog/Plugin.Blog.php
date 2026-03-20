<?php

class PluginBlog extends AbstractPlugin
{
	public function getId(): string
	{
		return 'blog';
	}

	public function getBasePath(): string
	{
		return __DIR__;
	}
}
