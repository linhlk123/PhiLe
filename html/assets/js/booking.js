// Date Picker Configuration
const dateInputs = document.querySelectorAll('input[type="text"][placeholder*="date"]');

dateInputs.forEach(input => {
    input.addEventListener('focus', function() {
        this.type = 'date';
    });
    
    input.addEventListener('blur', function() {
        if (!this.value) {
            this.type = 'text';
        }
    });
});

// Guest Counter
const guestInput = document.querySelector('.input-guest');
if (guestInput) {
    guestInput.addEventListener('click', function(e) {
        e.stopPropagation();
        showGuestPicker(this);
    });
}

function showGuestPicker(input) {
    const picker = document.createElement('div');
    picker.className = 'guest-picker';
    picker.innerHTML = `
        <div class="guest-picker-content">
            <div class="guest-row">
                <span>Người lớn</span>
                <div class="counter">
                    <button class="minus" data-type="adult">-</button>
                    <span class="count" data-type="adult">2</span>
                    <button class="plus" data-type="adult">+</button>
                </div>
            </div>
            <div class="guest-row">
                <span>Trẻ em</span>
                <div class="counter">
                    <button class="minus" data-type="child">-</button>
                    <span class="count" data-type="child">0</span>
                    <button class="plus" data-type="child">+</button>
                </div>
            </div>
            <button class="btn-done">Xong</button>
        </div>
    `;
    
    document.body.appendChild(picker);
    
    const rect = input.getBoundingClientRect();
    picker.style.top = rect.bottom + 10 + 'px';
    picker.style.left = rect.left + 'px';
    
    // Counter functionality
    picker.querySelectorAll('.plus, .minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const type = this.dataset.type;
            const countEl = picker.querySelector(`.count[data-type="${type}"]`);
            let count = parseInt(countEl.textContent);
            
            if (this.classList.contains('plus')) {
                count++;
            } else if (count > 0) {
                count--;
            }
            
            countEl.textContent = count;
        });
    });
    
    // Done button
    picker.querySelector('.btn-done').addEventListener('click', function() {
        const adults = picker.querySelector('.count[data-type="adult"]').textContent;
        const children = picker.querySelector('.count[data-type="child"]').textContent;
        input.value = `${adults} người lớn, ${children} trẻ em`;
        picker.remove();
    });
    
    // Close on outside click
    document.addEventListener('click', function closeP(e) {
        if (!picker.contains(e.target) && e.target !== input) {
            picker.remove();
            document.removeEventListener('click', closeP);
        }
    });
}

// Booking Form Submission
const bookingForms = document.querySelectorAll('.booking-form, .book-form');
bookingForms.forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Validate dates
        const checkin = this.querySelector('[name="checkInDate"]')?.value;
        const checkout = this.querySelector('[name="checkOutDate"]')?.value;
        
        if (checkin && checkout) {
            const checkinDate = new Date(checkin);
            const checkoutDate = new Date(checkout);
            
            if (checkoutDate <= checkinDate) {
                alert('Ngày trả phòng phải sau ngày nhận phòng');
                return;
            }
        }
        
        // Submit booking
        const formData = new FormData(this);
        console.log('Booking data:', Object.fromEntries(formData));
        
        // Redirect to booking engine
        const bookingUrl = this.getAttribute('action');
        window.open(bookingUrl, '_blank');
    });
});

// -------------------------
// Enhanced booking page JS
// -------------------------

// Simple slider for hero
const slides = document.querySelectorAll('.room-slide');
let currentSlide = 0;
function showSlide(idx){
    slides.forEach((s,i)=> s.classList.toggle('active', i===idx));
}
document.getElementById('prevSlide')?.addEventListener('click', ()=>{
    currentSlide = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(currentSlide);
});
document.getElementById('nextSlide')?.addEventListener('click', ()=>{
    currentSlide = (currentSlide + 1) % slides.length;
    showSlide(currentSlide);
});

// Auto rotate every 6s
if (slides.length>0) setInterval(()=>{ currentSlide=(currentSlide+1)%slides.length; showSlide(currentSlide); },6000);



// Handle book button clicks
document.body.addEventListener('click', function(e){
    const el = e.target.closest('.js-book-room');
    if(!el) return;
    e.preventDefault();
    const url = el.dataset.bookingUrl;
    const promo = document.getElementById('booking-promo-code')?.value || '';
    // open booking engine with promo if provided
    const finalUrl = promo ? url + '?promo=' + encodeURIComponent(promo) : url;
    window.open(finalUrl, '_blank');
});

// FAQ accordion
document.querySelectorAll('.faq-q').forEach(btn=>{
    btn.addEventListener('click', ()=>{
        const item = btn.closest('.faq-item');
        item.classList.toggle('open');
    });
});

// Initialize Slick Carousel for room listings
$(document).ready(function(){
    // Initialize each gallery slider in room listings
    $('.listing-with-gallery__gallery .gallery').each(function(){
        if(!$(this).hasClass('slick-initialized')){
            $(this).slick({
                dots: true,
                arrows: true,
                infinite: true,
                speed: 500,
                slidesToShow: 1,
                slidesToScroll: 1,
                autoplay: false,
                prevArrow: '<button type="button" class="slick-prev slick-arrow" aria-label="Previous">‹</button>',
                nextArrow: '<button type="button" class="slick-next slick-arrow" aria-label="Next">›</button>',
                fade: true,
                cssEase: 'ease-in-out'
            });
            
            // Show gallery after initialization
            $(this).css('opacity', '1');
        }
    });
});