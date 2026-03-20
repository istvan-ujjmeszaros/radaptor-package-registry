<?php

/**
 * @phpstan-type ShapeEntityBlog array{
 *     id?: int,
 *     slug?: string|null,
 *     title?: string|null,
 *     content?: string|null,
 *     __content?: string|null,
 *     description?: string|null,
 *     __description?: string|null,
 *     date?: string|null
 * }
 *
 * @extends SQLEntity<ShapeEntityBlog>
 * @property int|null $id
 * @property string|null $slug
 * @property string|null $title
 * @property string|null $content
 * @property string|null $__content
 * @property string|null $description
 * @property string|null $__description
 * @property string|null $date
 */
class EntityBlog extends SQLEntity
{
	public const string TABLE_NAME = 'blog';
	public const int ERROR_MULTIPLE = -2;
	public const int ERROR_NOT_FOUND = -3;

	// ============================================================================
	// Custom methods (safe from regeneration)
	// ============================================================================

	public static function getTitle(int $id): ?string
	{
		return static::findById($id)?->title;
	}

	public static function getList(bool $newest_first = false): array
	{
		if ($newest_first) {
			return DbHelper::selectMany(
				table: 'blog',
				order_by: "date DESC, id DESC"
			);
		} else {
			return DbHelper::selectMany('blog');
		}
	}

	public static function getIdByTitle(string $title): int
	{
		$data = DbHelper::selectMany('blog', ['title' => trim($title)], false, '', 'id');

		if (count($data) > 1) {
			return self::ERROR_MULTIPLE;
		}

		return $data[0]['id'] ?? self::ERROR_NOT_FOUND;
	}

	public static function getIdBySlug(string $slug): int
	{
		$data = DbHelper::selectMany('blog', ['slug' => trim($slug)], false, '', 'id');

		if (count($data) > 1) {
			return self::ERROR_MULTIPLE;
		}

		return $data[0]['id'] ?? self::ERROR_NOT_FOUND;
	}

	public static function getAllModificationsData(int $id): ?array
	{
		return Audit::getAllModificationsWithComments('blog', $id);
	}
}
