<?php

/**
 * Minimal teaching plugin descriptor.
 *
 * The descriptor is intentionally tiny: it tells Radaptor the stable plugin id
 * and the base directory where the plugin lives after installation.
 */
class PluginHelloWorld extends AbstractPlugin
{
	public function getId(): string
	{
		return 'hello-world';
	}

	public function getBasePath(): string
	{
		return DEPLOY_ROOT . 'plugins/hello-world';
	}
}
