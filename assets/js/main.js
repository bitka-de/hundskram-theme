class CartPanel {
  constructor() {
    this.cartBtn = document.getElementById("cart-button");
    this.cartPanel = document.getElementById("cart-panel");
    this.cartOverlay = document.getElementById("cart-overlay");
    this.closeBtn = document.getElementById("close-cart-panel");
    this.init();
  }
  init() {
    if (this.cartBtn && this.cartPanel && this.cartOverlay && this.closeBtn) {
      this.cartBtn.addEventListener("click", (e) => this.openCartPanel(e));
      this.closeBtn.addEventListener("click", () => this.closeCartPanel());
      this.cartOverlay.addEventListener("click", () => this.closeCartPanel());
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") this.closeCartPanel();
      });
    }
  }
  openCartPanel(e) {
    e.preventDefault();
    this.cartPanel.classList.remove("translate-x-full");
    this.cartOverlay.classList.remove("hidden");
  }
  closeCartPanel() {
    this.cartPanel.classList.add("translate-x-full");
    this.cartOverlay.classList.add("hidden");
  }
}

class UserDropdown {
  constructor() {
    this.toggle = document.querySelector(".user-dropdown-toggle");
    this.menu = document.querySelector(".user-dropdown-menu");
    this.init();
  }
  init() {
    if (this.toggle && this.menu) {
      this.toggle.addEventListener("click", (e) => {
        e.stopPropagation();
        const expanded = this.toggle.getAttribute("aria-expanded") === "true";
        this.toggle.setAttribute("aria-expanded", !expanded);
        this.menu.classList.toggle("hidden");
      });
      document.addEventListener("click", () => {
        if (!this.menu.classList.contains("hidden")) {
          this.menu.classList.add("hidden");
          this.toggle.setAttribute("aria-expanded", "false");
        }
      });
      this.menu.addEventListener("click", (e) => {
        e.stopPropagation();
      });
    }
  }
}

class SearchPopover {
  constructor() {
    this.searchToggle = document.getElementById("search-toggle");
    this.searchPopover = document.getElementById("search-popover");
    this.searchOverlay = document.getElementById("search-overlay");
    this.searchInput = document.getElementById("live-search-input");
    this.searchResults = document.getElementById("live-search-results");
    this.searchForm = document.getElementById("live-search-form");
    this.searchTypeToggle = document.getElementById("search-type-toggle");
    this.searchTypeIndicator = document.getElementById("search-type-indicator");
    this.searchTypeProduct = document.getElementById("search-type-product");
    this.searchTypeContent = document.getElementById("search-type-content");
    this.closeSearchPopover = document.getElementById("close-search-popover");
    this.searchTimeout = null;
    this.searchType = "product";
    this.init();
  }
  init() {
    if (this.searchTypeToggle) {
      this.searchTypeToggle.addEventListener("click", (e) => {
        e.preventDefault();
        this.setSearchType(
          this.searchType === "product" ? "content" : "product"
        );
      });
      this.setSearchType("product");
    }
    if (this.searchToggle && this.searchPopover && this.searchOverlay) {
      this.searchToggle.addEventListener("click", (e) => {
        e.stopPropagation();
        this.openPopover();
      });
      this.searchOverlay.addEventListener("click", () => this.closePopover());
      document.addEventListener("keydown", (e) => {
        if (e.key === "Escape") this.closePopover();
      });
    }
    if (this.closeSearchPopover) {
      this.closeSearchPopover.addEventListener("click", () =>
        this.closePopover()
      );
    }
    if (this.searchInput && this.searchResults) {
      this.searchInput.addEventListener("input", () => this.onInput());
      this.searchForm.addEventListener("submit", (e) => {
        e.preventDefault();
        const first = this.searchResults.querySelector("a");
        if (first) first.click();
      });
    }
  }
  openPopover() {
    this.searchPopover.classList.remove("hidden");
    this.searchOverlay.classList.remove("hidden");
    setTimeout(() => {
      if (this.searchInput) this.searchInput.focus();
    }, 100);
  }
  closePopover() {
    this.searchPopover.classList.add("hidden");
    this.searchOverlay.classList.add("hidden");
    if (this.searchResults) this.searchResults.innerHTML = "";
    if (this.searchInput) this.searchInput.value = "";
  }
  setSearchType(type) {
    this.searchType = type;
    if (this.searchTypeIndicator) {
      this.searchTypeIndicator.style.transform =
        type === "product" ? "translateX(0)" : "translateX(100%)";
    }
    if (this.searchTypeProduct && this.searchTypeContent) {
      if (type === "product") {
        this.searchTypeProduct.classList.add("text-white");
        this.searchTypeProduct.classList.remove("text-blue-600");
        this.searchTypeContent.classList.remove("text-white");
        this.searchTypeContent.classList.add("text-blue-600");
      } else {
        this.searchTypeProduct.classList.remove("text-white");
        this.searchTypeProduct.classList.add("text-blue-600");
        this.searchTypeContent.classList.add("text-white");
        this.searchTypeContent.classList.remove("text-blue-600");
      }
    }
    if (this.searchInput) {
      this.searchInput.placeholder =
        type === "product" ? "Produkte durchsuchen …" : "Inhalte durchsuchen …";
    }
    if (this.searchInput && this.searchInput.value.length >= 3) {
      this.onInput();
    }
  }
  onInput() {
    clearTimeout(this.searchTimeout);
    const query = this.searchInput.value.trim();
    if (query.length < 3) {
      this.searchResults.innerHTML =
        '<div class="p-6 text-gray-400 text-base text-center">Bitte mindestens 3 Buchstaben eingeben …</div>';
      return;
    }
    // Loading Spinner
    this.searchResults.innerHTML =
      '<div class="flex flex-col items-center justify-center py-10"><span class="loader mb-3"></span><span class="text-blue-600 font-semibold">Suche läuft …</span></div>';
    this.searchTimeout = setTimeout(() => this.fetchResults(query), 300);
  }
  fetchResults(query) {
    fetch(
      `/wp-admin/admin-ajax.php?action=hundskram_live_search&s=${encodeURIComponent(
        query
      )}&type=${encodeURIComponent(this.searchType)}`
    )
      .then((res) => res.json())
      .then((data) => {
        if (data && data.length > 0) {
          if (this.searchType === "product") {
            const products = data.filter((item) => item.type === "product");
            if (products.length === 0) {
              this.searchResults.innerHTML =
                '<div class="p-6 text-gray-400 text-base text-center">Keine Produkte gefunden.</div>';
              return;
            }
            this.searchResults.innerHTML = products
              .map(
                (item) =>
                  `<a href="${
                    item.url
                  }" class="group flex items-center gap-4 px-4 py-3 border-b border-neutral-100 hover:bg-blue-50 transition rounded-lg">
                                <img src="${
                                  item.thumbnail ||
                                  "/wp-content/plugins/woocommerce/assets/images/placeholder.png"
                                }" alt="${
                    item.title
                  }" class="w-16 h-16 object-contain rounded bg-neutral-100 border border-neutral-200 flex-shrink-0" loading="lazy">
                                <div class="flex-1 min-w-0">
                                    <div class="font-semibold text-base text-blue-900 group-hover:text-blue-700 truncate">${
                                      item.title
                                    }</div>
                                    <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded bg-blue-100 text-blue-700 font-semibold">Produkt</span>
                                </div>
                            </a>`
              )
              .join("");
          } else {
            const contents = data.filter(
              (item) => item.type === "page" || item.type === "post"
            );
            if (contents.length === 0) {
              this.searchResults.innerHTML =
                '<div class="p-6 text-gray-400 text-base text-center">Keine Inhalte gefunden.</div>';
              return;
            }
            this.searchResults.innerHTML = contents
              .map(
                (item) =>
                  `<a href="${
                    item.url
                  }" class="group block px-4 py-3 border-b border-neutral-100 hover:bg-blue-50 transition rounded-lg">
                                <div class="font-semibold text-base text-blue-900 group-hover:text-blue-700 truncate">${
                                  item.title
                                }</div>
                                <span class="inline-block mt-1 px-2 py-0.5 text-xs rounded ${
                                  item.type === "page"
                                    ? "bg-neutral-200 text-neutral-700"
                                    : "bg-blue-100 text-blue-700"
                                } font-semibold">${
                    item.type === "page" ? "Seite" : "Beitrag"
                  }</span>
                            </a>`
              )
              .join("");
          }
        } else {
          this.searchResults.innerHTML =
            '<div class="p-6 text-gray-400 text-base text-center">Keine Ergebnisse gefunden.</div>';
        }
      })
      .catch(() => {
        this.searchResults.innerHTML =
          '<div class="p-6 text-red-400 text-base text-center">Fehler bei der Suche.</div>';
      });
  }
}
