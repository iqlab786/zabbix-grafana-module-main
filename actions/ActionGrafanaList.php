<?php
namespace Modules\GrafanaDashboards\Actions;

use CController;
use CControllerResponseData;

class ActionGrafanaList extends CController {

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
        $dashboards = [];

        if (file_exists($data_file)) {
            $dashboards = json_decode(file_get_contents($data_file), true) ?: [];
        }

        $this->setResponse(new CControllerResponseData([
            'dashboards' => $dashboards,
            'user'       => ['type' => $this->getUserType()]
        ]));
    }
}
