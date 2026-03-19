<?php

/**
 * Minimal teaching plugin descriptor.
 *
 * The framework method is called getId(), but the returned value is really a
 * stable plugin slug. Treat it like a machine name:
 *
 * - lowercase
 * - hyphen-separated
 * - safe to store in paths, JSON files and logs
 *
 * That slug becomes the plugin folder name after registry install, so it is
 * worth keeping it boring and predictable.
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
