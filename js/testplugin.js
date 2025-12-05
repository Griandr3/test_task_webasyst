$(function () {
    var $wrapper = $('.gift4you-wrapper');
    var randomUrl = $wrapper.attr('data-random-url');
    var emailUrl = $wrapper.attr('data-email-url');

    $('#gift4you-spin').on('click', function () {

        var $button = $(this);
        $button.prop('disabled', true).text('Загрузка...');
        $('#gift4you-result').html('<p>Загрузка...</p>');

        if (!randomUrl) {
            $('#gift4you-result').html('<p>Ошибка: URL не настроен</p>');
            $button.prop('disabled', false).text('Крутить');
            return;
        }

        $.ajax({
            url: randomUrl,
            type: 'POST',
            dataType: 'json',
            success: function (response) {

                var data = response;
                if (response.data) {
                    data = response.data;
                }

                if (!data || !data.id) {
                    $('#gift4you-result').html('<p>Ошибка: товар не найден.</p>');
                    $button.prop('disabled', false).text('Крутить');
                    return;
                }

                var html =
                    '<div class="gift4you-card">' +
                    '<h3>' + data.name + '</h3>' +
                    (data.image ? '<img src="' + data.image + '" class="gift4you-img">' : '') +
                    '<p><strong>Цена:</strong> ' + data.price + '</p>' +
                    '<div class="gift4you-email-form">' +
                    '<input type="email" id="gift4you-email" placeholder="Ваш email">' +
                    '<button id="gift4you-send" data-product="' + data.id + '">Получить письмо</button>' +
                    '</div>' +
                    '</div>';

                $('#gift4you-result').html(html);
                $button.prop('disabled', false).text('Крутить еще раз');
            }
        });
    });

    $(document).on('click', '#gift4you-send', function () {
        var email = $('#gift4you-email').val();
        var product_id = $(this).data('product');

        if (!email) {
            alert('Введите email');
            return;
        }

        var $button = $(this);
        $button.prop('disabled', true).text('Отправка...');

        $.ajax({
            url: emailUrl,
            type: 'POST',
            dataType: 'json',
            data: {
                email: email,
                product_id: product_id
            },
            success: function (response) {

                try {
                    var data = JSON.parse(response);
                    if (data.status === 'ok') {
                        alert('Письмо отправлено!');
                        $('#gift4you-email').val('');
                    } else {
                        alert('Ошибка: ' + (data.message || 'Неизвестная ошибка'));
                    }
                } catch (e) {
                    console.error("Parse error:", e);
                    console.error("Response was:", response);
                    alert('Ошибка сервера. Смотрите консоль.');
                }
                $button.prop('disabled', false).text('Получить письмо');
            }
        });
    });
});