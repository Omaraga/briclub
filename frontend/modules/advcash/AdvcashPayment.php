<?php

namespace frontend\modules\advcash;

use Yii;

/**
 * advcash module definition class
 */
class AdvcashPayment extends \yii\base\Module
{
    /**
     * @inheritdoc
     */

    public $controllerNamespace = 'frontend\modules\advcash\controllers';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        $this->registerTranslations();
    }

    public function registerTranslations()
    {
        Yii::$app->i18n->translations['frontend/modules/advcash/main/*'] = [
            'class' => 'yii\i18n\PhpMessageSource',
            'sourceLanguage' => 'ru-RU',
            'basePath' => '@frontend/modules/advcash/messages',
            'fileMap' => [
                'frontend/modules/advcash/main' => 'main.php',
            ],
        ];
    }

    public static function t($category, $message, $params = [], $language = null)
    {
        return Yii::t('frontend/modules/advcash/'.$category, $message, $params, $language);
    }
}
