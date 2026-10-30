(function () {
  "use strict";
  document.querySelectorAll(".current-year").forEach(function (el) { el.textContent = String(new Date().getFullYear()); });
  document.querySelectorAll(".menu-toggle").forEach(function (button) {
    button.addEventListener("click", function () {
      var menu = document.querySelector(".main-nav");
      var backdrop = document.querySelector(".nav-backdrop");
      if (!menu) return;
      var open = !menu.classList.contains("open");
      menu.classList.toggle("open", open);
      button.classList.toggle("active", open);
      if (backdrop) backdrop.classList.toggle("open", open);
      document.body.classList.toggle("nav-open", open);
      button.setAttribute("aria-expanded", String(open));
      button.setAttribute("aria-label", open ? "Close navigation" : "Open navigation");
    });
  });

  // Close when tapping backdrop
  document.querySelectorAll(".nav-backdrop").forEach(function (backdrop) {
    backdrop.addEventListener("click", function () {
      var menu = document.querySelector(".main-nav");
      var button = document.querySelector(".menu-toggle");
      if (menu) menu.classList.remove("open");
      if (button) {
        button.classList.remove("active");
        button.setAttribute("aria-expanded", "false");
        button.setAttribute("aria-label", "Open navigation");
      }
      backdrop.classList.remove("open");
      document.body.classList.remove("nav-open");
    });
  });

  document.querySelectorAll(".main-nav a:not(.dropdown-toggle)").forEach(function (link) {
    link.addEventListener("click", function () {
      var menu = document.querySelector(".main-nav");
      var button = document.querySelector(".menu-toggle");
      var backdrop = document.querySelector(".nav-backdrop");
      if (menu) menu.classList.remove("open");
      if (button) {
        button.classList.remove("active");
        button.setAttribute("aria-expanded", "false");
        button.setAttribute("aria-label", "Open navigation");
      }
      if (backdrop) backdrop.classList.remove("open");
      document.body.classList.remove("nav-open");
    });
  });
  document.querySelectorAll(".demo-form").forEach(function(form){form.addEventListener("submit",function(event){event.preventDefault();var notice=form.querySelector(".notice");if(form.checkValidity()){if(notice)notice.textContent="This form is not connected to a backend.";}else form.reportValidity();});});
  document.querySelectorAll(".filter-input").forEach(function(input){input.addEventListener("input",function(){var query=input.value.trim().toLowerCase();var count=0;document.querySelectorAll(".profile-card").forEach(function(card){var matches=card.textContent.toLowerCase().indexOf(query)!==-1;card.hidden=!matches;if(matches)count++;});document.querySelectorAll(".no-results").forEach(function(el){el.hidden=count!==0;});});});
})();
