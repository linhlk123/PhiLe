document.addEventListener("DOMContentLoaded", function () {
    // --- KHAI BÁO PHẦN TỬ CHUNG ---

    // --- KHAI BÁO PHẦN TỬ COUPON ---
    const btnApply = document.querySelector(".btn-apply");
    const couponInput = document.querySelector(".coupon-input");
    const couponMessage = document.querySelector(".coupon-message");
    const discountRow = document.querySelector(".summary-row .discount");

    // --- KHAI BÁO PHẦN TỬ THANH TOÁN ---
    const paymentTabs = document.querySelectorAll(".btn-pay");
    const paymentContents = document.querySelectorAll(
        ".payment-method-content"
    );
    const paymentMethodInput = document.getElementById("payment_method");
    const confirmBtn = document.querySelector(".btn-confirm-pay");
    const paymentForm = confirmBtn ? confirmBtn.closest("form") : null;

    // --- 2. XỬ LÝ MÃ GIẢM GIÁ (COUPON) ---
    if (btnApply && couponInput && couponMessage) {
        btnApply.addEventListener("click", function (e) {
            e.preventDefault();

            const couponCode = couponInput.value.trim().toUpperCase();

            couponMessage.style.display = "none";
            couponMessage.classList.remove("success", "error");

            if (couponCode === "LEVIOSA10") {
                couponMessage.textContent =
                    "Áp dụng mã thành công! Giảm thêm 10%.";
                couponMessage.classList.add("success");
                couponMessage.style.display = "block";

                if (discountRow) {
                    // Giả lập cập nhật số tiền giảm giá mới
                    discountRow.textContent = "- 1.500.000₫";
                }

                btnApply.disabled = true;
                couponInput.readOnly = true;
            } else if (couponCode === "") {
                couponMessage.textContent = "Vui lòng nhập mã giảm giá.";
                couponMessage.classList.add("error");
                couponMessage.style.display = "block";
            } else {
                couponMessage.textContent =
                    "Mã giảm giá không hợp lệ hoặc đã hết hạn.";
                couponMessage.classList.add("error");
                couponMessage.style.display = "block";
            }
        });
    }

    // --- 3. XỬ LÝ CHUYỂN ĐỔI TAB THANH TOÁN (THẺ vs QR) ---
    if (paymentTabs.length > 0 && paymentContents.length > 0) {
        paymentTabs.forEach((tab) => {
            tab.addEventListener("click", function (e) {
                e.preventDefault();
                const target = this.getAttribute("data-target");

                paymentTabs.forEach((t) => t.classList.remove("active"));
                paymentContents.forEach((c) => c.classList.remove("active"));

                this.classList.add("active");

                const targetContent = document.querySelector(
                    `.${target}-content`
                );
                if (targetContent) {
                    targetContent.classList.add("active");
                }
                if (paymentMethodInput) {
                    paymentMethodInput.value = target; // card | qr
                }
            });
        });
    }

    // --- 4. XỬ LÝ XÁC NHẬN THANH TOÁN ---

    if (paymentForm && confirmBtn && paymentMethodInput) {
        paymentForm.addEventListener("submit", function (e) {
            const method = paymentMethodInput.value;

            // Chưa chọn phương thức
            if (!method) {
                e.preventDefault();
                alert("Vui lòng chọn phương thức thanh toán!");
                return;
            }

            // Nếu thanh toán bằng thẻ → kiểm tra input
            if (method === "card") {
                const cardNumber = paymentForm.querySelector(
                    'input[name="card_number"]'
                );
                const expiry = paymentForm.querySelector(
                    'input[name="card_expiry"]'
                );
                const cvv = paymentForm.querySelector('input[name="card_cvv"]');

                if (!cardNumber?.value || !expiry?.value || !cvv?.value) {
                    e.preventDefault();
                    alert("Vui lòng nhập đầy đủ thông tin thẻ!");
                    return;
                }
            }

            // Disable nút tránh submit nhiều lần
            confirmBtn.disabled = true;
            confirmBtn.innerText = "Đang xử lý thanh toán...";
        });
    }
});
