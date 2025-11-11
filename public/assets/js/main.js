// MAIN.JS — xử lý menu, hiệu ứng header, lazy load, lightbox, v.v.
document.addEventListener("DOMContentLoaded", function () {
  const menuToggle = document.querySelector(".menu-toggle");
  const menuClose = document.querySelector(".menu-close");
  const mainNav = document.querySelector(".main-nav");
  const navItems = document.querySelectorAll(".nav-item.dropdown");

  // Mở menu
  menuToggle.addEventListener("click", () => {
    mainNav.classList.add("active");
  });

  // Đóng menu
  menuClose.addEventListener("click", () => {
    mainNav.classList.remove("active");
    navItems.forEach(item => item.classList.remove("active"));
  });


});

  // ====== SMOOTH SCROLL ======
  const smoothScrollLinks = document.querySelectorAll('a[href^="#"]');
  smoothScrollLinks.forEach(link => {
    link.addEventListener("click", function (e) {
      e.preventDefault();
      const targetId = this.getAttribute("href");
      const targetElement = document.querySelector(targetId);
      if (targetElement) {
        targetElement.scrollIntoView({ behavior: "smooth" });
      }
    });
  });

  // ====== HEADER SCROLL EFFECT ======
  let lastScroll = 0;
  const header = document.querySelector(".header");

  window.addEventListener("scroll", function () {
    const currentScroll = window.pageYOffset;

    if (currentScroll > 100) header.classList.add("scrolled");
    else header.classList.remove("scrolled");

    if (currentScroll > lastScroll && currentScroll > 500)
      header.style.transform = "translateY(-100%)";
    else header.style.transform = "translateY(0)";

    lastScroll = currentScroll;
  });

  // ====== LANGUAGE SELECTOR ======
  const languageSelector = document.querySelector(".language-selector");
  if (languageSelector) {
    languageSelector.addEventListener("click", function () {
      this.classList.toggle("active");
    });
  }

  // ====== FORM VALIDATION ======
  const forms = document.querySelectorAll("form");
  forms.forEach(form => {
    form.addEventListener("submit", function (e) {
      e.preventDefault();
      const inputs = this.querySelectorAll("[required]");
      let isValid = true;

      inputs.forEach(input => {
        if (!input.value.trim()) {
          isValid = false;
          input.classList.add("error");
        } else {
          input.classList.remove("error");
        }
      });

      if (isValid) {
        console.log("Form submitted");
        this.reset();
        showNotification("Cảm ơn bạn đã liên hệ!");
      }
    });
  });

  function showNotification(message) {
    const notification = document.createElement("div");
    notification.className = "notification";
    notification.textContent = message;
    document.body.appendChild(notification);

    setTimeout(() => notification.classList.add("show"), 100);
    setTimeout(() => {
      notification.classList.remove("show");
      setTimeout(() => notification.remove(), 300);
    }, 3000);
  }

  // ====== LAZY LOAD ======
  const images = document.querySelectorAll("img[data-src]");
  const imageObserver = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        const img = entry.target;
        img.src = img.dataset.src;
        img.removeAttribute("data-src");
        observer.unobserve(img);
      }
    });
  });
  images.forEach(img => imageObserver.observe(img));

  // ====== ANIMATE ON SCROLL ======
  const animatedElements = document.querySelectorAll(".data-fade-in");
  const elementObserver = new IntersectionObserver(entries => {
    entries.forEach(entry => {
      if (entry.isIntersecting) entry.target.classList.add("animate");
    });
  }, { threshold: 0.1 });
  animatedElements.forEach(el => elementObserver.observe(el));

  // ====== FLOATING BUTTONS ======
  const btnPhone = document.querySelector(".btn-phone");
  const btnChat = document.querySelector(".btn-chat");

  if (btnPhone) btnPhone.addEventListener("click", () => {
    window.location.href = "tel:+84235393337";
  });

  if (btnChat) btnChat.addEventListener("click", () => {
    console.log("Open chat");
  });

  // ====== GALLERY LIGHTBOX ======
  const galleryItems = document.querySelectorAll(".gallery-item");
  galleryItems.forEach(item => {
    item.addEventListener("click", function () {
      const imgSrc = this.querySelector("img").src;
      openLightbox(imgSrc);
    });
  });

  function openLightbox(src) {
    const lightbox = document.createElement("div");
    lightbox.className = "lightbox";
    lightbox.innerHTML = `
      <div class="lightbox-content">
        <img src="${src}" alt="Image">
        <button class="close-lightbox">&times;</button>
      </div>`;
    document.body.appendChild(lightbox);

    setTimeout(() => lightbox.classList.add("active"), 10);

    lightbox.querySelector(".close-lightbox").addEventListener("click", function () {
      lightbox.classList.remove("active");
      setTimeout(() => lightbox.remove(), 300);
    });
  }

  // ====== RESTAURANT IMAGE PREVIEW ======
  const restaurantItems = document.querySelectorAll(".restaurant-list li");
  const restaurantImg = document.getElementById("restaurant-img");

  restaurantItems.forEach(item => {
    item.addEventListener("mouseenter", () => {
      const newImg = item.getAttribute("data-img");
      restaurantImg.style.opacity = 0;
      setTimeout(() => {
        restaurantImg.src = newImg;
        restaurantImg.style.opacity = 1;
      }, 100);
    });
  });

// ====== LOADING ANIMATION ======
window.addEventListener("load", function () {
  document.body.classList.add("loaded");
});
