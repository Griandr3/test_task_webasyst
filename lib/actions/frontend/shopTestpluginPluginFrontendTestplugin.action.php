<?php
class shopTestpluginPluginFrontendTestpluginAction extends shopFrontendAction {
    public function execute()
    {
        $plugin = wa()->getPlugin('testplugin');
        $ids = $plugin->getSettings('product_ids');

        $shop_url = wa()->getRouteUrl('shop/frontend');
        $random_url = $shop_url . 'testplugin/random/';
        $email_url  = $shop_url . 'testplugin/email/';

        $this->view->assign('random_url', $random_url);
        $this->view->assign('email_url', $email_url);
        $this->view->assign('product_ids', $ids);
    }
}