<?php assert(isset($this) && $this instanceof Template); ?>
<?php
$field_refs = is_array($this->props['field_refs'] ?? null) ? $this->props['field_refs'] : [];
$html_attributes = [
	'data-controller' => 'form-timezone',
];

if (
	(string)($this->props['mode'] ?? '') === AbstractForm::_MODE_CREATE
	&& is_array($field_refs['title'] ?? null)
	&& is_array($field_refs['slug'] ?? null)
) {
	$html_attributes = [
		'data-controller' => 'form-timezone slug-generator',
		'data-slug-generator-source-id-value' => (string)($field_refs['title']['id'] ?? ''),
		'data-slug-generator-slug-id-value' => (string)($field_refs['slug']['id'] ?? ''),
	];
}

$template = new Template('sdui.form', $this->getRenderer(), $this->getWidgetConnection());
$template->props = array_replace($this->props, [
	'html_attributes' => $html_attributes,
]);
$template->setSlots([
	'hidden_fields' => $this->fetchSlot('hidden_fields'),
	'rows' => $this->fetchSlot('rows'),
]);
?>
<?= $template->fetch() ?>
