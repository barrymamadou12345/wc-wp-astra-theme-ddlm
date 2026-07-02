/* gallery-diag.js — Diagnostic gallery : log le HTML rendu et les styles après 5s */
(function () {
  "use strict";

  console.log("=== DM GALLERY DIAG START ===");

  setTimeout(function () {
    var items = document.querySelectorAll(".dm-gallery-item");
    console.log("Gallery items in DOM:", items.length);

    items.forEach(function (item, i) {
      if (i >= 3) return;
      var expand = item.querySelector(".dm-gallery-item-expand");
      var media = item.querySelector(".dm-gallery-item-media");
      var img = item.querySelector("img");
      var styles = window.getComputedStyle(item);
      var expandStyles = expand ? window.getComputedStyle(expand) : null;

      console.log("--- Item " + i + " ---");
      console.log("  class:", item.className);
      console.log("  data-index:", item.getAttribute("data-index"));
      console.log("  expand_btn HTML:", expand ? expand.outerHTML : "NOT FOUND");
      console.log("  expand_btn textContent:", expand ? expand.textContent : "N/A");
      console.log("  expand computed color:", expandStyles ? expandStyles.color : "N/A");
      console.log("  expand computed opacity:", expandStyles ? expandStyles.opacity : "N/A");
      console.log("  expand computed display:", expandStyles ? expandStyles.display : "N/A");
      console.log("  expand computed fontSize:", expandStyles ? expandStyles.fontSize : "N/A");
      console.log("  media data-url:", media ? media.getAttribute("data-image-url") : "N/A");
      console.log("  img src:", img ? img.src.substring(0, 100) : "N/A");
      console.log("  item transform:", styles.transform);
      console.log("  item transition:", styles.transition);
      console.log("  item zIndex:", styles.zIndex);
    });

    var lightbox = document.getElementById("dm-gallery-lightbox");
    if (lightbox) {
      var closeBtn = lightbox.querySelector(".dm-gallery-lightbox-close");
      var closeStyles = closeBtn ? window.getComputedStyle(closeBtn) : null;
      console.log("--- Lightbox ---");
      console.log("  close_btn HTML:", closeBtn ? closeBtn.outerHTML : "NOT FOUND");
      console.log("  close_btn textContent:", closeBtn ? closeBtn.textContent : "N/A");
      console.log("  close computed color:", closeStyles ? closeStyles.color : "N/A");
      console.log("  close computed fontSize:", closeStyles ? closeStyles.fontSize : "N/A");
      console.log("  close computed position:", closeStyles ? closeStyles.position : "N/A");
      console.log("  close computed display:", closeStyles ? closeStyles.display : "N/A");
    } else {
      console.log("Lightbox: NOT FOUND");
    }

    // Check CSS rules
    console.log("--- CSS Rules ---");
    var sheets = document.styleSheets;
    for (var s = 0; s < sheets.length; s++) {
      try {
        var rules = sheets[s].cssRules;
        for (var r = 0; r < rules.length; r++) {
          var sel = rules[r].selectorText;
          if (!sel) continue;
          if (sel.indexOf("dm-gallery-item:hover") !== -1 ||
              sel.indexOf("dm-gallery-item-expand") !== -1 ||
              sel.indexOf("lightbox-close") !== -1 ||
              sel.indexOf("dm-gallery-video-expand") !== -1) {
            console.log("  " + sel + " => " + rules[r].cssText.substring(0, 200));
          }
        }
      } catch (e) {
        // CORS
      }
    }

    // Check loaded CSS/JS files
    console.log("--- Loaded Assets ---");
    var links = document.querySelectorAll('link[rel="stylesheet"]');
    links.forEach(function (link) {
      if (link.href.indexOf("gallery") !== -1) {
        console.log("  CSS:", link.href);
      }
    });
    var scripts = document.querySelectorAll("script[src]");
    scripts.forEach(function (script) {
      if (script.src.indexOf("gallery") !== -1) {
        console.log("  JS:", script.src);
      }
    });

    console.log("=== DM GALLERY DIAG END ===");
  }, 5000);
})();
