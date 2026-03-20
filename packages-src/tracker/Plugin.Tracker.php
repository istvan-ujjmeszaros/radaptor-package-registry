<?php

class PluginTracker extends AbstractPlugin
{
	public function getId(): string
	{
		return 'tracker';
	}

	public function getBasePath(): string
	{
		return __DIR__;
	}

	public function getTagContexts(): array
	{
		return ['tracker_project', 'tracker_ticket'];
	}

	public function getCommentContexts(): array
	{
		return ['tracker_ticket'];
	}
}
