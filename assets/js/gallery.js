/* gallery.js — Page Galerie Délices de la Mer
 * Tabs, filtres dynamiques, likes/dislikes/hearts, lightbox vidéo, animations
 */
(function () {
  "use strict";

  var data = window.dmGalleryData || {};
  var nonce = data.nonce || "";
  var ajaxUrl = data.ajaxUrl || "";

  // -----------------------------------------------------------------------
  // Tabs (Images / Vidéos)
  // -----------------------------------------------------------------------
  var tabs = document.querySelectorAll(".dm-gallery-tab");
  var gridImages = document.getElementById("dm-gallery-grid-images");
  var gridVideos = document.getElementById("dm-gallery-grid-videos");
  var filtersImages = document.getElementById("dm-gallery-filters-images");
  var filtersVideos = document.getElementById("dm-gallery-filters-videos");

  tabs.forEach(function (tab) {
    tab.addEventListener("click", function () {
      tabs.forEach(function (t) {
        t.classList.remove("is-active");
      });
      tab.classList.add("is-active");

      var target = tab.getAttribute("data-tab");
      if (target === "images") {
        gridImages.classList.add("is-active");
        gridImages.classList.remove("is-hidden");
        gridVideos.classList.add("is-hidden");
        gridVideos.classList.remove("is-active");
        filtersImages.classList.remove("is-hidden");
        filtersVideos.classList.add("is-hidden");
      } else {
        gridVideos.classList.add("is-active");
        gridVideos.classList.remove("is-hidden");
        gridImages.classList.add("is-hidden");
        gridImages.classList.remove("is-active");
        filtersVideos.classList.remove("is-hidden");
        filtersImages.classList.add("is-hidden");
      }
      // Reset filters
      resetFilters(target === "images" ? filtersImages : filtersVideos);
      applyFilter(target === "images" ? gridImages : gridVideos, "all");
    });
  });

  // -----------------------------------------------------------------------
  // Filtres dynamiques
  // -----------------------------------------------------------------------
  function getActiveGrid() {
    return gridImages.classList.contains("is-active") ? gridImages : gridVideos;
  }

  function getActiveFilters() {
    return gridImages.classList.contains("is-active")
      ? filtersImages
      : filtersVideos;
  }

  function resetFilters(container) {
    var filters = container.querySelectorAll(".dm-gallery-filter");
    filters.forEach(function (f) {
      f.classList.remove("is-active");
    });
    var allFilter = container.querySelector('[data-filter="all"]');
    if (allFilter) allFilter.classList.add("is-active");
  }

  function applyFilter(grid, filterValue) {
    var items = grid.querySelectorAll(
      ".dm-gallery-item, .dm-gallery-video-item",
    );
    items.forEach(function (item) {
      if (filterValue === "all") {
        item.classList.remove("is-hidden");
      } else if (filterValue === "most-liked") {
        item.classList.remove("is-hidden");
      } else if (filterValue.indexOf("cat:") === 0) {
        var filterCat = filterValue.substring(4).toLowerCase();
        var itemCat = (item.getAttribute("data-category") || "").toLowerCase();
        if (itemCat === filterCat) {
          item.classList.remove("is-hidden");
        } else {
          item.classList.add("is-hidden");
        }
      } else if (filterValue.indexOf("year:") === 0) {
        var filterYear = filterValue.substring(5);
        var itemYear = item.getAttribute("data-year") || "";
        if (itemYear === filterYear) {
          item.classList.remove("is-hidden");
        } else {
          item.classList.add("is-hidden");
        }
      } else {
        item.classList.add("is-hidden");
      }
    });

    // If most-liked, sort items by likes descending
    if (filterValue === "most-liked") {
      var parent = grid.querySelector(
        ".dm-gallery-grid, .dm-gallery-videos-grid",
      );
      if (parent) {
        var itemsArr = Array.prototype.slice.call(parent.children);
        itemsArr.sort(function (a, b) {
          var aLikes = parseInt(a.getAttribute("data-likes") || "0", 10);
          var bLikes = parseInt(b.getAttribute("data-likes") || "0", 10);
          return bLikes - aLikes;
        });
        itemsArr.forEach(function (el) {
          parent.appendChild(el);
        });
      }
    }
  }

  // Attach filter listeners
  [filtersImages, filtersVideos].forEach(function (container) {
    if (!container) return;
    var filters = container.querySelectorAll(".dm-gallery-filter");
    filters.forEach(function (filter) {
      filter.addEventListener("click", function () {
        filters.forEach(function (f) {
          f.classList.remove("is-active");
        });
        filter.classList.add("is-active");
        var grid = container === filtersImages ? gridImages : gridVideos;
        applyFilter(grid, filter.getAttribute("data-filter"));
      });
    });
  });

  // -----------------------------------------------------------------------
  // Likes / Dislikes / Hearts — AJAX
  // -----------------------------------------------------------------------
  var reactBtns = document.querySelectorAll(".dm-react-btn");
  var floatLayer = document.getElementById("dm-gallery-float-anim");

  // Restore active state from cookies on page load
  (function restoreReactionStates() {
    var cookies = document.cookie.split("; ");
    var reactCookies = {};
    cookies.forEach(function (c) {
      var parts = c.split("=");
      var key = parts[0];
      var val = parts[1] || "";
      if (key.indexOf("dm_gal_react_") === 0) {
        var idx = key.replace("dm_gal_react_", "");
        reactCookies[idx] = val;
      }
    });
    reactBtns.forEach(function (btn) {
      var index = btn.getAttribute("data-index");
      var reaction = btn.getAttribute("data-reaction");
      if (reactCookies[index] === reaction) {
        btn.classList.add("is-active");
      }
    });
  })();

  reactBtns.forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.preventDefault();
      e.stopPropagation();

      var reaction = btn.getAttribute("data-reaction");
      var index = btn.getAttribute("data-index");

      // Pop animation
      btn.classList.remove("dm-react-pop");
      void btn.offsetWidth; // trigger reflow
      btn.classList.add("dm-react-pop");

      // Floating icon animation
      if (floatLayer) {
        var rect = btn.getBoundingClientRect();
        var icon = document.createElement("span");
        icon.className = "dm-float-icon";
        icon.style.left = rect.left + rect.width / 2 - 12 + "px";
        icon.style.top = rect.top + "px";
        if (reaction === "likes") icon.textContent = "👍";
        else if (reaction === "dislikes") icon.textContent = "👎";
        else if (reaction === "hearts") icon.textContent = "❤️";
        floatLayer.appendChild(icon);
        setTimeout(function () {
          icon.remove();
        }, 1200);
      }

      // AJAX request
      var formData = new FormData();
      formData.append("action", "dm_gallery_react");
      formData.append("nonce", nonce);
      formData.append("index", index);
      formData.append("reaction", reaction);

      fetch(ajaxUrl, {
        method: "POST",
        body: formData,
        credentials: "same-origin",
      })
        .then(function (res) {
          return res.json();
        })
        .then(function (res) {
          if (res.success && res.data) {
            // Update all reaction buttons for this index
            var allBtns = document.querySelectorAll(
              '.dm-react-btn[data-index="' + index + '"]',
            );
            allBtns.forEach(function (b) {
              var r = b.getAttribute("data-reaction");
              var countEl = b.querySelector(".dm-react-count");
              var val = res.data[r] || 0;
              if (countEl) countEl.textContent = formatCount(val);

              // Update active state
              if (res.data.active === r) {
                b.classList.add("is-active");
              } else {
                b.classList.remove("is-active");
              }
            });

            // Update data-likes for sorting
            var items = document.querySelectorAll(
              '[data-index="' + index + '"]',
            );
            items.forEach(function (item) {
              var total = (res.data.likes || 0) + (res.data.hearts || 0);
              item.setAttribute("data-likes", total);
            });
          }
        })
        .catch(function (err) {
          console.error("Gallery reaction error:", err);
        });
    });
  });

  function formatCount(n) {
    n = parseInt(n, 10) || 0;
    if (n >= 1000000) return (n / 1000000).toFixed(1).replace(/\.0$/, "") + "M";
    if (n >= 1000) return (n / 1000).toFixed(1).replace(/\.0$/, "") + "k";
    return String(n);
  }

  // -----------------------------------------------------------------------
  // Lightbox vidéo
  // -----------------------------------------------------------------------
  var lightbox = document.getElementById("dm-gallery-lightbox");
  var lightboxContent = lightbox
    ? lightbox.querySelector(".dm-gallery-lightbox-content")
    : null;
  var lightboxClose = lightbox
    ? lightbox.querySelector(".dm-gallery-lightbox-close")
    : null;
  var lightboxBackdrop = lightbox
    ? lightbox.querySelector(".dm-gallery-lightbox-backdrop")
    : null;

  function openLightbox(embedUrl, isImage) {
    if (!lightbox || !lightboxContent) return;
    lightboxContent.classList.remove("is-image");
    var html = "";
    if (isImage) {
      lightboxContent.classList.add("is-image");
      html = '<img src="' + embedUrl + '" alt="" />';
    } else if (
      embedUrl.indexOf("youtube.com/embed") !== -1 ||
      embedUrl.indexOf("player.vimeo.com") !== -1
    ) {
      html =
        '<iframe src="' +
        embedUrl +
        '" allow="autoplay; fullscreen; encrypted-media" allowfullscreen></iframe>';
    } else {
      html = '<video src="' + embedUrl + '" controls autoplay></video>';
    }
    lightboxContent.innerHTML = html;
    lightbox.classList.add("is-open");
    document.body.style.overflow = "hidden";
  }

  function closeLightbox() {
    if (!lightbox) return;
    lightbox.classList.remove("is-open");
    if (lightboxContent) {
      lightboxContent.innerHTML = "";
      lightboxContent.classList.remove("is-image");
    }
    document.body.style.overflow = "";
  }

  if (lightboxClose) lightboxClose.addEventListener("click", closeLightbox);
  if (lightboxBackdrop)
    lightboxBackdrop.addEventListener("click", closeLightbox);
  document.addEventListener("keydown", function (e) {
    if (
      e.key === "Escape" &&
      lightbox &&
      lightbox.classList.contains("is-open")
    ) {
      closeLightbox();
    }
  });

  var videoExpandBtns = document.querySelectorAll(".dm-gallery-video-expand");
  videoExpandBtns.forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.stopPropagation();
      var thumb = btn.closest(".dm-gallery-video-thumb");
      if (!thumb) return;
      var embed = thumb.getAttribute("data-embed");
      if (embed) openLightbox(embed, false);
    });
  });

  // Image expand buttons — open image in lightbox
  var imageExpandBtns = document.querySelectorAll(".dm-gallery-item-expand");
  imageExpandBtns.forEach(function (btn) {
    btn.addEventListener("click", function (e) {
      e.stopPropagation();
      var media = btn.closest(".dm-gallery-item-media");
      if (!media) return;
      var imgUrl = media.getAttribute("data-image-url");
      if (imgUrl) openLightbox(imgUrl, true);
    });
  });

  // -----------------------------------------------------------------------
  // Reveal animation
  // -----------------------------------------------------------------------
  var revealEls = document.querySelectorAll(".reveal-el");
  if ("IntersectionObserver" in window) {
    var obs = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (entry) {
          if (entry.isIntersecting) {
            entry.target.classList.add("is-visible");
            obs.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.1, rootMargin: "0px 0px -50px 0px" },
    );
    revealEls.forEach(function (el) {
      obs.observe(el);
    });
  } else {
    revealEls.forEach(function (el) {
      el.classList.add("is-visible");
    });
  }
})();
