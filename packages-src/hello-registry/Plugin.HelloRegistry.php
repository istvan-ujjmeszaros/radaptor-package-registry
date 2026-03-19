<?php

class PluginHelloRegistry extends AbstractPlugin
{
	public function getId(): string
	{
		return 'hello-registry';
	}

	public function getBasePath(): string
	{
		return DEPLOY_ROOT . 'plugins/hello-registry';
	}
}
