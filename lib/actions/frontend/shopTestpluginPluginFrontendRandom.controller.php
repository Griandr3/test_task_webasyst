<?php
class shopTestpluginPluginFrontendRandomController extends waJsonController {
    public function execute()
    {
        $plugin = wa()->getPlugin('testplugin');
        $ids_string = $plugin->getSettings('product_ids');
        if (empty($ids_string)) {
            $this->errors = 'Товары не настроены';
            return;
        }

        $ids = explode(',', $ids_string);
        $ids = array_map('trim', $ids);
        $ids = array_map('intval', $ids);
        $ids = array_filter($ids);

        if (empty($ids)) {
            $this->errors = 'Товары не найдены';
            return;
        }

        $random_id = $ids[array_rand($ids)];
        $product_model = new shopProductModel();
        $product = $product_model->getById($random_id);

        if (!$product) {
            $this->errors = 'Товар не найден';
            return;
        }

        $image_url = '';
        if (!empty($product['image_id'])) {
            $product_obj = new shopProduct($random_id);
            $image_url = $product_obj->getImageUrl(150, 150);
        }

        $this->response = array(
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => shop_currency($product['price']),
            'image' => $image_url,
        );
    }
}