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
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return PolicyDecision::allow();
	}

	public function run(): void
	{
		echo "Hello from the plugin raw event!";
	}
}
