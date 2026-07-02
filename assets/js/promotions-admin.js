(function ($) {
  "use strict";

  var promoIndex = 0;

  function getPromoIndex() {
    var maxIndex = -1;
    $("#dm-promo-repeater .dm-promo-row").each(function () {
      var idx = parseInt($(this).data("index"), 10);
      if (!isNaN(idx) && idx > maxIndex) {
        maxIndex = idx;
      }
    });
    return maxIndex + 1;
  }

  function initProductSearch($row) {
    var $input = $row.find(".dm-promo-search-input");
    var $dropdown = $row.find(".dm-promo-search-dropdown");
    var $selectedList = $row.find(".dm-promo-selected-list");
    var promoIdx = $row.data("index");
    var searchTimer = null;

    // Get currently selected product IDs
    function getSelectedIds() {
      var ids = [];
      $selectedList.find(".dm-promo-chip").each(function () {
        ids.push(parseInt($(this).data("pid"), 10));
      });
      return ids;
    }

    // Add product to selected
    function addProduct(id, name) {
      var selected = getSelectedIds();
      if (selected.indexOf(id) !== -1) return;

      var $chip = $(
        '<span class="dm-promo-chip" data-pid="' +
          id +
          '">' +
          name +
          ' <button type="button" class="dm-promo-chip-remove">&times;</button></span>',
      );
      var $hidden = $(
        '<input type="hidden" name="dm_promotions[' +
          promoIdx +
          '][products][]" value="' +
          id +
          '" class="dm-promo-product-hidden" data-pid="' +
          id +
          '">',
      );
      $selectedList.append($chip).append($hidden);
    }

    // Remove product from selected
    function removeProduct(id) {
      $selectedList.find('.dm-promo-chip[data-pid="' + id + '"]').remove();
      $selectedList
        .find('.dm-promo-product-hidden[data-pid="' + id + '"]')
        .remove();
    }

    // Render dropdown results
    function renderResults(results) {
      if (!results || results.length === 0) {
        $dropdown.html(
          '<div class="dm-promo-search-status">Aucun produit trouvé</div>',
        );
        return;
      }
      var selected = getSelectedIds();
      var html = "";
      results.forEach(function (item) {
        var isSel = selected.indexOf(item.id) !== -1;
        html +=
          '<div class="dm-promo-search-item' +
          (isSel ? " is-selected" : "") +
          '" data-pid="' +
          item.id +
          '" data-name="' +
          item.text.replace(/"/g, "&quot;") +
          '">' +
          item.text +
          "</div>";
      });
      $dropdown.html(html);
    }

    // Search on input (debounced)
    $input.on("input", function () {
      var term = $(this).val().trim();
      if (term.length < 1) {
        $dropdown.hide();
        return;
      }
      $dropdown.show();
      $dropdown.html('<div class="dm-promo-search-status">Recherche…</div>');

      clearTimeout(searchTimer);
      searchTimer = setTimeout(function () {
        $.ajax({
          url: dmPromoAdmin.ajaxUrl,
          type: "GET",
          dataType: "json",
          data: {
            action: "dm_search_products",
            q: term,
          },
          success: function (data) {
            renderResults(data.results || []);
          },
          error: function () {
            $dropdown.html(
              '<div class="dm-promo-search-status">Erreur de recherche</div>',
            );
          },
        });
      }, 300);
    });

    // Click on result item
    $dropdown.on("click", ".dm-promo-search-item", function () {
      var id = parseInt($(this).data("pid"), 10);
      var name = $(this).data("name");
      var selected = getSelectedIds();
      if (selected.indexOf(id) !== -1) {
        removeProduct(id);
      } else {
        addProduct(id, name);
      }
      // Refresh the results to show updated selection state
      $input.trigger("input");
    });

    // Remove chip on click
    $selectedList.on("click", ".dm-promo-chip-remove", function (e) {
      e.preventDefault();
      var $chip = $(this).closest(".dm-promo-chip");
      removeProduct(parseInt($chip.data("pid"), 10));
    });

    // Hide dropdown when clicking outside
    $(document).on("click", function (e) {
      if (!$(e.target).closest(".dm-promo-search-wrapper").length) {
        $dropdown.hide();
      }
    });
  }

  function initMediaUploader($row) {
    $row.on("click", ".dm-promo-img-upload", function (e) {
      e.preventDefault();
      var $button = $(this);
      var $input = $button.siblings(".dm-promo-img-input");
      var $preview = $button.siblings(".dm-promo-img-preview");

      var frame = wp.media({
        title: "Choisir une image de promotion",
        button: { text: "Utiliser cette image" },
        multiple: false,
      });

      frame.on("select", function () {
        var attachment = frame.state().get("selection").first().toJSON();
        $input.val(attachment.url);
        $preview.attr("src", attachment.url).show();
      });

      frame.open();
    });

    // Video uploader
    $row.on("click", ".dm-promo-video-upload", function (e) {
      e.preventDefault();
      var $button = $(this);
      var $input = $button.siblings(".dm-promo-video-input");

      var frame = wp.media({
        title: "Choisir une vidéo de promotion",
        button: { text: "Utiliser cette vidéo" },
        multiple: false,
        library: { type: "video" },
      });

      frame.on("select", function () {
        var attachment = frame.state().get("selection").first().toJSON();
        $input.val(attachment.url);
      });

      frame.open();
    });
  }

  function initContentBlock($block) {
    $block.on("change", ".dm-content-type", function () {
      var type = $(this).val();
      $block.find(".dm-content-text-field").toggle(type !== "list");
      $block.find(".dm-content-list-field").toggle(type === "list");
    });

    $block.on("click", ".dm-content-remove", function () {
      $block.remove();
    });
  }

  function updateRowIndexes() {
    $("#dm-promo-repeater .dm-promo-row").each(function (i) {
      $(this).data("index", i);
      $(this)
        .find("input, select, textarea")
        .each(function () {
          var name = $(this).attr("name");
          if (name) {
            name = name.replace(
              /dm_promotions\[\d+\]/,
              "dm_promotions[" + i + "]",
            );
            $(this).attr("name", name);
          }
        });
    });
  }

  function addPromoRow() {
    var index = getPromoIndex();
    var template = $("#dm-promo-template").html();
    // Replace __INDEX__ with actual index
    template = template.replace(/__INDEX__/g, index);
    var $newRow = $(template);
    $newRow.data("index", index);

    // Init product search
    initProductSearch($newRow);

    // Init media uploader
    initMediaUploader($newRow);

    // Init content blocks
    $newRow.find(".dm-promo-content-block").each(function () {
      initContentBlock($(this));
    });

    // Init content add button
    initContentAdd($newRow);

    // Init remove button
    $newRow.on("click", ".dm-promo-remove", function () {
      if (confirm("Supprimer cette promotion ?")) {
        $newRow.remove();
        updateRowIndexes();
      }
    });

    // Update title display on input
    $newRow.on("input", ".dm-promo-title-input", function () {
      $newRow
        .find(".dm-promo-title-display")
        .text($(this).val() || "Nouvelle promotion");
    });

    $("#dm-promo-repeater").append($newRow);
  }

  function getContentBlockIndex($block) {
    var $list = $block.closest(".dm-promo-content-list");
    var idx = 0;
    $list.find(".dm-promo-content-block").each(function (i) {
      if ($(this)[0] === $block[0]) {
        idx = i;
      }
    });
    return idx;
  }

  function updateContentIndexes($row) {
    var promoIdx = $row.data("index");
    $row.find(".dm-promo-content-block").each(function (ci) {
      $(this)
        .find("input, select, textarea")
        .each(function () {
          var name = $(this).attr("name");
          if (name) {
            name = name.replace(/content\[\d+\]/, "content[" + ci + "]");
            $(this).attr("name", name);
          }
        });
    });
  }

  function initContentAdd($row) {
    $row.on("click", ".dm-promo-content-add", function () {
      var promoIdx = $row.data("index");
      var html =
        '<div class="dm-promo-content-block">' +
        '<div class="dm-promo-content-block-header">' +
        '<select name="dm_promotions[' +
        promoIdx +
        '][content][0][type]" class="dm-content-type">' +
        '<option value="title">Titre</option>' +
        '<option value="text">Texte</option>' +
        '<option value="list">Liste à puces</option>' +
        "</select>" +
        '<button type="button" class="button dm-content-remove" style="color:#a00;border-color:#a00;">Retirer</button>' +
        "</div>" +
        '<p><input type="text" name="dm_promotions[' +
        promoIdx +
        '][content][0][title]" value="" class="regular-text" placeholder="Titre du bloc" /></p>' +
        '<p class="dm-content-text-field"><textarea name="dm_promotions[' +
        promoIdx +
        '][content][0][text]" rows="3" class="large-text" placeholder="Texte du bloc"></textarea></p>' +
        '<div class="dm-content-list-field" style="display:none;"><p class="description">Ajoutez un élément par ligne :</p>' +
        '<textarea name="dm_promotions[' +
        promoIdx +
        '][content][0][list_items]" rows="4" class="large-text" placeholder="Un élément par ligne"></textarea></div>' +
        "</div>";

      var $block = $(html);
      $block.find(".dm-content-list-field").hide();
      initContentBlock($block);
      $row.find(".dm-promo-content-list").append($block);
      updateContentIndexes($row);
    });
  }

  $(document).ready(function () {
    // Init existing rows
    $("#dm-promo-repeater .dm-promo-row").each(function () {
      var $row = $(this);
      initProductSearch($row);
      initMediaUploader($row);
      initContentAdd($row);

      $row.on("click", ".dm-promo-remove", function () {
        if (confirm("Supprimer cette promotion ?")) {
          $row.remove();
          updateRowIndexes();
        }
      });

      $row.on("input", ".dm-promo-title-input", function () {
        $row
          .find(".dm-promo-title-display")
          .text($(this).val() || "Nouvelle promotion");
      });

      $row.find(".dm-promo-content-block").each(function () {
        initContentBlock($(this));
      });
    });

    // Add new promo
    $("#dm-promo-add").on("click", function () {
      addPromoRow();
    });

    initCommonSections();
  });

  // ---- Sections communes (how it works / benefits / FAQ) ----
  function initCommonSections() {
    $(".dm-common-section").each(function () {
      var $section = $(this);
      var option = $section.data("option");
      var type = $section.data("type");

      $section.on("click", ".dm-common-add", function () {
        var tmplId =
          type === "faq"
            ? "#dm-common-faq-template"
            : "#dm-common-card-template";
        var tmpl = $(tmplId).html();
        if (!tmpl) return;
        var index = Date.now() + Math.floor(Math.random() * 1000);
        var html = tmpl
          .split("__OPTION__")
          .join(option)
          .split("__INDEX__")
          .join(index);
        $section.find(".dm-common-list").append($(html));
      });

      $section.on("click", ".dm-common-remove", function () {
        $(this).closest(".dm-common-row").remove();
      });
    });
  }
})(jQuery);
