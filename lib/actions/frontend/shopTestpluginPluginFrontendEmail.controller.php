<?php
class shopTestpluginPluginFrontendEmailController extends waJsonController {
    public function execute()
    {
        $email = waRequest::post('email', '', waRequest::TYPE_STRING_TRIM);
        $product_id = waRequest::post('product_id', 0, waRequest::TYPE_INT);

        if (empty($email) || empty($product_id)) {
            $this->errors = 'Не указан email или ID товара';
            return;
        }

        $validator = new waEmailValidator();
        if (!$validator->isValid($email)) {
            $this->errors = 'Некорректный email';
            return;
        }

        $product_model = new shopProductModel();
        $product = $product_model->getById($product_id);

        if (!$product) {
            $this->errors = 'Товар не найден';
            return;
        }

        $product_name = $product['name'];
        $product_price = shop_currency($product['price']);

        $routing = wa()->getRouting();
        $domain = $routing->getDomain(null, true);
        $route_url = wa()->getRouteUrl('shop/frontend/product', array('product_url' => $product['url']));
        $product_url = $domain . $route_url;

        $body = "Здравствуйте!\n\n";
        $body .= "Ваш подарок — товар: {$product_name}\n";
        $body .= "Цена: {$product_price}\n";
        $body .= "Ссылка: {$product_url}\n\n";
        $body .= "Спасибо, что участвуете в акции!";

        try {
            $message = new waMailMessage('Ваш подарок!', $body);
            $message->setTo($email);

            if ($message->send()) {
                $this->response = array('status' => 'ok');
            } else {
                $this->errors = 'Не удалось отправить письмо';
            }
        } catch (Exception $e) {
            $this->errors = 'Ошибка отправки: ' . $e->getMessage();
        }
    }
}