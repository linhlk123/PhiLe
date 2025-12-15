<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Điều khoản & Chính sách - Leviosa Resort</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/policy.css') }}">
</head>

<body>

    {{-- HEADER --}}
    @include('layouts.header')

    {{-- TERMS SECTION --}}
    <section class="terms-section">

        {{-- CONTAINER 1 --}}
        <div class="terms-container">
            <h2>Điều khoản sử dụng</h2>

            <p>
                Khi truy cập và sử dụng website Leviosa Resort, quý khách đồng ý
                tuân thủ các điều khoản và điều kiện được nêu dưới đây.
            </p>

            <ul>
                <li>Thông tin trên website chỉ mang tính tham khảo và có thể thay đổi.</li>
                <li>Leviosa Resort có quyền chỉnh sửa nội dung mà không cần báo trước.</li>
                <li>Người dùng không được sao chép, sử dụng nội dung cho mục đích thương mại.</li>
                <li>Mọi hành vi vi phạm sẽ được xử lý theo quy định pháp luật.</li>
            </ul>
        </div>

        {{-- CONTAINER 2 --}}
        <div class="terms-container">
            <h2>Chính sách & Quy định</h2>

            <p>
                Leviosa Resort cam kết bảo vệ quyền riêng tư và trải nghiệm
                nghỉ dưỡng an toàn cho khách hàng.
            </p>

            <ul>
                <li>Thông tin cá nhân của khách hàng được bảo mật tuyệt đối.</li>
                <li>Chính sách hoàn – hủy đặt phòng áp dụng theo từng gói dịch vụ.</li>
                <li>Khách hàng có trách nhiệm tuân thủ nội quy trong khu nghỉ dưỡng.</li>
                <li>Leviosa Resort có quyền từ chối phục vụ nếu vi phạm quy định chung.</li>
            </ul>
        </div>

    </section>

    {{-- FOOTER --}}
    @include('layouts.footer')

</body>

</html>