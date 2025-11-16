<!-- app/views/components/OrderTypeButtonGroup.php -->
<div class="form-group">
  <label>Order Type</label>
  <div id="order-type-buttons">
    <button type="button" data-type="delivery" class="order-btn active">Delivery</button>
    <button type="button" data-type="pickup" class="order-btn">Pickup</button>
  </div>
</div>

<script>
class OrderTypeButtonGroup {
  constructor({ onChange } = {}) {
    this.buttons = document.querySelectorAll("#order-type-buttons .order-btn");
    this.orderType = "delivery"; // default
    this.onChange = onChange || (() => {});

    this.bindEvents();
    this.toggleFields(); // inicial
    this.updateCartTotal(); // inicial
  }

  bindEvents() {
    this.buttons.forEach(btn => {
      btn.addEventListener("click", () => {
        this.buttons.forEach(b => b.classList.remove("active"));
        btn.classList.add("active");
        this.orderType = btn.dataset.type;
        this.toggleFields();
        this.updateCartTotal();
        this.onChange(this.orderType);
      });
    });
  }

  toggleFields() {
    // Inputs de dirección
    const addressFields = [
      document.getElementById("useraddress"),
      document.getElementById("zipcode"),
      document.getElementById("city"),
      document.getElementById("state")
    ];
    addressFields.forEach(f => {
      if (!f) return;
      const group = f.closest(".form-group");
      if (group) group.style.display = this.orderType === "delivery" ? "flex" : "none";
    });

    // ZIP code snippet completo
    const zipSnippet = document.getElementById("zip-code-snippet");
    if (zipSnippet) zipSnippet.style.display = this.orderType === "delivery" ? "block" : "none";

    // // --- ShippingCalc siempre visible ---
    // const shippingEl = document.getElementById("shipping-calc");
    // if (shippingEl) shippingEl.style.display = "block";
  }

  updateCartTotal() {
    // ShippingCalc
    const shippingPriceEl = document.getElementById("shipping-price");
    const shippingInputHidden = document.getElementById("shipping_price_input");

    if (this.orderType === "pickup") {
      // --- ShippingCalc siempre visible ---
      const shippingEl = document.getElementById("shipping-calc");
      if (shippingEl) shippingEl.style.display = "block";
      
      if (shippingPriceEl) shippingPriceEl.textContent = "0.00";
      if (shippingInputHidden) shippingInputHidden.value = "0.00";
    } else {
      // Delivery: puedes tomar el valor real desde ZipCodeSnippet

      document.getElementById("shipping-calc").style.display = "none";
    //   const price = parseFloat(shippingInputHidden?.value) || 10.00;
    //   if (shippingPriceEl) shippingPriceEl.textContent = price.toFixed(2);
    }

    // Actualizar total global
    if (typeof recalcGlobalTotal === "function") {
      recalcGlobalTotal();
    }

    // CartTotalComponent (si lo usas)
    if (window.cartTotalComponent) {
      const baseTotal =
        parseFloat(document.getElementById("cart-total")?.textContent) || 0;
      const shipping = parseFloat(shippingPriceEl?.textContent) || 0;
      window.cartTotalComponent.update(baseTotal + shipping);
    }
  }

  getType() {
    return this.orderType;
  }
}

// Instancia global
window.orderTypeButtonGroup = new OrderTypeButtonGroup();

</script>

<style>
#order-type-buttons {
  display: flex;
  gap: 8px;
}

.order-btn {
  padding: 6px 12px;
  border: 1px solid #aaa;
  background: #f5f5f5;
  cursor: pointer;
  border-radius: 4px;
  transition: 0.2s;
}

.order-btn.active {
  background: #4caf50;
  color: white;
  border-color: #4caf50;
}

.order-btn:hover {
  background: #e0e0e0;
}
</style>
