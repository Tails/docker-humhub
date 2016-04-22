<?php

/**
 * @link https://www.humhub.org/
 * @copyright Copyright (c) 2015 HumHub GmbH & Co. KG
 * @license https://www.humhub.com/licences
 */

namespace humhub\components;

use Yii;

/**
 * @inheritdoc
 */
class Application extends \yii\web\Application
{

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'humhub\\controllers';

    /**
     * @inheritdoc
     */
    public function beforeAction($action)
    {

        /**
         * Check if it's already installed - if not force controller module
         */
        if (!$this->params['installed'] && $this->controller->module != null && $this->controller->module->id != 'installer') {
            $this->controller->redirect(\yii\helpers\Url::to(['/installer/index']));
            return false;
        }

        /**
         * More random widget autoId prefix
         * Ensures to be unique also on ajax partials
         */
        \yii\base\Widget::$autoIdPrefix = 'h' . mt_rand(1, 999999) . 'w';

        return parent::beforeAction($action);
    }

    /**
     * @inheritdoc
     */
    public function preInit(&$config)
    {
        // below was the humhub default

        // if (!isset($config['timeZone']) && date_default_timezone_get()) {
        //     $config['timeZone'] = date_default_timezone_get();
        // }

        // this is our custom setting to surpress the datetime warning
        // humhub will not load otherwise
        $config['timeZone'] = 'Europe/Amsterdam';

        parent::preInit($config);
    }

}