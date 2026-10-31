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
