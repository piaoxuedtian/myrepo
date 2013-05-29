<?php

class DefaultController extends Controller {

    /**
     * @description RBAC 模块首页
     */
    public function actionIndex() {
        $this->render('index');
    }

    /**
     * @description 扫描应用全部动作
     */
    public function actionScaner() {
        $actions = (Yii::app()->getModule('rbac')->disabledScanFrontend) ? array() : $this->getActions(CFileHelper::findFiles(Yii::app()->getControllerPath()));
        $disabledScanModules = Yii::app()->getModule('rbac')->disabledScanModules;
        $modules = $this->getModuleControllerMap(Yii::app()->modules, null);
        foreach ($modules as $moduleId => $controllerPath) {
            if (!in_array($moduleId, $disabledScanModules)) {
                $actions = array_merge($actions, $this->getActions(CFileHelper::findFiles($controllerPath), $moduleId));
            }
        }

        $filterForm = new FilterArrayForm;
        if (isset($_GET['FilterArrayForm'])) {
            $filterForm->filters = $_GET['FilterArrayForm'];
            $actions = $filterForm->filter($actions);
        }

        $dataProvider = new CArrayDataProvider($actions, array(
                    'keyField' => 'name',
                    'pagination' => array(
                        'pageVar' => 'page',
                        'pageSize' => $this->pageSize,
                    )
                ));

        $this->render('scaner', array(
            'dataProvider' => $dataProvider,
            'filterForm' => $filterForm,
        ));
    }

    /**
     * 获取模块及其对应的控制器保存路径
     * @param array $modules
     * @param string $prefix
     * @return array
     */
    private function getModuleControllerMap($modules, $prefix = null) {
        $data = array();
        foreach ($modules as $id => $settings) {
            if ($prefix) {
                $moduleId = "{$prefix}.{$id}";
                $children = explode('.', $moduleId);
                $parentModule = Yii::app()->getModule($children[0]);
                unset($children[0]);
                foreach ($children as $child) {
                    $parentModule = $parentModule->getModule($child);
                }
                $controllerPath = $parentModule->controllerPath;
            } else {
                $moduleId = $id;
                $controllerPath = Yii::app()->getModule($moduleId)->controllerPath;
            }
            $data["{$moduleId}"] = $controllerPath;
            if (isset($settings['modules'])) {
                $data = array_merge($data, $this->getModuleControllerMap($settings['modules'], $id));
            }
        }

        return $data;
    }

    /**
     * 获取控制其中的所有动作名称
     * @param array $controllerFiles
     * @param string $moduleId
     * @return array
     */
    private function getActions($controllerFiles = array(), $moduleId = null) {
        $actions = array();
        $existItems = $this->dbConnection->createCommand("SELECT name FROM {$this->authManager->itemTable}")->queryColumn();
        foreach ($controllerFiles as $file) {
            $controllerActions = $this->_parseControllerActions($file);
            if ($controllerActions) {
                $moduleControllerName = $this->_getRbacController($file, $moduleId);
                if ($moduleControllerName) {
                    $moduleControllerName = strtr(strtolower($moduleControllerName), array('/' => '.'));
                    // tasks
                    $tasks = array(
                        "task.{$moduleControllerName}.administrator",
                        "task.{$moduleControllerName}.viewer",
                        "task.{$moduleControllerName}.editor",
                    );
                    foreach ($tasks as $task) {
                        $actions[] = array(
                            'name' => $task,
                            'description' => null,
                            'active' => (in_array($task, $existItems)) ? true : false,
                            'type' => RbacHelper::TYPE_TASK,
                        );
                    }
                    // controller actions
                    foreach ($controllerActions as $action => $description) {
                        $name = strtolower("{$moduleControllerName}.{$action}");
                        $actions[] = array(
                            'name' => $name,
                            'description' => $description,
                            'active' => (in_array($name, $existItems)) ? true : false,
                            'type' => RbacHelper::TYPE_OPERATION,
                        );
                    }
                }
            }
        }

        return $actions;
    }

    /**
     * @description 添加授权项目
     * @throws CHttpException
     */
    public function actionCreateAuthItem() {
        $request = Yii::app()->request;
        if ($request->isAjaxRequest) {
            $name = $request->getPost('name');
            $type = $request->getPost('type');
            $types = array_keys(RbacHelper::types());
            if (!empty($name) && isset($types[$type])) {
                $count = $this->dbConnection->createCommand("SELECT COUNT(*) FROM {$this->authManager->itemTable} WHERE name = :name AND type = :type")->queryScalar(array(':name' => $name, ':type' => $type));
                if (!$count) {
                    $description = $request->getPost('description');
                    $this->authManager->createAuthItem($name, $type, $description);
                }
            }
        } else {
            throw new CHttpException(400, RbacHelper::translate('Invalid request. Please do not repeat this request again.'));
        }
    }

    /**
     * @description 模块配置情况
     */
    public function actionSettings() {
        $showData = array(
            'debug',
            'language',
            'notAuthorizedView',
            'userTable',
            'userTableId',
            'userTableName',
            'pageSize',
            'disabledScanFrontend',
            'disabledScanModules',
        );
        $rawData = (array) $this->module;
        $data = array();
        foreach ($rawData as $key => $value) {
            if (in_array($key, $showData)) {
                if (is_array($value)) {
                    $data["{$key}"] = count($value) == 1 ? current($value) : implode(', ', $value);
                } else {
                    if ($value === true) {
                        $data["{$key}"] = 'True';
                    } elseif ($value === false) {
                        $data["{$key}"] = 'False';
                    } elseif ($value === null) {
                        $data["{$key}"] = 'Null';
                    } else {
                        $data["{$key}"] = $value;
                    }
                }
            }
        }

        $this->render('settings', array(
            'data' => $data,
        ));
    }

    /**
     * @description 显示总是允许授权项
     */
    public function actionShowAllowedAuthItems() {
        $allowedAuthItems = RbacHelper::getAllowedAuthItems();
        if ($allowedAuthItems) {
            $rawData = $this->dbConnection->createCommand()
                    ->select('name, description')
                    ->from($this->authManager->itemTable)
                    ->where(array('in', 'name', $allowedAuthItems))
                    ->order('name ASC')
                    ->queryAll();
        } else {
            $rawData = array();
        }
        $dataProvider = new CArrayDataProvider($rawData, array(
                    'totalItemCount' => count($rawData),
                    'pagination' => array(
                        'pageVar' => 'page',
                        'pageSize' => $this->pageSize,
                    ),
                    'keyField' => 'name',
                ));

        $this->render('showAllowedAuthItems', array(
            'dataProvider' => $dataProvider,
        ));
    }

    private function _getRbacController($controllerFile, $moduleId = null) {
        return ($this->_isExtendsRbacController($controllerFile)) ? $this->_parseControllerFilePath($controllerFile, $moduleId) : null;
    }

    /**
     * 解析控制器文件路径
     * @param string $controllerFilePath
     * @param string $moduleId
     * @return string
     */
    private function _parseControllerFilePath($controllerFilePath, $moduleId = null) {
        $controller = strtr(basename($controllerFilePath), array('.php' => '', 'Controller' => ''));
        return ($moduleId) ? "{$moduleId}.{$controller}" : $controller;
    }

    /**
     * 获取指定控制器文件中的所有动作
     * @param string $controller
     * @param string $moduleId
     * @return array
     */
    private function _parseControllerActions($controller, $moduleId = null) {
        $count = 0;
        $controller = ($moduleId) ? "{$moduleId}.{$controller}" : $controller;
        $h = file($controller);
        $rows = count($h);
        $actions = $descriptions = array();
        for ($i = 0; $i < $rows; $i++) {
            $line = trim($h[$i]);
            if (in_array($line, array('', '/**', '*', '*/', '{', '}', '<?php', '?>')) || strpos($line, 'actions()') || (strpos($line, 'description') === false && strpos($line, 'function') === false)) {
                continue;
            }
            if (preg_match("/^(.+)function( +)action*/", $line)) {
                $posAct = strpos(trim($line), "action");
                $posPar = strpos(trim($line), "(");
                $patterns[0] = '/\s*/m';
                $patterns[1] = '#\((.*)\)#';
                $patterns[2] = '/\{/m';
                $replacements[2] = '';
                $replacements[1] = '';
                $replacements[0] = '';
                $action = preg_replace($patterns, $replacements, trim(trim(substr(trim($line), $posAct, $posPar - $posAct))));
                $actions[$i] = preg_replace("/action/", "", $action, 1);
            } elseif (preg_match("/^\*( +)@description( +)*/", $line)) {
                $descriptions[$i] = trim(str_replace('* @description', '', $line));
            }

            $count = count($actions);
            if ($count != count($descriptions)) {
                $descriptions = array_pad($descriptions, $count, null);
            }
        }

        return ($count) ? array_combine($actions, $descriptions) : array();
    }

    /**
     * 判断是否继承于 RbacController 基类
     * @param string $controller
     * @return boolean
     */
    private function _isExtendsRbacController($controller) {
        $class = basename(str_replace(".php", "", $controller));
        if (!class_exists($class, false)) {
            include_once $controller;
        }

        return (new $class($class) instanceof Controller) ? true : false;
    }

}