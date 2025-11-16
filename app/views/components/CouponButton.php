<!-- COUPONBUTTON -->
<button id="openCouponBtn" class="openCouponBtn" aria-label="">...</button>

<!-- Modal -->
<div id="couponModal" role="dialog" aria-modal="true" aria-labelledby="couponTitle">
  <div class="modal-content">
    <h2 id="couponTitle"></h2>
    <p id="couponDescription"></p>

    <input type="text" id="couponInput" placeholder="" aria-label="" />
    <br />

    <button id="applyCouponBtn"></button>
    <button id="closeCouponBtn"></button>

    <p id="errorMsg" style="color: red; font-size: 0.9rem; margin-top: 0.5rem; display:none;"></p>
  </div>
</div>

<script>
  const uiText = {
    EN: {
      id: "language_EN",
      concepts: {
        coupon_button: "Got a coupon?",
        coupon_modal_title: "Enter Your Coupon Code",
        coupon_modal_description: "Please enter your code to apply the discount.",
               coupon_placeholder: "Example: FUTURE20",
        coupon_apply_btn: "Apply Coupon",
        coupon_close_btn: "Close",
        coupon_error: "Please enter a valid code."
      }
    },

    ES: {
      id: "language_ES",
      concepts: {
        coupon_button: "¿Tienes un cupón?",
        coupon_modal_title: "Introduce tu Código de Cupón",
        coupon_modal_description: "Por favor, ingresa tu código para aplicar el descuento.",
        coupon_placeholder: "Ejemplo: FUTURO20",
        coupon_apply_btn: "Aplicar Cupón",
        coupon_close_btn: "Cerrar",
        coupon_error: "Por favor, introduce un código válido."
      }
    }
  };

  function applyLanguage(lang = "EN") {
    const t = uiText[lang].concepts;

    document.getElementById("openCouponBtn").textContent = t.coupon_button;
    document.getElementById("openCouponBtn").ariaLabel = t.coupon_button;

    document.getElementById("couponTitle").textContent = t.coupon_modal_title;
    document.getElementById("couponDescription").textContent = t.coupon_modal_description;

    document.getElementById("couponInput").placeholder = t.coupon_placeholder;
    document.getElementById("couponInput").ariaLabel = t.coupon_placeholder;

    document.getElementById("applyCouponBtn").textContent = t.coupon_apply_btn;
    document.getElementById("closeCouponBtn").textContent = t.coupon_close_btn;

    document.getElementById("errorMsg").textContent = t.coupon_error;
  }

  // Default language: English
  applyLanguage("EN");
</script>

<!-- Div oculto para guardar el código -->
<div id="coupon_code"></div>

<script>
const modal = document.getElementById('couponModal');
const openBtn = document.getElementById('openCouponBtn');
const closeBtn = document.getElementById('closeCouponBtn');
const applyBtn = document.getElementById('applyCouponBtn');
const couponInput = document.getElementById('couponInput');
const couponCodeDiv = document.getElementById('coupon_code');
const errorMsg = document.getElementById('errorMsg');

function showModal() {
  modal.style.display = 'flex';
  couponInput.value = '';
  errorMsg.style.display = 'none';
  couponInput.focus();
  document.body.style.overflow = 'hidden';
}

function hideModal() {
  modal.style.display = 'none';
  document.body.style.overflow = '';
}

openBtn.addEventListener('click', showModal);
closeBtn.addEventListener('click', hideModal);

modal.addEventListener('click', (e) => {
  if (e.target === modal) hideModal();
});

document.addEventListener('keydown', (e) => {
  if (e.key === 'Escape' && modal.style.display === 'flex') {
    hideModal();
  }
});

document.getElementById('applyCouponBtn').addEventListener('click', () => {
  const code = couponInput.value.trim().toUpperCase();
  const coupons = {
    "D10": 10,
    "SAVE20": 20,
    "FUTURO20": 20
  };

  if (!coupons[code]) {
    alert("Cupón inválido o expirado.");
    return;
  }

  const discountPercent = coupons[code];
  const cartTotalEl = parseFloat(document.getElementById('cart-total').textContent) || 0;
   
  const subtotalOriginal = cartTotalEl ;

  const shippingPriceEl = document.getElementById('shipping-price');

  // const subtotalOriginal = parseFloat(cartTotalEl?.textContent.replace('$','')) || 0;
  const shippingPrice = parseFloat(shippingPriceEl?.textContent) || 0;
  const discountAmount = subtotalOriginal * discountPercent / 100;
  // const totalNew = subtotalOriginal - discountAmount;
  const totalNew = subtotalOriginal - discountAmount + shippingPrice;

  // Actualiza DiscountSummary si existe
  if (window.discountSummary instanceof DiscountSummary) {
    window.discountSummary.apply(discountAmount, totalNew);
  } else {
    // fallback
    const discountRow = document.getElementById('discount-row');
    const newTotalRow = document.getElementById('new-total-row');
    const discountAmountEl = document.getElementById('discount-amount');
    const newTotalEl = document.getElementById('new-total');

    if (discountRow && discountAmountEl && newTotalEl) {
      discountRow.style.display = 'flex';
      newTotalRow.style.display = 'flex';
      discountAmountEl.textContent = `-${discountAmount.toFixed(2)}`;
      newTotalEl.textContent = `${totalNew.toFixed(2)}`;
    }
  }

  
  const totalInput = document.getElementById('cart-total-input');
  if (totalInput) totalInput.value = totalNew.toFixed(2);

  // couponCodeDiv.textContent = code;
  // Después de calcular discountAmount y totalNew
couponCodeDiv.textContent = code;

// --- NUEVO: llenar los inputs ocultos ---
const couponInputHidden = document.getElementById('coupon_code_input');
const discountInputHidden = document.getElementById('discount_amount_input');

if(couponInputHidden) couponInputHidden.value = code;
if(discountInputHidden) discountInputHidden.value = discountAmount.toFixed(2);

// hideModal();

  hideModal();

});

// Inicializar DiscountSummary globalmente
document.addEventListener('DOMContentLoaded', () => {
  window.discountSummary = new DiscountSummary();
});
</script>

<style>
    #couponModal {
      display: none; /* Oculto al cargar la página */
      position: fixed;
      z-index: 9999;
      left: 0; top: 0;
      width: 100%; height: 100%;
      background: rgba(0,0,0,0.5);
      backdrop-filter: blur(3px);
      justify-content: center;
      align-items: center;
    }

  #couponModal .modal-content {
    background: white;
    padding: 2rem;
    border-radius: 12px;
    width: 320px;
    text-align: center;
    box-shadow: 0 8px 20px rgba(0,0,0,0.3);
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
  }
  #couponModal h2 {
    margin-top: 0;
    color: var(--color-secondary);
  }
  #couponModal p {
    margin: 1rem 0;
    font-size: 1rem;
    color: #333;
  }
  #couponModal input[type="text"] {
    width: 80%;
    padding: 0.5rem;
    font-size: 1rem;
    border: 2px solid #F15A24;
    border-radius: 8px;
    margin-top: 1rem;
    outline-offset: 2px;
  }
  #couponModal button {
    margin-top: 1.5rem;
    background: var(--color-primary);
    border: none;
    color: white;
    padding: 0.7rem 1.4rem;
    font-weight: bold;
    border-radius: 8px;
    cursor: pointer;
    transition: background 0.3s ease;
  }
  #couponModal button:hover {
    background: var(--color-secondary);
  }

  .openCouponBtn,
  #openCouponBtn {
    /*position: fixed;*/
    /*bottom: 20px;*/
    /*right: 20px;*/
    background: var(--color-contrasting);
    border: none;
    color: white;
    padding: 0.8rem 1.6rem;
    font-weight: 700;
    border-radius: 50px;
    cursor: pointer;
    /*box-shadow: 0 6px 15px rgba(241, 90, 36, 0.5);*/
    /*font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;*/
    /*z-index: 10000;*/
    user-select: none;
    margin: 20px 0;
  }
  #openCouponBtn:hover {
    background: var(--color-secondary);
    color: var(--color-primary);
  }

  /* Div oculto para almacenar el código */
  #coupon_code {
    display: none;
  }
</style>
