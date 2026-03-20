<?php

class EventBlogsBlogListAjaxLoad extends AbstractEvent
{
	public function authorize(PolicyContext $policyContext): PolicyDecision
	{
		return PolicyDecision::allow();
	}

	public function run(): void
	{
		$draw = (int) Request::_GET('draw', 1);
		$blogList = EntityBlog::getList();
		$totalCount = count($blogList);

		$data = [];

		foreach ($blogList as $row) {
			$data[] = [
				$row['id'],
				substr($row['date'], 0, 10), // Date only, no time
				$row['title'],
				$row['slug'],
				$row['id'],
			];
		}

		$output = [
			'draw' => $draw,
			'recordsTotal' => $totalCount,
			'recordsFiltered' => $totalCount,
			'data' => $data,
		];

		ApiResponse::renderSuccess($output);
	}
}
