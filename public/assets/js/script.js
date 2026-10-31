(function () {
  "use strict";
  document.querySelectorAll(".current-year").forEach(function (el) { el.textContent = String(new Date().getFullYear()); });

  function initPublicNav() {
    var toggle = document.querySelector(".menu-toggle");
    var nav = document.querySelector(".main-nav");
    var backdrop = document.querySelector(".nav-backdrop");
    var closeBtn = document.querySelector(".mobile-nav-close");

    function setOpen(open) {
      if (!nav) return;
      nav.classList.toggle("open", open);
      if (toggle) {
        toggle.classList.toggle("active", open);
        toggle.setAttribute("aria-expanded", String(open));
        toggle.setAttribute("aria-label", open ? "Close navigation" : "Open navigation");
      }
      if (backdrop) backdrop.classList.toggle("open", open);
      document.body.classList.toggle("nav-open", open);
    }

    if (toggle) {
      toggle.onclick = function (e) {
        e.preventDefault();
        e.stopPropagation();
        var isOpen = nav && nav.classList.contains("open");
        setOpen(!isOpen);
      };
    }

    if (closeBtn) {
      closeBtn.onclick = function (e) {
        e.preventDefault();
        e.stopPropagation();
        setOpen(false);
      };
    }

    if (backdrop) {
      backdrop.onclick = function (e) {
        e.preventDefault();
        e.stopPropagation();
        setOpen(false);
      };
    }

    if (nav) {
      nav.querySelectorAll("a:not(.dropdown-toggle)").forEach(function (link) {
        link.addEventListener("click", function () {
          setOpen(false);
        });
      });
    }
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", initPublicNav);
  } else {
    initPublicNav();
  }

  document.querySelectorAll(".demo-form").forEach(function(form){form.addEventListener("submit",function(event){event.preventDefault();var notice=form.querySelector(".notice");if(form.checkValidity()){if(notice)notice.textContent="This form is not connected to a backend.";}else form.reportValidity();});});
  document.querySelectorAll(".filter-input").forEach(function(input){input.addEventListener("input",function(){var query=input.value.trim().toLowerCase();var count=0;document.querySelectorAll(".profile-card").forEach(function(card){var matches=card.textContent.toLowerCase().indexOf(query)!==-1;card.hidden=!matches;if(matches)count++;});document.querySelectorAll(".no-results").forEach(function(el){el.hidden=count!==0;});});});
})();
