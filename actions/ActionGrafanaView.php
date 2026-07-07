<?php
namespace Modules\GrafanaDashboards\Actions;

use CController;
use CControllerResponseData;

class ActionGrafanaView extends CController {

    protected function init(): void {
        $this->disableCsrfValidation();
    }

    protected function checkInput(): bool {
        return true;
    }

    protected function checkPermissions(): bool {
        return true;
    }

    protected function doAction(): void {
        $data_file = __DIR__ . '/../data/dashboards.json';
        $id        = $_GET['id'] ?? '';
        $dashboard = null;

        if ($id && file_exists($data_file)) {
            foreach (json_decode(file_get_contents($data_file), true) ?: [] as $d) {
                if ($d['id'] === $id) {
                    $dashboard = $d;
                    break;
                }
            }
        }

        $this->setResponse(new CControllerResponseData([
            'dashboard' => $dashboard,
            'user'      => ['type' => $this->getUserType()]
        ]));
    }
}
