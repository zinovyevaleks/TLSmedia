$(function() {

    // Custom JS

    //Плавная прокрутка до якоря
    $(".anchor_link").click(function() {
        var _href = $(this).attr("href");
        $("html, body").animate({
            scrollTop: $(_href).offset().top + "px"
        });
        return false;
    });

    // Переключение цветов активных хэдеров карточек в Аудите

    $('.audit_card_header').click(function() {
        if ($(this).hasClass('header-red')) {
            $(this).removeClass('header-red');
        } else {
            $('.audit_card_header').removeClass('header-red');
            $(this).addClass('header-red');
        }

        if ($('#audit_ad').hasClass('header-red')) {
            $('.tab-pane').removeClass('show');
            $('#audit_ad_img').addClass('show');
        } else if ($('#audit_sales').hasClass('header-red')) {
            $('.tab-pane').removeClass('show');
            $('#audit_sales_img').addClass('show');
        } else if ($('#audit_site').hasClass('header-red')) {
            $('.tab-pane').removeClass('show');
            $('#audit_site_img').addClass('show');
        }
    });


    //E-mail Ajax Send
    //Форма заказа
    $("#order").submit(function() {
        var th = $(this);
        $.ajax({
            type: "POST",
            url: "https://tls.media/mail.php",
            data: th.serialize()
        }).done(function() {
            dataLayer.push({
                'event': 'formzayavka1'
            });
            alert("Благодарим вас за обращение. Наш мереджер свяжется с вами в ближайшее время.");
            setTimeout(function() {
                // Done Functions
                th.trigger("reset");
            }, 1000);
        });
        return false;
    });


    //Скачать прайс
    $("#downloadPrice").submit(function() {
        var th = $(this);
        $.ajax({
            type: "POST",
            url: "https://tls.media/mail.php",
            data: th.serialize()
        }).done(function() {});

    });

    //заявка на аудит
    $("#auditForm").submit(function() {
        var th = $(this);
        $.ajax({
            type: "POST",
            url: "https://tls.media/mail.php",
            data: th.serialize()
        }).done(function() {
            dataLayer.push({
                'event': 'formzayavka2'
            });
            alert("Благодарим вас за обращение. Наш мереджер свяжется с вами в ближайшее время.");
            setTimeout(function() {
                // Done Functions
                th.trigger("reset");
            }, 1000);
        });
        return false;
    });

    //FAQ
    $("#faqForm").submit(function() {
        var th = $(this);
        $.ajax({
            type: "POST",
            url: "https://tls.media/mail.php",
            data: th.serialize()
        }).done(function() {
            dataLayer.push({
                'event': 'formzayavka3'
            });
            alert("Благодарим вас за обращение. Наш мереджер свяжется с вами в ближайшее время.");
            setTimeout(function() {
                // Done Functions
                th.trigger("reset");
            }, 1000);
        });
        return false;
    });
});
