<?php

namespace diegocosta\craftembediframe\controllers;

use Craft;
use craft\web\Controller;
use diegocosta\craftembediframe\Plugin;
use yii\web\ForbiddenHttpException;

class DefaultController extends Controller
{
    protected array|int|bool $allowAnonymous = false;

    public function actionIndex()
    {
        if (!Craft::$app->user->checkPermission('_embed-iframe:access')) {
            throw new ForbiddenHttpException('User is not allowed to access this page.');
        }

        $settings = Plugin::getInstance()->getSettings();

        return $this->renderTemplate('_embed-iframe/index', [
            'settings' => $settings,
        ]);
    }

    public function actionHealth()
    {
        return $this->asJson(['ok' => true]);
    }
}
