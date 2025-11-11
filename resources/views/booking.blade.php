@extends('layouts.app')

@section('title', 'Đặt phòng - Resort')

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/booking.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
    <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
@endsection

@section('content')

    <main>
      <!-- Hero / Room preview with booking form -->
      <section class="room-preview">
        <div class="room-slider">
          <div class="room-slide active">
            <div class="room-image"><img src="{{ asset('assets/images/booking/deluxe-suite.jpg') }}" alt="Room 1"></div>
            <div class="room__content">
              <h2 class="room__title h2-font"><a href="#">Deluxe Suite</a></h2>
              <div class="room__desc"><p>Phòng rộng rãi với ban công hướng biển, phù hợp cho cặp đôi và gia đình nhỏ.</p></div>
              <a class="room__btn" href="#rooms">Xem phòng</a>
            </div>
          </div>
          <div class="room-slide">
            <div class="room-image"><img src="{{ asset('assets/images/booking/bed-beach.jpg') }}" alt="Room 2"></div>
            <div class="room__content">
              <h2 class="room__title h2-font"><a href="#">Sea View Room</a></h2>
              <div class="room__desc"><p>Tận hưởng bình minh trên ban công riêng, view biển tuyệt đẹp.</p></div>
              <a class="room__btn" href="#rooms">Xem phòng</a>
            </div>
          </div>
          <div class="room-slide">
            <div class="room-image"><img src="{{ asset('assets/images/booking/garden-view.jpg') }}" alt="Room 3"></div>
            <div class="room__content">
              <h2 class="room__title h2-font"><a href="#">Garden Villa</a></h2>
              <div class="room__desc"><p>Biệt thự riêng tư giữa vườn xanh, dịch vụ chăm sóc tận tình.</p></div>
              <a class="room__btn" href="#rooms">Xem phòng</a>
            </div>
          </div>
          <div class="slider-nav">
            <button class="slider-arrow" id="nextSlide">›</button>
          </div>
        </div>

      <div class="booking-form-wrapper">
        <form class="booking-form" action="#" method="GET">
          <div class="form-group">
            <input name="checkInDate" type="date" aria-label="Ngày đến" required>
          </div>
          <div class="form-group">
            <input name="checkOutDate" type="date" aria-label="Ngày đi" required>
          </div>
          <div class="form-group">
            <select name="roomType">
              <option value="">Hạng phòng</option>
              <option value="deluxe">Deluxe</option>
              <option value="sea">Sea View</option>
              <option value="villa">Villa</option>
            </select>
          </div>
          <div class="form-group">
            <input name="promo" id="booking-promo-code" placeholder="Mã khuyến mãi (nếu có)">
          </div>
          <button class="btn-search" type="submit">TÌM</button>
        </form>
      </div>
    </section>

    <section id="rooms" class="offers-section">
      <div class="container container--mw1440">
        <h2>Danh sách hạng phòng</h2>
        <div class="list">
          
          <!-- Room 1: Deluxe Garden View Room -->
          <div class="col-12">
            <div class="listing-with-gallery listing-with-gallery--wide">
              <div class="listing-with-gallery__gallery">
                <div class="gallery prevent-fouc">
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/deluxe-suite.jpg') }}" 
                           alt="Deluxe Garden View Room">
                    </div>
                  </div>
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/bed-beach.jpg') }}"
                           alt="Deluxe Garden View Room">
                    </div>
                  </div>
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/garden-view.jpg') }}"
                           alt="Deluxe Garden View Room">
                    </div>
                  </div>
                </div>
              </div>
              <div class="listing-with-gallery__wrapper">
                <div class="listing-with-gallery__content">
                  <h3 class="listing-with-gallery__title t-m">
                    <a href="#" class="title url">Garden View Room</a>
                  </h3>
                  <div class="rte-block icon-list">
                    <div class="rte-bloc__container t-c-m">
                      <ul>
                        <li><span class="glyph-roomsize"></span>35 m² / 377 sqft</li>
                        <li><span class="glyph-capacity"></span>2 Adults</li>
                      </ul>
                    </div>
                  </div>
                  <div class="rte-block">
                    <div class="rte-bloc__container t-c-m">
                      • Tầm nhìn vườn xanh tuyệt đẹp<br>
                      • Ban công riêng thoáng mát<br>
                      • Thiết kế sang trọng, tiện nghi
                    </div>
                  </div>
                  <div class="features-special">
                    <span class="t-s">HIGHLIGHTS</span>
                    <span class="feature__item">
                      <span class="glyph-garden-view"></span>
                      <span class="feature__item__caption">View vườn</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-balcony"></span>
                      <span class="feature__item__caption">Ban công riêng</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-coffee-machine"></span>
                      <span class="feature__item__caption">Máy pha cà phê</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-chrome-cast"></span>
                      <span class="feature__item__caption">Smart TV</span>
                    </span>
                  </div>
                </div>
                <div class="listing-with-gallery__cta">
                  <div class="flex-strect-btn">
                    <a href="{{ route('booking.room') }}" class="btn t-xs btn--gold btn--dynamic-width">Đặt Ngay</a>
                    <a href="#" class="btn t-xs btn--ghost btn--dynamic-width">Chi Tiết</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Room 2: Sea View Room -->
          <div class="col-12">
            <div class="listing-with-gallery listing-with-gallery--wide">
              <div class="listing-with-gallery__gallery">
                <div class="gallery prevent-fouc">
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/bed-beach.jpg') }}"
                           alt="Sea View Room">
                    </div>
                  </div>
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/deluxe-suite.jpg') }}"
                           alt="Sea View Room">
                    </div>
                  </div>
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/garden-view.jpg') }}"
                           alt="Sea View Room">
                    </div>
                  </div>
                </div>
              </div>
              <div class="listing-with-gallery__wrapper">
                <div class="listing-with-gallery__content">
                  <h3 class="listing-with-gallery__title t-m">
                    <a href="#" class="title url">Lake View Premier</a>
                  </h3>
                  <div class="rte-block icon-list">
                    <div class="rte-bloc__container t-c-m">
                      <ul>
                        <li><span class="glyph-roomsize"></span>40 m² / 430 sqft</li>
                        <li><span class="glyph-capacity"></span>2 Adults</li>
                      </ul>
                    </div>
                  </div>
                  <div class="rte-block">
                    <div class="rte-bloc__container t-c-m">
                      • View biển tuyệt đẹp<br>
                      • Ban công rộng rãi<br>
                      • Bồn tắm sang trọng
                    </div>
                  </div>
                  <div class="features-special">
                    <span class="t-s">HIGHLIGHTS</span>
                    <span class="feature__item">
                      <span class="glyph-river-view"></span>
                      <span class="feature__item__caption">View hồ</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-balcony"></span>
                      <span class="feature__item__caption">Ban công riêng</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-chrome-cast"></span>
                      <span class="feature__item__caption">Smart TV</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-USB-charging-station"></span>
                      <span class="feature__item__caption">Cổng sạc USB</span>
                    </span>
                  </div>
                </div>
                <div class="listing-with-gallery__cta">
                  <div class="flex-strect-btn">
                    <a href="{{ route('booking.room') }}" class="btn t-xs btn--gold btn--dynamic-width">Đặt Ngay</a>
                    <a href="#" class="btn t-xs btn--ghost btn--dynamic-width">Chi Tiết</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Room 3: Garden Villa -->
          <div class="col-12">
            <div class="listing-with-gallery listing-with-gallery--wide">
              <div class="listing-with-gallery__gallery">
                <div class="gallery prevent-fouc">
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/garden-view.jpg') }}"
                           alt="Garden Villa">
                    </div>
                  </div>
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/deluxe-suite.jpg') }}"
                           alt="Garden Villa">
                    </div>
                  </div>
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/bed-beach.jpg') }}"
                           alt="Garden Villa">
                    </div>
                  </div>
                </div>
              </div>
              <div class="listing-with-gallery__wrapper">
                <div class="listing-with-gallery__content">
                  <h3 class="listing-with-gallery__title t-m">
                    <a href="#" class="title url">Beach Villa</a>
                  </h3>
                  <div class="rte-block icon-list">
                    <div class="rte-bloc__container t-c-m">
                      <ul>
                        <li><span class="glyph-roomsize"></span>80 m² / 861 sqft</li>
                        <li><span class="glyph-capacity"></span>4 Adults</li>
                      </ul>
                    </div>
                  </div>
                  <div class="rte-block">
                    <div class="rte-bloc__container t-c-m">
                      • Biệt thự riêng biệt<br>
                      • Sân vườn riêng<br>
                      • Phòng khách rộng rãi
                    </div>
                  </div>
                  <div class="features-special">
                    <span class="t-s">HIGHLIGHTS</span>
                    <span class="feature__item">
                      <span class="glyph-Direct-beach-access"></span>
                      <span class="feature__item__caption">Hồ bơi riêng</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-living-area"></span>
                      <span class="feature__item__caption">Phòng khách</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-coffee-machine"></span>
                      <span class="feature__item__caption">Bếp đầy đủ</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-wine-fridge"></span>
                      <span class="feature__item__caption">Tủ rượu</span>
                    </span>
                  </div>
                </div>
                <div class="listing-with-gallery__cta">
                  <div class="flex-strect-btn">
                    <a href="{{ route('booking.room') }}" class="btn t-xs btn--gold btn--dynamic-width">Đặt Ngay</a>
                    <a href="#" class="btn t-xs btn--ghost btn--dynamic-width">Chi Tiết</a>
                  </div>
                </div>
              </div>
            </div>
          </div>


          <!-- Room 4: Royal Villa -->
          <div class="col-12">
            <div class="listing-with-gallery listing-with-gallery--wide">
              <div class="listing-with-gallery__gallery">
                <div class="gallery prevent-fouc">
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/garden-view.jpg') }}"
                           alt="Garden Villa">
                    </div>
                  </div>
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/deluxe-suite.jpg') }}"
                           alt="Garden Villa">
                    </div>
                  </div>
                  <div class="gallery__item">
                    <div class="gallery__image">
                      <img src="{{ asset('assets/images/booking/bed-beach.jpg') }}"
                           alt="Garden Villa">
                    </div>
                  </div>
                </div>
              </div>
              <div class="listing-with-gallery__wrapper">
                <div class="listing-with-gallery__content">
                  <h3 class="listing-with-gallery__title t-m">
                    <a href="#" class="title url">Royal Villa</a>
                  </h3>
                  <div class="rte-block icon-list">
                    <div class="rte-bloc__container t-c-m">
                      <ul>
                        <li><span class="glyph-roomsize"></span>80 m² / 861 sqft</li>
                        <li><span class="glyph-capacity"></span>4 Adults</li>
                      </ul>
                    </div>
                  </div>
                  <div class="rte-block">
                    <div class="rte-bloc__container t-c-m">
                      • Biệt thự riêng biệt<br>
                      • Sân vườn riêng<br>
                      • Phòng khách rộng rãi
                    </div>
                  </div>
                  <div class="features-special">
                    <span class="t-s">HIGHLIGHTS</span>
                    <span class="feature__item">
                      <span class="glyph-Direct-beach-access"></span>
                      <span class="feature__item__caption">Hồ bơi riêng</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-living-area"></span>
                      <span class="feature__item__caption">Phòng khách</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-coffee-machine"></span>
                      <span class="feature__item__caption">Bếp đầy đủ</span>
                    </span>
                    <span class="feature__item">
                      <span class="glyph-wine-fridge"></span>
                      <span class="feature__item__caption">Tủ rượu</span>
                    </span>
                  </div>
                </div>
                <div class="listing-with-gallery__cta">
                  <div class="flex-strect-btn">
                    <a href="{{ route('booking.room') }}" class="btn t-xs btn--gold btn--dynamic-width">Đặt Ngay</a>
                    <a href="#" class="btn t-xs btn--ghost btn--dynamic-width">Chi Tiết</a>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </section>

    <section class="container">
      <div class="card">
        <h2>Câu hỏi thường gặp</h2>
        <div class="faq">
          <div class="faq-item">
            <button class="faq-q">Hủy phòng có được hoàn tiền không?</button>
            <div class="faq-a">Chính sách hủy phòng phụ thuộc vào gói bạn đã chọn. Vui lòng xem chi tiết khi đặt.</div>
          </div>
          <div class="faq-item">
            <button class="faq-q">Giờ nhận và trả phòng?</button>
            <div class="faq-a">Nhận phòng: từ 14:00, Trả phòng: trước 12:00.</div>
          </div>
        </div>
      </div>
    </section>
  </main>
@endsection


@section('scripts')
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
    <script src="{{ asset('assets/js/gallery-slider.js') }}"></script>
    <script src="{{ asset('assets/js/booking.js') }}"></script>
@endsection
