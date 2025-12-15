@include('layouts.header')

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-image">
        <img src="{{ asset('assets/images/index/index-1.jpg') }}" alt="Leviosa Resort">
    </div>

    <!-- Action Buttons: Checkin & Booking -->
    <div class="booking-form-wrapper">
        <div class="booking-form" style="display:flex; gap:16px; align-items:center; justify-content:center;">
            <a class="btn-detail" href="{{ route('checkin.page') }}"
                style="display:inline-flex; align-items:center; gap:10px; padding:12px 18px; border-radius:999px; box-shadow:0 8px 24px rgba(0,0,0,0.08); text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fas fa-door-open" style="font-size:16px"></i>
                <span>Check-in</span>
            </a>
            <a class="room__btn" href="{{ route('booking') }}"
                style="display:inline-flex; align-items:center; gap:10px; padding:12px 18px; border-radius:999px; box-shadow:0 8px 24px rgba(0,0,0,0.08); text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fas fa-bed" style="font-size:16px"></i>
                <span>Đặt phòng</span>
            </a>
            <a class="btn-offer" href="{{ route('services') }}"
                style="display:inline-flex; align-items:center; gap:10px; padding:12px 18px; border-radius:999px; box-shadow:0 8px 24px rgba(0,0,0,0.08); text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fas fa-concierge-bell" style="font-size:16px"></i>
                <span>Đặt dịch vụ</span>
            </a>
            <a class="btn-detail" href="{{ route('payment.list') }}"
                style="display:inline-flex; align-items:center; gap:10px; padding:12px 18px; border-radius:999px; box-shadow:0 8px 24px rgba(0,0,0,0.08); text-transform:uppercase; letter-spacing:0.5px;">
                <i class="fas fa-credit-card" style="font-size:16px"></i>
                <span>Thanh toán</span>
            </a>
        </div>
    </div>
</section>

<!-- Introduction Section -->
<section class="intro-section">
    <div class="container">
        <div class="intro-content" style="translate: none; rotate: none; scale: none; transform: translate(0px, 0px); opacity: 1;">
            <h1>Một Dấu Ấn Tinh Hoa<br>Vượt Thời Gian</h1>
            <p style="font-size: 16px">Nép mình giữa những hàng tre xanh mướt và những khoảnh vườn nhiệt đới tươi mát, Legacy Hoi An Resort là nơi hòa quyện giữa nét đẹp kiến trúc truyền thống Việt Nam và sự tinh tế hiện đại. Từng không gian tại đây được thiết kế để mang đến sự yên bình, giúp bạn tạm gác lại nhịp sống hối hả và tìm về với chính mình.</p>
        </div>

        <div class="intro-gallery span-10">
            <div class="gallery-grid grid-span-10">
                <div class="gallery-item large">
                    <img src="{{ asset('assets/images/index/index-2.jpg') }}" alt="Pool">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('assets/images/index/index-3.jpg') }}" alt="Interior">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('assets/images/index/index-4.jpg') }}" alt="Beach">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('assets/images/index/index-5.jpg') }}" alt="Room">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('assets/images/index/index-6.jpg') }}" alt="Garden">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('assets/images/index/index-7.jpg') }}" alt="Terrace">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('assets/images/index/index-8.jpg') }}" alt="Lobby">
                </div>
                <div class="gallery-item">
                    <img src="{{ asset('assets/images/index/index-9.jpg') }}" alt="Exterior">
                </div>
            </div>
        </div>
    </div>
</section>


<section class="room-preview">
    <div class="room-slider">
        <!-- Slide 1 -->
        <div class="room-slide active">
            <div class="room-image">
                <img src="{{ asset('assets/images/index/index-10.jpg') }}" alt="Suites">
            </div>
            <div class="room__content">
                <h5 class="room__title h2-font">
                    <a href="{{ route('booking') }}">Suites</a>
                </h5>
                <div class="room__desc">
                    <p>Ốc đảo tinh tế nuôi dưỡng tâm hồn</p>
                </div>
                <a class="room__btn" href="{{ route('booking') }}">
                    <span>KHÁM PHÁ</span>
                </a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="room-slide">
            <div class="room-image">
                <img src="{{ asset('assets/images/index/index-10-2.jpg') }}" alt="Villas">
            </div>
            <div class="room__content">
                <h5 class="room__title h2-font">
                    <a href="{{ route('booking') }}">Villas</a>
                </h5>
                <div class="room__desc">
                    <p>Không gian riêng tư tuyệt đối</p>
                </div>
                <a class="room__btn" href="{{ route('booking') }}">
                    <span>KHÁM PHÁ</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Navigation Arrows -->
    <div class="slider-nav">
        <button class="slider-arrow prev-arrow">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="slider-arrow next-arrow">
            <i class="fas fa-chevron-right"></i>
        </button>
    </div>
</section>


<section class="restaurant-section">
    <div class="container">
        <h2>Nhà Hàng & Bar</h2>

        <div class="restaurant-content">
            <!-- Hình ảnh bên trái -->
            <div class="restaurant-image">
                <img id="restaurant-img" src="{{ asset('assets/images/index/index-12.jpg') }}" alt="Restaurant">
            </div>

            <!-- Thông tin bên phải -->
            <div class="restaurant-info">
                <p class="restaurant-desc">

                    Khách lưu trú tại Leviosa có thể tận hưởng không gian sang trọng cùng các tiện ích hiện đại như Spa cao cấp, Tắm bùn & Suối nước nóng thiên nhiên, Hồ bơi ngoài trời, và Fitness Center (Gym & Yoga) dành cho những ai yêu thích vận động.

                    Ngoài ra, Leviosa còn cung cấp nhà hàng đa phong cách, quầy bar, khu vui chơi, cùng các dịch vụ đặt riêng như sân thể thao, xe đưa đón, phòng karaoke, rạp chiếu phim và sảnh tổ chức sự kiện – đáp ứng nhu cầu nghỉ dưỡng, giải trí và tổ chức hội nghị của mọi đối tượng khách hàng.

                    Với đội ngũ nhân viên tận tâm và không gian chuẩn 5 sao, Leviosa Resort hứa hẹn mang đến cho du khách kỳ nghỉ đáng nhớ, nơi thư giãn gặp gỡ sự tinh tế.
                </p>

                <!-- Danh sách nhà hàng -->
                <ul class="restaurant-list">
                    <li data-img="{{ asset('assets/images/index/index-12.jpg') }}">Nhà hàng Field ></li>
                    <li data-img="{{ asset('assets/images/index/index-12-2.jpg') }}">Nhà hàng Sen ></li>
                    <li data-img="{{ asset('assets/images/index/index-12-3.jpg') }}">Pad & Buff Bar ></li>
                </ul>

                <a href="{{ route('services') }}" class="btn-detail">KHÁM PHÁ</a>
            </div>
        </div>
    </div>
</section>

<!-- Offers Section -->
<section class="offers-section">
    <div class="container">
        <h2>Ưu đãi</h2>

        <div class="offers-grid">
            <div class="offer-card">
                <img src="{{ asset('assets/images/index/index-13.jpg') }}" alt="Offer 1">
                <div class="offer-content">
                    <h3>Ưu Đãi Đặt Phòng Sớm</h3>
                    <p>Đặt trước 60 ngày và tiết kiệm 30% trên tất cả các hạng phòng. Bao gồm bữa sáng buffet miễn phí.</p>
                    <a href="{{ route('booking') }}" class="btn-offer">XEM CHI TIẾT</a>
                </div>
            </div>

            <div class="offer-card">
                <img src="{{ asset('assets/images/index/index-14.jpg') }}" alt="Offer 2">
                <div class="offer-content">
                    <h3>Gói Khám Phá Tuyệt Vời</h3>
                    <p>Gói combo hấp dẫn gồm tour tham quan phố cổ, massage truyền thống và bữa tối lãng mạn.</p>
                    <a href="{{ route('services') }}" class="btn-offer">XEM CHI TIẾT</a>
                </div>
            </div>

            <div class="offer-card">
                <img src="../assets/images/index/index-15.png" alt="Offer 3">
                <div class="offer-content">
                    <h3>Nghỉ Dưỡng Dài Ngày</h3>
                    <p>Nghỉ từ 5 đêm trở lên, nhận ưu đãi đặc biệt cùng nhiều dịch vụ cao cấp miễn phí.</p>
                    <a href="{{ route('booking') }}" class="btn-offer">XEM CHI TIẾT</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Map Section -->
<section class="map-section">
    <div class="container">
        <h2>Các Điểm Tham Quan</h2>

        <div class="map-content">
            <div class="map-container">
                <img src="../assets/images/index/MAP.png" alt="Map">
            </div>

            <div class="attractions-list">
                <div class="attraction-item">
                    <img src="../assets/images/index/tham-quan/anh-1.png
                        " alt="Beach">
                    <div class="attraction-info">
                        <h4>1. Bãi biển Cửa Đại</h4>
                        <span>4.0km</span>
                    </div>
                </div>

                <div class="attraction-item">
                    <img src="../assets/images/index/tham-quan/anh-2.png" alt="Old Town">
                    <div class="attraction-info">
                        <h4>2. Khu Phố Cổ Hội An</h4>
                        <span>2.5km</span>
                    </div>
                </div>

                <div class="attraction-item">
                    <img src="../assets/images/index/tham-quan/anh-3.png" alt="Museum">
                    <div class="attraction-info">
                        <h4>3. Bảo tàng Phúc Kiến</h4>
                        <span>3.2km</span>
                    </div>
                </div>

                <div class="attraction-item">
                    <img src="../assets/images/index/tham-quan/anh04.png" alt="Bridge">
                    <div class="attraction-info">
                        <h4>4. Chùa Cầu Nhật Bản</h4>
                        <span>3.0km</span>
                    </div>
                </div>

                <div class="attraction-item">
                    <img src="../assets/images/index/tham-quan/anh-5.png" alt="Sanctuary">
                    <div class="attraction-info">
                        <h4>5. Khu Du Lịch Nhật Bản</h4>
                        <span>5.8km</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <h2>Đăng Ký Nhận Bản Tin Của Chúng Tôi</h2>
        <p>Nhận thông tin về ưu đãi đặc biệt và tin tức mới nhất từ Leviosa Resort</p>

        <form class="newsletter-form">
            <input type="email" placeholder="Địa chỉ email của bạn" required>
            <button type="submit">ĐĂNG KÝ</button>
        </form>
    </div>
</section>

@include('layouts.footer')