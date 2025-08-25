<?php

namespace diegocosta\craftembediframe;

use Craft;
use craft\base\Model;
use craft\base\Plugin as BasePlugin;
use craft\events\RegisterUserPermissionsEvent;
use craft\services\UserPermissions;
use craft\events\RegisterUrlRulesEvent;
use craft\web\UrlManager;
use yii\base\Event;
use diegocosta\craftembediframe\models\Settings;

class Plugin extends BasePlugin
{
    public string $schemaVersion = '1.0.0';
    public bool $hasCpSettings = true;
    public bool $hasCpSection = true;

    public function init(): void
    {
        parent::init();

        $prefix = $this->handle; // "_embed-iframe"

        // /admin/_embed-iframe → controller default/index
        Event::on(
            UrlManager::class,
            UrlManager::EVENT_REGISTER_CP_URL_RULES,
            function (RegisterUrlRulesEvent $event) use ($prefix) {
                $event->rules[$prefix] = $prefix . '/default/index';
                $event->rules[$prefix . '/health'] = $prefix . '/default/health';
            }
        );

        // Permissions
        Event::on(
            UserPermissions::class,
            UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function (RegisterUserPermissionsEvent $event) {
                // Agrupa suas permissões sob o nome do plugin no CP
                $event->permissions[$this->name] = [
                    'label' => Craft::t($this->handle, 'Embed Iframe'),
                    'permissions' => [
                        $this->handle . ':access' => [
                            'label' => Craft::t($this->handle, 'Access Embed Iframe'),
                        ],
                    ],
                ];
            }
        );
    }

    protected function createSettingsModel(): ?Model
    {
        return Craft::createObject(Settings::class);
    }

    protected function settingsHtml(): ?string
    {
        $prefix = $this->handle;
        return Craft::$app->getView()->renderTemplate($prefix . '/_settings', [
            'plugin'   => $this,
            'settings' => $this->getSettings(),
        ]);
    }

    public function getCpNavItem(): ?array
    {
        $item = parent::getCpNavItem();
        $s = $this->getSettings();

        // hide from nav for those who don't have permission
        if (!Craft::$app->user->checkPermission($this->handle . ':access')) {
            return null;
        }

        $item['label'] = $s->navLabel ?: Craft::t($this->handle, 'Embed Iframe');
        $item['url']   = $this->handle;
        return $item;
    }
}
