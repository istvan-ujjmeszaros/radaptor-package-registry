<?php

/**
 * Smallest possible event example for a plugin.
 *
 * Direct `echo` from an event is supported by the framework, so it can be
 * useful for tiny debug endpoints or very small internal utilities.
 *
 * In normal product code it is usually better to render a Template or return
 * a structured response, because that keeps presentation concerns easier to
 * test and maintain.
 */
class EventHelloWorldPluginRaw extends AbstractEvent
{
	/**
	 * Keep the demo endpoint public so it is easy to try.
	 */
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return PolicyDecision::allow();
	}

	/**
	 * Emit a tiny text response directly.
	 */
	public function run(): void
	{
		$message = t('hello_world.raw.message');

		echo $message;
	}
}
