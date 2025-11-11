// Wait for DOM and slick to be ready
$(document).ready(function(){
  // Initialize each gallery
  $('.gallery').each(function(index) {
    const $gallery = $(this);
    
    // Initialize slick
    $gallery.on('init', function() {
      // Show gallery after initialization
      $(this).css('opacity', 1);
    }).slick({
      dots: true,
      infinite: true,
      speed: 500,
      slidesToShow: 1,
      slidesToScroll: 1,
      autoplay: true,
      autoplaySpeed: 4000,
      arrows: true,
      prevArrow: '<button type="button" class="slick-prev slick-arrow">‹</button>',
      nextArrow: '<button type="button" class="slick-next slick-arrow">›</button>',
      fade: true,
      cssEase: 'linear',
      adaptiveHeight: false,
      responsive: [
        {
          breakpoint: 768,
          settings: {
            arrows: false,
            dots: true
          }
        }
      ]
    });
  });
});