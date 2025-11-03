/*=============== toggle icon navbar =========== */

let menuIcon = document.querySelector(".mobile-burger-menu");
let navbar = document.querySelector(".navbar");

function toggleMenu() {
  if (menuIcon) {
    menuIcon.classList.toggle("open");
  }
  if (navbar) {
    navbar.classList.toggle("active");
  }
  // Prevent body scroll when menu is open
  if (navbar && navbar.classList.contains("active")) {
    document.body.style.overflow = "hidden";
  } else {
    document.body.style.overflow = "";
  }
}

if (menuIcon) {
  menuIcon.onclick = toggleMenu;
}

// Close menu when clicking outside
document.addEventListener("click", (e) => {
  if (navbar && navbar.classList.contains("active")) {
    if (!navbar.contains(e.target) && 
        !menuIcon?.contains(e.target)) {
      if (menuIcon) {
        menuIcon.classList.remove("open");
      }
      navbar.classList.remove("active");
      document.body.style.overflow = "";
    }
  }
});

/*=============== sticky header =========== */
window.onscroll = () => {
  let header = document.querySelector(".header");

  header.classList.toggle("sticky", window.scrollY > 100);
};

/*=============== dark mode =========== */

(function() {
  const body = document.body;
  
  // Load saved theme immediately (before DOM ready to prevent flash)
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark") {
    body.classList.add("dark");
  } else {
    body.classList.remove("dark");
  }
  
  // Function to toggle dark mode
  function toggleDarkMode(e) {
    if (e) {
      e.preventDefault();
      e.stopPropagation();
    }
    
    body.classList.toggle("dark");
    
    if (body.classList.contains("dark")) {
      localStorage.setItem("theme", "dark");
    } else {
      localStorage.setItem("theme", "light");
    }
  }
  
  // Use event delegation on document to catch all dark mode clicks
  document.addEventListener("click", function(e) {
    // Check if clicked element or its parent has dark-mode class
    const darkModeButton = e.target.closest(".dark-mode");
    if (darkModeButton) {
      toggleDarkMode(e);
    }
  });
  
  // Also add direct listeners when DOM is ready
  function initDarkMode() {
    const darkModeButtons = document.querySelectorAll(".dark-mode");
    darkModeButtons.forEach((button) => {
      if (button) {
        button.addEventListener("click", toggleDarkMode);
      }
    });
  }

  // Initialize dark mode when DOM is ready
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initDarkMode);
  } else {
    // DOM is already ready
    initDarkMode();
  }
})();

// swiper latest post
var swiper = new Swiper(".latest-post-slider", {
  slidesPerView: 3,
  spaceBetween: 30,
  autoplay: true,
  loop: true,
  // Navigation arrows
  navigation: {
    nextEl: ".next-slide",
    prevEl: ".prev-slide",
  },
  breakpoints: {
    340: {
      slidesPerView: 1,
    },
    940: {
      slidesPerView: 3,
    },
  },
});

// post filter
const categoryBoxes = document.querySelectorAll(".category-box");
const filterPosts = document.querySelectorAll(".filter-post");

categoryBoxes.forEach((categoryBox) => {
  categoryBox.addEventListener("click", () => {
    const selectedFilter = categoryBox.dataset.filter;

    filterPosts.forEach((post) => {
      const postFilter = post.dataset.category.toLowerCase();
      if (selectedFilter === "all" || postFilter === selectedFilter) {
        post.style.display = "flex";
      } else {
        post.style.display = "none";
      }
    });

    categoryBoxes.forEach((box) => {
      box.classList.remove("active");
    });

    categoryBox.classList.add("active");
  });
});

// two design home

var swiper = new Swiper(".twoDesing", {
  slidesPerView: "auto",
  spaceBetween: 10,
  centeredSlides: true,
  initialSlide: 1,
  // Navigation arrows
  navigation: {
    nextEl: ".next-slide-two",
    prevEl: ".prev-slide-two",
  },
  breakpoints: {
    // Mobile: single slide
    320: {
      slidesPerView: 1,
      spaceBetween: 10,
      centeredSlides: true,
    },
    // Tablet Portrait: single slide for better UX
    768: {
      slidesPerView: 1,
      spaceBetween: 20,
      centeredSlides: true,
    },
    // Desktop: auto (3D effect)
    1024: {
      slidesPerView: "auto",
      spaceBetween: 10,
      centeredSlides: true,
    },
  },
});

