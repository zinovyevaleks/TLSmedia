$(function() {

	// Custom JS


  $('.audit_card_header').find('a').click(function() {
      if ($(this).parent('.audit_card_header').hasClass('header-red')) {
          $(this).parent('.audit_card_header').removeClass('header-red');
      }else{
          $('.audit_card_header').removeClass('header-red');
          $(this).parent('.audit_card_header').addClass('header-red');
      }

      if ($('#audit_ad').hasClass('header-red')) {
          $('.tab-pane').removeClass('show');
          $('#audit_ad_img').addClass('show');
      }else if ($('#audit_sales').hasClass('header-red')) {
          $('.tab-pane').removeClass('show');
          $('#audit_sales_img').addClass('show');
      }else if($('#audit_site').hasClass('header-red')) {
          $('.tab-pane').removeClass('show');
          $('#audit_site_img').addClass('show');
      }
  });





});
