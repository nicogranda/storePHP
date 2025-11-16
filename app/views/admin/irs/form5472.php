<style>
  .form-5472 {
    max-width: 800px;
    margin: 0 auto;
    font-family: sans-serif;
  }

  .form-5472 h2 {
    margin-top: 2rem;
    color: #F15A24;
    border-bottom: 2px solid rgba(241, 90, 36, 0.2);
    padding-bottom: 5px;
  }

  .form-group {
    display: flex;
    margin-bottom: 1rem;
  }

  .form-group label {
    flex: 1;
    padding-right: 10px;
    font-weight: bold;
  }

  .form-group input,
  .form-group select,
  .form-group textarea {
    flex: 2;
    padding: 5px;
    border: 1px solid #ccc;
    border-radius: 4px;
  }

  textarea {
    resize: vertical;
    height: 60px;
  }

  button {
    margin-top: 2rem;
    padding: 10px 20px;
    background-color: #F15A24;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
  }

  button:hover {
    background-color: #d94d1f;
  }
</style>

<form action="index.php?page=irs&action=5472" method="post" target="_blank" class="form-5472">
  <h2>Página 1 – Información de la Corporación</h2>

  <div class="form-group">
    <label>Nombre de la corporación:</label>
    <input type="text" name="corp_name" required>
  </div>

  <div class="form-group">
    <label>Dirección (calle y número):</label>
    <input type="text" name="corp_address" required>
  </div>

  <div class="form-group">
    <label>Ciudad, Estado, ZIP:</label>
    <input type="text" name="corp_citystatezip" required>
  </div>

  <div class="form-group">
    <label>EIN:</label>
    <input type="text" name="ein" required>
  </div>

  <div class="form-group">
    <label>Activos Totales ($):</label>
    <input type="number" step="0.01" name="total_assets">
  </div>

  <div class="form-group">
    <label>Actividad principal:</label>
    <input type="text" name="activity">
  </div>

  <div class="form-group">
    <label>Código de actividad:</label>
    <input type="text" name="activity_code">
  </div>

  <div class="form-group">
    <label>Pagos brutos recibidos (este formulario):</label>
    <input type="number" step="0.01" name="gross_payments_form">
  </div>

  <div class="form-group">
    <label>País de incorporación:</label>
    <input type="text" name="country_incorp">
  </div>

  <div class="form-group">
    <label>Fecha de incorporación:</label>
    <input type="date" name="date_incorp">
  </div>

  <h2>Página 2 – Accionistas Extranjeros</h2>

  <div class="form-group">
    <label>Nombre del accionista:</label>
    <input type="text" name="shareholder_name">
  </div>

  <div class="form-group">
    <label>País de ciudadanía/incorporación:</label>
    <input type="text" name="shareholder_country">
  </div>

  <div class="form-group">
    <label>FTIN:</label>
    <input type="text" name="shareholder_ftin">
  </div>

  <div class="form-group">
    <label>País donde declara impuestos:</label>
    <input type="text" name="shareholder_tax_country">
  </div>

<style>
  .transactions-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 1.5rem;
  }

  .transactions-table th,
  .transactions-table td {
    border: 1px solid #ccc;
    padding: 8px;
    vertical-align: top;
  }

  .transactions-table th {
    background-color: #f3f3f3;
    text-align: left;
  }

  .transactions-table input {
    width: 100%;
    padding: 4px;
    box-sizing: border-box;
  }

  .transactions-table caption {
    caption-side: top;
    font-weight: bold;
    font-size: 1.2em;
    color: #F15A24;
    margin-bottom: 0.5rem;
  }
</style>

<table class="transactions-table">
  <caption>Página 3 – Transacciones Monetarias</caption>
  <thead>
    <tr>
      <th>Línea</th>
      <th>Concepto</th>
      <th>Valor ($)</th>
    </tr>
  </thead>
  <tbody>
    <tr><td>9</td><td>Ventas de inventario</td><td><input type="number" step="0.01" name="line_9"></td></tr>
    <tr><td>10</td><td>Ventas de propiedad tangible</td><td><input type="number" step="0.01" name="line_10"></td></tr>
    <tr><td>11</td><td>Pagos recibidos por contribución de plataforma</td><td><input type="number" step="0.01" name="line_11"></td></tr>
    <tr><td>12</td><td>Pagos recibidos por reparto de costos</td><td><input type="number" step="0.01" name="line_12"></td></tr>
    <tr><td>13a</td><td>Alquileres recibidos (no intangibles)</td><td><input type="number" step="0.01" name="line_13a"></td></tr>
    <tr><td>13b</td><td>Regalías recibidas (no intangibles)</td><td><input type="number" step="0.01" name="line_13b"></td></tr>
    <tr><td>14</td><td>Ventas/licencias de propiedad intangible</td><td><input type="number" step="0.01" name="line_14"></td></tr>
    <tr><td>15</td><td>Servicios técnicos, científicos, etc.</td><td><input type="number" step="0.01" name="line_15"></td></tr>
    <tr><td>16</td><td>Comisiones recibidas</td><td><input type="number" step="0.01" name="line_16"></td></tr>
    <tr><td>17b</td><td>Montos prestados – saldo final o promedio mensual</td><td><input type="number" step="0.01" name="line_17b"></td></tr>
    <tr><td>18</td><td>Intereses recibidos</td><td><input type="number" step="0.01" name="line_18"></td></tr>
    <tr><td>19</td><td>Primas de seguros recibidas</td><td><input type="number" step="0.01" name="line_19"></td></tr>
    <tr><td>20</td><td>Comisiones por garantías de préstamos recibidas</td><td><input type="number" step="0.01" name="line_20"></td></tr>
    <tr><td>21</td><td>Otros ingresos recibidos</td><td><input type="number" step="0.01" name="line_21"></td></tr>
    <tr><td>22</td><td><strong>Total líneas 9 a 21</strong></td><td><input type="number" step="0.01" name="line_22" readonly></td></tr>

    <tr><td>23</td><td>Compras de inventario</td><td><input type="number" step="0.01" name="line_23"></td></tr>
    <tr><td>24</td><td>Compras de propiedad tangible</td><td><input type="number" step="0.01" name="line_24"></td></tr>
    <tr><td>25</td><td>Pagos por contribución de plataforma</td><td><input type="number" step="0.01" name="line_25"></td></tr>
    <tr><td>26</td><td>Pagos por reparto de costos</td><td><input type="number" step="0.01" name="line_26"></td></tr>
    <tr><td>27a</td><td>Alquileres pagados (no intangibles)</td><td><input type="number" step="0.01" name="line_27a"></td></tr>
    <tr><td>27b</td><td>Regalías pagadas (no intangibles)</td><td><input type="number" step="0.01" name="line_27b"></td></tr>
    <tr><td>28</td><td>Licencias/compras de propiedad intangible</td><td><input type="number" step="0.01" name="line_28"></td></tr>
    <tr><td>29</td><td>Pagos por servicios técnicos, ingeniería, etc.</td><td><input type="number" step="0.01" name="line_29"></td></tr>
    <tr><td>30</td><td>Comisiones pagadas</td><td><input type="number" step="0.01" name="line_30"></td></tr>
    <tr><td>31b</td><td>Montos prestados – saldo final o promedio mensual</td><td><input type="number" step="0.01" name="line_31b"></td></tr>
    <tr><td>32</td><td>Intereses pagados</td><td><input type="number" step="0.01" name="line_32"></td></tr>
    <tr><td>33</td><td>Primas de seguros pagadas</td><td><input type="number" step="0.01" name="line_33"></td></tr>
    <tr><td>34</td><td>Comisiones por garantías de préstamos pagadas</td><td><input type="number" step="0.01" name="line_34"></td></tr>
    <tr><td>35</td><td>Otros pagos realizados</td><td><input type="number" step="0.01" name="line_35"></td></tr>
    <tr><td>36</td><td><strong>Total líneas 23 a 35</strong></td><td><input type="number" step="0.01" name="line_36" readonly></td></tr>
  </tbody>
</table>


  <h2>CSA y BEAT</h2>

  <div class="form-group">
    <label>Descripción del CSA:</label>
    <textarea name="csa_description"></textarea>
  </div>

  <div class="form-group">
    <label>% beneficios esperados (CSA):</label>
    <input type="number" step="0.01" name="csa_benefit_share">
  </div>

  <div class="form-group">
    <label>Pagos 59A (BEAT):</label>
    <input type="number" step="0.01" name="beat_payments">
  </div>

  <div class="form-group">
    <label>Beneficios fiscales (BEAT):</label>
    <input type="number" step="0.01" name="beat_tax_benefits">
  </div>

  <button type="submit">Generar Formulario 5472 PDF</button>
</form>
<script>
document.addEventListener('DOMContentLoaded', () => {
  const sumFieldsGroupA = [
    'line_9', 'line_10', 'line_11', 'line_12', 'line_13a', 'line_13b',
    'line_14', 'line_15', 'line_16', 'line_17b', 'line_18', 'line_19',
    'line_20', 'line_21'
  ];

  const sumFieldsGroupB = [
    'line_23', 'line_24', 'line_25', 'line_26', 'line_27a', 'line_27b',
    'line_28', 'line_29', 'line_30', 'line_31b', 'line_32', 'line_33',
    'line_34', 'line_35'
  ];

  function sumAndSetTotal(inputNames, totalInputName) {
    let total = 0;
    inputNames.forEach(name => {
      const el = document.querySelector(`input[name="${name}"]`);
      if (el && el.value) {
        total += parseFloat(el.value) || 0;
      }
    });
    const totalInput = document.querySelector(`input[name="${totalInputName}"]`);
    if (totalInput) {
      totalInput.value = total.toFixed(2);
    }
  }

  function addListeners(inputNames, totalInputName) {
    inputNames.forEach(name => {
      const el = document.querySelector(`input[name="${name}"]`);
      if (el) {
        el.addEventListener('input', () => {
          sumAndSetTotal(inputNames, totalInputName);
        });
      }
    });
  }

  addListeners(sumFieldsGroupA, 'line_22');
  addListeners(sumFieldsGroupB, 'line_36');

  // Inicializa los totales al cargar la página
  sumAndSetTotal(sumFieldsGroupA, 'line_22');
  sumAndSetTotal(sumFieldsGroupB, 'line_36');
});
</script>
