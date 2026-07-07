<?php
namespace Modules\GrafanaDashboards\Actions;

use CController;
use CControllerResponseRedirect;

class ActionGrafanaDelete extends CController {

    protected function init(): void {
        $this->disableCsrfValidation();
    }

    protected function checkInput(): bool {
        return true;
    }

    protected function checkPermissions(): bool {
        return ($this->getUserType() >= USER_TYPE_ZABBIX_ADMIN);
    }

    protected function doAction(): void {
        $data_file = __DIR__ . '/../data/dashboards.json';
        $id = $_GET['id'] ?? '';

        if ($id && file_exists($data_file)) {
            $dashboards = json_decode(file_get_contents($data_file), true) ?: [];
            $dashboards = array_values(array_filter($dashboards, fn($d) => $d['id'] !== $id));
            file_put_contents($data_file, json_encode($dashboards, JSON_PRETTY_PRINT));
        }

        $this->setResponse(new CControllerResponseRedirect(
            (new \CUrl('zabbix.php'))->setArgument('action', 'grafana.list')
        ));
    }
}
