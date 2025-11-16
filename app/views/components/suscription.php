<!-- Subscription Snippet -->
<section id="subscribe" aria-labelledby="subscribe-title" class="subscribe-section">
  <div class="subscribe-card">
    <h2 id="subscribe-title" class="subscribe-title">Subscribe to our emails</h2>
    <p class="subscribe-sub">
      And enjoy a <strong>10% discount</strong> off your next order.
    </p>

    <form id="subscriptionForm" class="subscribe-form" novalidate>
      <label for="email" class="sr-only">Email</label>
      <input
        id="email"
        name="email"
        type="email"
        inputmode="email"
        placeholder="Enter your email"
        autocomplete="email"
        required
        aria-required="true"
      />
      <button type="submit" aria-label="Subscribe and get 10% off">Subscribe</button>
    </form>

    <p id="subscribe-message" class="subscribe-message" aria-live="polite"></p>
    <small class="subscribe-note">No spam. Only good vibes and deals.</small>
  </div>
</section>

<style>
/* Minimal CSS - ajusta variables si ya las tenés en el style global */
.subscribe-section {
  padding: 1.25rem;
  display: flex;
  justify-content: center;

}

.subscribe-card {
  width: 60%;
  /* max-width: 1400px; */
  background: var(--color-third, #fff);
  border: 1px solid var(--color-border, #e6e6e6);
  box-shadow: 0 6px 20px rgba(0,0,0,0.05);
  /* border-radius: 10px; */
  padding: 1.25rem;
  box-sizing: border-box;
  text-align: center;
}

.subscribe-title {
  margin: 0 0 0.25rem;
  font-size: 1.25rem;
  font-weight: 700;
  color: var(--color-text, #111);
}

.subscribe-sub {
  margin: 0 0 0.8rem;
  color: #444;
}

.coupon {
  background: rgba(0,115,230,0.08);
  padding: 0.15rem 0.4rem;
  border-radius: 4px;
  font-weight: 700;
  color: var(--color-primary, #0073e6);
}

.subscribe-form {
  display: flex;
  gap: 0.5rem;
  align-items: center;
  justify-content: center;
  margin-bottom: 0.5rem;
  width: 700px ;
}

.subscribe-form input[type="email"] {
  flex: 1 1 auto;
  min-width: 0;
 
  padding: 0.6rem 0.75rem;
  border: 1px solid #d8d8d8;
  border-radius: 8px;
  font-size: 0.95rem;
  box-sizing: border-box;
  outline: none;
}

.subscribe-form input[type="email"]:focus {
  border-color: var(--color-primary, #0073e6);
  box-shadow: 0 0 0 3px rgba(0,115,230,0.08);
}

.subscribe-form button {
  background: var(--color-primary, #0073e6);
  color: black;
  border: none;
  padding: 0.6rem 0.9rem;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  white-space: nowrap;
  transition: transform 0.12s ease, filter 0.12s ease;
}

.subscribe-form button:hover {
  transform: translateY(-2px);
  filter: brightness(0.95);
}

.subscribe-message {
  min-height: 1.2rem;
  margin: 0.25rem 0;
  color: #1b7a2a; /* success */
  font-weight: 600;
}

.subscribe-message.error {
  color: #b02a2a;
}

/* screen-reader only */
.sr-only {
  position: absolute !important;
  height: 1px; width: 1px;
  overflow: hidden;
  clip: rect(1px, 1px, 1px, 1px);
  white-space: nowrap;
}

/* Responsive */
@media (max-width: 520px) {
  .subscribe-form {
    flex-direction: column;
  }

  .subscribe-form button {
    width: 100%;
  }
}
</style>

<script>
(function () {
  const form = document.getElementById('subscriptionForm');
  const emailInput = document.getElementById('email');
  const messageEl = document.getElementById('subscribe-message');
  const STORAGE_KEY = 'subscribed_emails_v1';

  function showMessage(msg, isError = false) {
    messageEl.textContent = msg;
    messageEl.classList.toggle('error', isError);
  }

  function isAlreadySubscribed(email) {
    try {
      const store = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
      return store.includes(email.toLowerCase());
    } catch (e) {
      return false;
    }
  }

  function saveSubscribed(email) {
    try {
      const store = JSON.parse(localStorage.getItem(STORAGE_KEY) || '[]');
      store.push(email.toLowerCase());
      localStorage.setItem(STORAGE_KEY, JSON.stringify(Array.from(new Set(store))));
    } catch (e) {
      // ignore
    }
  }

  form.addEventListener('submit', function (evt) {
    evt.preventDefault();
    messageEl.classList.remove('error');

    const email = emailInput.value.trim();
    if (!email) {
      showMessage('Please provide an email address.', true);
      emailInput.focus();
      return;
    }

    // Simple client-side email validation
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      showMessage('That email doesn’t look valid — try again.', true);
      emailInput.focus();
      return;
    }

    if (isAlreadySubscribed(email)) {
      showMessage('You already subscribed — check your inbox for code VIP10. 🚀');
      return;
    }

    // Simulate an API call (replace URL with your endpoint)
    showMessage('Subscribing…');

    /* Example real request:
    fetch('/api/subscribe', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email, source: 'VIP_BANNER', coupon: 'VIP10' })
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) { ... } else { ... }
    });
    */

    // Simulated async flow
    setTimeout(() => {
      // On success:
      saveSubscribed(email);
      showMessage('Thanks! You’re in — use code VIP10 on your next order. ✨');
      emailInput.value = '';
    }, 700);
  });

  // Optional: support pressing Enter on input
  emailInput.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
    }
  });
})();
</script>
