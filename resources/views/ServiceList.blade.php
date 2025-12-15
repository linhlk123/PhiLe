@include('layouts.header')

<link rel="stylesheet" href="{{ asset('assets/css/ServiceList.css') }}">

<div class="service-page">

  <section class="top-service">
    <h1>Dịch Vụ Tại Leviosa Resort</h1>
    <p>Khám phá các dịch vụ đẳng cấp và tiện nghi tại Leviosa Resort để kỳ nghỉ của bạn trở nên hoàn hảo hơn.</p>
  </section>

  <section class="service-grid">
    <div class="service-card" data-service="Spa" data-service-id="1">
      <img src="{{ asset('assets/images/spa.png') }}" alt="Spa">
      <h3>Spa</h3>
      <p>Thư giãn tuyệt đối với liệu trình spa cao cấp.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 1.000.000đ / người</p>
    </div>

    <div class="service-card" data-service="Tắm bùn & suối nước nóng" data-service-id="2">
      <img src="{{ asset('assets/images/BunSuoi.jpg') }}" alt="Tắm bùn">
      <h3>Tắm bùn & Suối nước nóng</h3>
      <p>Tận hưởng nguồn khoáng chất tự nhiên giúp phục hồi năng lượng.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 100.000đ / người</p>
    </div>

    <div class="service-card" data-service="Nhà hàng" data-service-id="3">
      <img src="{{ asset('assets/images/NhaHang.jpg') }}" alt="Nhà hàng">
      <h3>Nhà hàng</h3>
      <p>Thưởng thức ẩm thực đa dạng từ Á đến Âu.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 350.000đ / người</p>
    </div>

    <div class="service-card" data-service="Khu vui chơi" data-service-id="4">
      <img src="{{ asset('assets/images/KhuVuiChoi.jpg') }}" alt="Khu vui chơi">
      <h3>Khu vui chơi</h3>
      <p>Không gian giải trí cho cả gia đình.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 150.000đ / người</p>
    </div>

    <div class="service-card" data-service="Fitness Center (Gym & Yoga)" data-service-id="5">
      <img src="{{ asset('assets/images/fitness.jpg') }}" alt="Fitness">
      <h3>Fitness Center (Gym & Yoga)</h3>
      <p>Rèn luyện sức khỏe cùng huấn luyện viên chuyên nghiệp.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 50.000đ / người</p>
    </div>

    <div class="service-card" data-service="Hồ bơi" data-service-id="6">
      <img src="{{ asset('assets/images/HoBoi.jpg') }}" alt="Hồ bơi">
      <h3>Hồ bơi</h3>
      <p>Thả mình giữa làn nước trong xanh giữa thiên nhiên.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 50.000đ / người</p>
    </div>

    <div class="service-card" data-service="Bar" data-service-id="7">
      <img src="{{ asset('assets/images/bar.jpg') }}" alt="Bar">
      <h3>Bar</h3>
      <p>Không gian âm nhạc và cocktail độc đáo.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 100.000đ / người</p>
    </div>

    <div class="service-card" data-service="Đặt sân thể thao" data-service-id="8">
      <img src="{{ asset('assets/images/San.png') }}" alt="Đặt sân">
      <h3>Đặt sân thể thao</h3>
      <p>Cầu lông, pickleball, bóng đá, bóng bàn, bóng rổ, bóng chuyền, golf.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 100.000đ / sân</p>
    </div>

    <div class="service-card" data-service="Đặt xe đưa đón" data-service-id="9">
      <img src="{{ asset('assets/images/XeDien.jpg') }}" alt="Xe đưa đón">
      <h3>Đặt xe đưa đón</h3>
      <p>Đưa đón tận nơi, đảm bảo thoải mái và an toàn.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 10.000đ / lượt</p>
    </div>

    <div class="service-card" data-service="Đặt sảnh tổ chức sự kiện" data-service-id="10">
      <img src="{{ asset('assets/images/SanhHoiNghi.jpg') }}" alt="Sảnh tổ chức sự kiện">
      <h3>Đặt sảnh tổ chức sự kiện</h3>
      <p>Sảnh rộng rãi, sang trọng và lịch sự tạo cảm giác trang trọng.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 5.000.000đ / tiệc</p>
    </div>

    <div class="service-card" data-service="Đặt phòng karaoke" data-service-id="11">
      <img src="{{ asset('assets/images/karaoke.jpg') }}" alt="Karaoke">
      <h3>Đặt phòng karaoke</h3>
      <p>Dàn âm thanh "khủng", quẩy cực sung cùng hội bạn.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 200.000đ / phòng</p>
    </div>

    <div class="service-card" data-service="Đặt rạp chiếu phim" data-service-id="12">
      <img src="{{ asset('assets/images/RapChieuPhim.jpg') }}" alt="Rạp chiếu phim">
      <h3>Rạp chiếu phim</h3>
      <p>Phòng chiếu riêng tư, màn hình cực nét cho trải nghiệm sống động.</p>
      <p class="price"><i class="fas fa-money-bill-wave"></i> 200.000đ / phòng</p>
    </div>
  </section>

  <!-- Popup form -->
  <div id="BookModal" class="modal hidden">
    <div class="modal-cont">
      <span id="closeModal" class="close">&times;</span>
      <h2>Đặt Dịch Vụ <span id="serviceName"></span></h2>
      <form id="BookForm">
        @csrf
        <div class="Fgroup">
          <label for="fullname">Họ và tên:</label>
          <input type="text" id="fullname" placeholder="Nhập họ và tên của bạn" required>
        </div>

        <div class="Fgroup">
          <label for="phone">Số điện thoại:</label>
          <input type="tel" id="phone" placeholder="VD: 0987654321" required>
        </div>

        <div class="Fgroup">
          <label for="date">Ngày sử dụng dịch vụ:</label>
          <input type="date" id="date" required>
        </div>

        <div class="Fgroup">
          <label for="time">Giờ bắt đầu:</label>
          <input type="time" id="time" required>
        </div>

        <div class="Fgroup">
          <label for="quantity">Số lượng người:</label>
          <input type="number" id="quantity" min="1" max="20" value="1" required>
        </div>

        <div class="Fgroup">
          <label for="note">Ghi chú thêm (nếu có):</label>
          <textarea id="note" rows="3" placeholder="Ví dụ: muốn có huấn luyện viên riêng..."></textarea>
        </div>

        <button type="submit" class="btn">Đặt dịch vụ</button>
      </form>
    </div>
  </div>
</div>

<script src="{{ asset('assets/js/ServiceList.js') }}"></script>

@include('layouts.footer')