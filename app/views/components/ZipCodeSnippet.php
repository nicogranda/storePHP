<div id="zip-code-snippet" class="zip-snippet">
  <div class="form-group">
    <label for="zip_code">ZIP Code</label>
    <input type="text" id="zip_code" placeholder="e.g. 30350" maxlength="5">
    <div id="zip-warning" class="input-error"></div>
    <div id="location-info" class="zip-location" style="display:none;"></div>
  </div>
</div>

<script>
/* ===================================================
   ZipCodeSnippet Component
   =================================================== */
class ZipCodeSnippet {
  constructor({ onValidated, endpoint = "api/USPS/rate.php", debug = false } = {}) {
    this.zipInput = document.getElementById("zip_code");
    this.warningEl = document.getElementById("zip-warning");
    this.locationEl = document.getElementById("location-info");
    this.endpoint = endpoint;
    this.onValidated = onValidated || (() => {});
    this.debug = debug;

    this.bindEvents();
  }

  bindEvents() {
    if (!this.zipInput) return;
    this.zipInput.addEventListener("blur", () => this.validateAndFetch());
  }

  isValidZip(zip) {
    return /^\d{5}$/.test(zip);
  }

  async validateAndFetch() {
    const zip = this.zipInput.value.trim();
    if (!this.isValidZip(zip)) {
      this.warningEl.textContent = "Invalid ZIP Code.";
      this.locationEl.style.display = "none";
      return;
    }

    this.warningEl.textContent = "Checking...";
    this.locationEl.style.display = "none";

    try {
      const response = await fetch(this.endpoint, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ zip }),
      });

      if (!response.ok) throw new Error(`HTTP ${response.status}`);

      const data = await response.json();
      if (this.debug) console.log("📦 USPS API Response:", data);

      if (data.error) {
        this.warningEl.textContent = data.message || "Error fetching rate.";
        this.locationEl.style.display = "none";
        return;
      }

      const city = data.destination?.city || data.city || "";
      const state = data.destination?.state || data.state || "";
      const delivery_time = data.delivery_time || "N/A";
      const price = parseFloat(data.price) || 0;

      if (city && state) {
        this.locationEl.textContent = `${city}, ${state}`;
        this.locationEl.style.display = "block";

        // Actualizar los hidden inputs del formulario
document.getElementById('zipcode').value = zip;
document.getElementById('city').value    = city;
document.getElementById('state').value   = state;

        
      } else {
        this.locationEl.textContent = "Location not found.";
        this.locationEl.style.display = "block";
      }

      this.warningEl.textContent = "";

      this.onValidated({ zip, city, state, delivery_time, price });

      if (this.debug) console.log("✅ ZIP validated:", { zip, city, state, delivery_time, price });

    } catch (error) {
      console.error("🚨 ZIP Fetch Error:", error);
      this.warningEl.textContent = "Error connecting to shipping service.";
      this.locationEl.style.display = "none";
    }
  }
}
</script>

<style>
.zip-location {
  font-size: 13px;
  color: #333;
  margin-top: 4px;
  font-style: italic;
  transition: opacity 0.3s ease;
}
</style>
