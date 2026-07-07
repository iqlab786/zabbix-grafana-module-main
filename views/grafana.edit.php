<?php declare(strict_types = 1);
/**
 * @var CView $this
 * @var array $data
 */

$d       = $data['dashboard'];
$is_edit = !empty($d['id']);
$title   = $is_edit ? _('Edit Grafana Dashboard') : _('Add Grafana Dashboard');

$form = (new CTag('form', true))
	->setAttribute('method', 'post')
	->setAttribute('action', 'zabbix.php?action=grafana.edit')
	->setAttribute('style', 'max-width:600px;')
	->addItem((new CTag('input', false))->setAttribute('type', 'hidden')->setAttribute('name', 'save')->setAttribute('value', '1'))
	->addItem($is_edit
		? (new CTag('input', false))->setAttribute('type', 'hidden')->setAttribute('name', 'id')->setAttribute('value', htmlspecialchars($d['id']))
		: null
	);

$field_style = 'width:100%;padding:7px 10px;border:1px solid #ccc;border-radius:3px;font-size:13px;box-sizing:border-box;';
$label_style = 'display:block;font-weight:bold;font-size:12px;margin-bottom:4px;color:#444;';
$row_style   = 'margin-bottom:16px;';

$form->addItem((new CDiv([
	(new CTag('label', true, _('Dashboard Name') . ' *'))->setAttribute('for', 'name')->setAttribute('style', $label_style),
	(new CTag('input', false))
		->setAttribute('type', 'text')->setAttribute('id', 'name')->setAttribute('name', 'name')
		->setAttribute('value', htmlspecialchars($d['name']))->setAttribute('placeholder', 'e.g. ISP Monitoring')
		->setAttribute('required', 'required')->setAttribute('autofocus', 'autofocus')
		->setAttribute('style', $field_style)
]))->setAttribute('style', $row_style));

$form->addItem((new CDiv([
	(new CTag('label', true, _('Grafana Base URL') . ' *'))->setAttribute('for', 'grafana_url')->setAttribute('style', $label_style),
	(new CTag('input', false))
		->setAttribute('type', 'url')->setAttribute('id', 'grafana_url')->setAttribute('name', 'grafana_url')
		->setAttribute('value', htmlspecialchars($d['grafana_url']))->setAttribute('placeholder', 'http://192.168.1.100:3000')
		->setAttribute('required', 'required')->setAttribute('style', $field_style),
	(new CDiv(_('The base URL of your Grafana instance')))->setAttribute('style', 'font-size:11px;color:#888;margin-top:3px;')
]))->setAttribute('style', $row_style));

$form->addItem((new CDiv([
	(new CTag('label', true, _('Dashboard UID') . ' *'))->setAttribute('for', 'dashboard_uid')->setAttribute('style', $label_style),
	(new CTag('input', false))
		->setAttribute('type', 'text')->setAttribute('id', 'dashboard_uid')->setAttribute('name', 'dashboard_uid')
		->setAttribute('value', htmlspecialchars($d['dashboard_uid']))->setAttribute('placeholder', 'abc123xyz')
		->setAttribute('required', 'required')->setAttribute('style', $field_style),
	(new CDiv(_('Found in Grafana URL: /d/UID/dashboard-name')))->setAttribute('style', 'font-size:11px;color:#888;margin-top:3px;')
]))->setAttribute('style', $row_style));

$form->addItem((new CDiv([
	(new CTag('label', true, _('Description')))->setAttribute('for', 'description')->setAttribute('style', $label_style),
	(new CTag('textarea', true, htmlspecialchars($d['description'] ?? '')))
		->setAttribute('id', 'description')->setAttribute('name', 'description')
		->setAttribute('placeholder', _('Optional description...'))
		->setAttribute('style', $field_style . 'height:80px;resize:vertical;')
]))->setAttribute('style', $row_style));

$btn_style = 'padding:7px 18px;border:none;border-radius:3px;font-size:13px;cursor:pointer;';
$form->addItem((new CDiv([
	(new CTag('button', true, $is_edit ? _('Update') : _('Add Dashboard')))
		->setAttribute('type', 'submit')
		->setAttribute('style', $btn_style . 'background:#1f83c6;color:#fff;'),
	(new CTag('a', true, _('Cancel')))
		->setAttribute('href', 'zabbix.php?action=grafana.list')
		->setAttribute('style', $btn_style . 'background:#e0e0e0;color:#333;text-decoration:none;display:inline-block;margin-left:8px;')
]))->setAttribute('style', 'margin-top:8px;'));

(new CHtmlPage())
	->setTitle($title)
	->addItem($form)
	->show();
