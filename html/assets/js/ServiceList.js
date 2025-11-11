// Mở form khi click vào dịch vụ
const serviceCards = document.querySelectorAll('.service-card');
const modal = document.getElementById('BookModal');
const closeModal = document.getElementById('closeModal');
const serviceNameEl = document.getElementById('serviceName');
const bookingForm = document.getElementById('BookForm');

let selectedServiceId = null;

serviceCards.forEach(card => {
  card.addEventListener('click', () => {
    const service = card.getAttribute('data-service');
    selectedServiceId = card.getAttribute('data-service-id');
    serviceNameEl.textContent = service;
    modal.classList.remove('hidden');
  });
});

closeModal.addEventListener('click', () => {
  modal.classList.add('hidden');
  bookingForm.reset();
});

window.addEventListener('click', e => {
  if (e.target === modal) {
    modal.classList.add('hidden');
    bookingForm.reset();
  }
});

bookingForm.addEventListener('submit', async (e) => {
  e.preventDefault();

  const formData = {
    fullname: document.getElementById('fullname').value,
    phone: document.getElementById('phone').value,
    service_id: selectedServiceId,
    date: document.getElementById('date').value,
    time: document.getElementById('time').value,
    quantity: document.getElementById('quantity').value,
    note: document.getElementById('note').value
  };

  try {
    const response = await fetch('/services/book', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
      },
      body: JSON.stringify(formData)
    });

    const result = await response.json();

    if (result.success) {
      alert(`✅ Đặt dịch vụ thành công!\n\nMã đặt dịch vụ: #${result.booking_id}\nKhách hàng: ${result.customer_name}\nTổng tiền: ${result.total_price}\n\nCảm ơn bạn đã chọn Leviosa Resort.`);
      modal.classList.add('hidden');
      bookingForm.reset();
    } else {
      alert(`❌ Lỗi: ${result.message}`);
    }
  } catch (error) {
    console.error('Error:', error);
    alert('❌ Có lỗi xảy ra khi đặt dịch vụ. Vui lòng thử lại!');
  }
});

