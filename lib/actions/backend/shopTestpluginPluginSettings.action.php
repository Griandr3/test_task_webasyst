<?php

class shopTestpluginPluginSettingsAction extends waViewAction
{
    public function execute()
    {
        $plugin = shopPlugin::getPlugin('testplugin');
        $settings = $plugin->getSettings();

        if (waRequest::method() == 'post') {
            $product_ids = waRequest::post('product_ids', '', waRequest::TYPE_STRING);
            $plugin->saveSettings([
                'product_ids' => $product_ids,
            ]);
            $this->redirect(wa()->getConfig()->getBackendUrl(true) . 'shop/?action=plugins#/testplugin/');
        }

        $this->view->assign([
            'settings' => $settings,
        ]);
    }
}
