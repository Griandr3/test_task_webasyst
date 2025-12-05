<?php

class shopTestpluginPlugin extends shopPlugin
{
    public function getSettingsConfig()
    {
        return array(
            'product_ids' => array(
                'title' => 'ID товаров',
                'description' => 'Укажите ID товаров через запятую',
                'value' => '',
                'control_type' => waHtmlControl::INPUT,
            ),
        );
    }
}

