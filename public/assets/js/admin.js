/**
 * Mobile sidebar drawer for the Admin panel and the Bhudev portal.
 * Plain JavaScript, delegated on the document, so it works regardless of
 * script order, jQuery availability, or the topbar being re-rendered.
 */
(function () {
  "use strict";

  function getSidebar() {
    return document.querySelector(".admin-sidebar");
  }

  function setSidebar(open) {
    var sidebar = getSidebar();
    if (!sidebar) return;
    var overlay = document.querySelector(".sidebar-overlay");
    sidebar.classList.toggle("open", open);
    if (overlay) overlay.classList.toggle("open", open);
    document.body.classList.toggle("sidebar-open", open);
    var toggle = document.querySelector(".sidebar-toggle-btn");
    if (toggle) toggle.setAttribute("aria-expanded", open ? "true" : "false");
  }

  function handleToggleEvent(event) {
    var target = event.target;
    if (!(target instanceof Element)) return;

    if (target.closest(".sidebar-toggle-btn")) {
      event.preventDefault();
      var sidebar = getSidebar();
      setSidebar(!(sidebar && sidebar.classList.contains("open")));
      return;
    }

    if (target.closest(".sidebar-close-btn") || target.classList.contains("sidebar-overlay")) {
      event.preventDefault();
      setSidebar(false);
      return;
    }

    if (target.closest(".admin-sidebar a") && window.innerWidth <= 900) {
      setSidebar(false);
    }
  }

  document.addEventListener("click", handleToggleEvent);
  document.addEventListener("touchend", function (e) {
    if (e.target.closest && (e.target.closest(".sidebar-toggle-btn") || e.target.closest(".sidebar-close-btn") || e.target.classList.contains("sidebar-overlay"))) {
      handleToggleEvent(e);
    }
  }, { passive: false });

  // Leaving the phone/tablet breakpoint never leaves the drawer stuck open.
  window.addEventListener("resize", function () {
    if (window.innerWidth > 900) setSidebar(false);
  });
})();

/**
 * DigitalBhudev Admin Panel JavaScript
 * Uses jQuery for interaction, confirmation dialogs, and mobile sidebar navigation.
 */
(function ($) {
  "use strict";

  $(function () {
    // Modal Close
    $("[data-modal-close]").on("click", function () {
      $(this).closest(".admin-modal-backdrop").removeClass("open");
    });

    $(".admin-modal-backdrop").on("click", function (e) {
      if ($(e.target).is(".admin-modal-backdrop")) {
        $(this).removeClass("open");
      }
    });

    // Status Action Modals: Reject, Suspend, Delete
    $("[data-action='reject']").on("click", function (e) {
      e.preventDefault();
      var actionUrl = $(this).data("url");
      var name = $(this).data("name") || "this Bhudev";
      var $modal = $("#rejectModal");
      $modal.find("form").attr("action", actionUrl);
      $modal.find(".modal-target-name").text(name);
      $modal.find("textarea[name='reason']").val("");
      $modal.addClass("open");
      setTimeout(function () {
        $modal.find("textarea[name='reason']").trigger("focus");
      }, 100);
    });

    $("[data-action='suspend']").on("click", function (e) {
      e.preventDefault();
      var actionUrl = $(this).data("url");
      var name = $(this).data("name") || "this Bhudev";
      var $modal = $("#suspendModal");
      $modal.find("form").attr("action", actionUrl);
      $modal.find(".modal-target-name").text(name);
      $modal.find("textarea[name='reason']").val("");
      $modal.addClass("open");
      setTimeout(function () {
        $modal.find("textarea[name='reason']").trigger("focus");
      }, 100);
    });

    $("[data-action='delete']").on("click", function (e) {
      e.preventDefault();
      var actionUrl = $(this).data("url");
      var name = $(this).data("name") || "this Bhudev";
      var $modal = $("#deleteModal");
      $modal.find("form").attr("action", actionUrl);
      $modal.find(".modal-target-name").text(name);
      $modal.addClass("open");
    });

    // File input thumbnail preview
    $('input[type="file"][name="photo"]').on("change", function () {
      var file = this.files[0];
      if (file) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $("#photoPreview").attr("src", e.target.result).show();
          $("#photoPlaceholder").hide();
        };
        reader.readAsDataURL(file);
      }
    });
  });
})(jQuery);
