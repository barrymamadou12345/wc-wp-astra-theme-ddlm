(function () {
  "use strict";

  var STORAGE_KEY = "dm_promo_state";
  var PROMO_ID_KEY = "dm_promo_id";

  function getPromoData() {
    var card = document.querySelector(".dm-promo-card");
    if (!card) return null;
    return {
      el: card,
      id: card.getAttribute("data-promo-id") || "",
    };
  }

  function getStoredState() {
    try {
      return localStorage.getItem(STORAGE_KEY) || "expanded";
    } catch (e) {
      return "expanded";
    }
  }

  function setStoredState(state) {
    try {
      localStorage.setItem(STORAGE_KEY, state);
    } catch (e) {}
  }

  function getStoredPromoId() {
    try {
      return localStorage.getItem(PROMO_ID_KEY) || "";
    } catch (e) {
      return "";
    }
  }

  function setStoredPromoId(id) {
    try {
      localStorage.setItem(PROMO_ID_KEY, id);
    } catch (e) {}
  }

  function applyState(card, state) {
    card.classList.remove("is-hidden", "is-minimized");
    var minBtn = card.querySelector(".dm-promo-card-minimize");
    if (state === "hidden") {
      card.classList.add("is-hidden");
    } else if (state === "minimized") {
      card.classList.add("is-minimized");
      if (minBtn) {
        minBtn.textContent = "Agrandir";
      }
    } else {
      if (minBtn) {
        minBtn.textContent = "Réduire";
      }
    }
  }

  function initPromoCard() {
    var data = getPromoData();
    if (!data || !data.el) return;

    var card = data.el;
    var promoId = data.id;

    // Check if promo ID changed → reset state
    var storedId = getStoredPromoId();
    if (storedId !== promoId) {
      setStoredPromoId(promoId);
      setStoredState("expanded");
      applyState(card, "expanded");
    } else {
      applyState(card, getStoredState());
    }

    // Minimize button (toggle expand/minimize)
    var minimizeBtn = card.querySelector(".dm-promo-card-minimize");
    if (minimizeBtn) {
      minimizeBtn.addEventListener("click", function () {
        if (card.classList.contains("is-minimized")) {
          applyState(card, "expanded");
          setStoredState("expanded");
        } else {
          applyState(card, "minimized");
          setStoredState("minimized");
        }
      });
    }

    // Close button
    var closeBtn = card.querySelector(".dm-promo-card-close");
    if (closeBtn) {
      closeBtn.addEventListener("click", function () {
        card.classList.add("is-hidden");
        setStoredState("hidden");
      });
    }

    // Bar click (expand when minimized)
    var bar = card.querySelector(".dm-promo-card-bar");
    if (bar) {
      bar.addEventListener("click", function (e) {
        if (e.target.closest(".dm-promo-card-bar-btn")) return;
        if (card.classList.contains("is-minimized")) {
          applyState(card, "expanded");
          setStoredState("expanded");
        }
      });
    }
  }

  function initPetals() {
    var container = document.querySelector(".dm-promo-petals");
    if (!container) return;

    var colors = [
      "#ff6b00",
      "#ff8c42",
      "#ffaa66",
      "#ffd700",
      "#ffffff",
      "#ff4500",
    ];
    var shapes = ["50%", "20%", "50% 10% 50% 10%"];
    var petalCount = 12;

    for (var i = 0; i < petalCount; i++) {
      var petal = document.createElement("span");
      petal.className = "dm-promo-petal";
      petal.style.left = Math.random() * 100 + "%";
      petal.style.top = 70 + Math.random() * 30 + "%";
      petal.style.background =
        colors[Math.floor(Math.random() * colors.length)];
      petal.style.animationDelay = Math.random() * 4 + "s";
      petal.style.animationDuration = 3 + Math.random() * 3 + "s";
      var size = 5 + Math.random() * 8;
      petal.style.width = size + "px";
      petal.style.height = size + "px";
      petal.style.borderRadius =
        shapes[Math.floor(Math.random() * shapes.length)];
      petal.style.boxShadow = "0 0 6px " + petal.style.background;
      container.appendChild(petal);
    }
  }

  function initCountdown() {
    var el = document.querySelector(".dm-promo-countdown");
    if (!el) return;

    var endDate = el.getAttribute("data-enddate");
    if (!endDate) return;

    var endTs = new Date(endDate).getTime();
    if (isNaN(endTs)) return;

    var dEl = el.querySelector(".dm-cd-days");
    var hEl = el.querySelector(".dm-cd-hours");
    var mEl = el.querySelector(".dm-cd-mins");
    var sEl = el.querySelector(".dm-cd-secs");

    function pad(n) {
      return n < 10 ? "0" + n : "" + n;
    }

    function tick() {
      var now = Date.now();
      var diff = endTs - now;

      if (diff <= 0) {
        if (dEl) dEl.textContent = "0";
        if (hEl) hEl.textContent = "00";
        if (mEl) mEl.textContent = "00";
        if (sEl) sEl.textContent = "00";
        clearInterval(interval);
        return;
      }

      var days = Math.floor(diff / 86400000);
      var hours = Math.floor((diff % 86400000) / 3600000);
      var mins = Math.floor((diff % 3600000) / 60000);
      var secs = Math.floor((diff % 60000) / 1000);

      if (dEl) dEl.textContent = days;
      if (hEl) hEl.textContent = pad(hours);
      if (mEl) mEl.textContent = pad(mins);
      if (sEl) sEl.textContent = pad(secs);
    }

    tick();
    var interval = setInterval(tick, 1000);
  }

  function initProductFilters() {
    var filters = document.querySelectorAll(".dm-promo-filter");
    if (!filters.length) return;
    var cards = document.querySelectorAll(".dm-promo-product-card");

    filters.forEach(function (btn) {
      btn.addEventListener("click", function () {
        var filter = btn.getAttribute("data-filter");

        filters.forEach(function (b) {
          b.classList.remove("is-active");
        });
        btn.classList.add("is-active");

        cards.forEach(function (card) {
          var cats = (card.getAttribute("data-categories") || "").split(" ");
          if (filter === "all" || cats.indexOf(filter) !== -1) {
            card.classList.remove("is-filtered-out");
          } else {
            card.classList.add("is-filtered-out");
          }
        });
      });
    });
  }

  function initReveal() {
    var els = document.querySelectorAll(".reveal-el");
    if (!els.length) return;
    if (!("IntersectionObserver" in window)) {
      els.forEach(function (el) {
        el.classList.add("is-visible");
      });
      return;
    }
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
    els.forEach(function (el) {
      obs.observe(el);
    });
  }

  function init() {
    initPromoCard();
    initPetals();
    initCountdown();
    initProductFilters();
    initReveal();
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", init);
  } else {
    init();
  }
})();
