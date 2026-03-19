<?php

/**
 * Minimal teaching plugin descriptor.
 *
 * The framework method is called getId(), but the returned value should be
 * treated as a stable machine slug:
 *
 * - lowercase
 * - hyphen-separated
 * - safe to store in paths, JSON files and logs
 */
class PluginHelloWorld extends AbstractPlugin
{
	/**
	 * Return the stable plugin slug used by the control plane.
	 */
	public function getId(): string
	{
		return 'hello-world';
	}

	/**
	 * Return the directory where the plugin is expected after install/sync.
	 */
	public function getBasePath(): string
	{
		return DEPLOY_ROOT . 'plugins/hello-world';
	}
}
