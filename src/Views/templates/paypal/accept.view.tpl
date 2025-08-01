<h2>Pago procesado correctamente</h2>

<p><strong>ID de la Orden:</strong> {{orderId}}</p>
<p><strong>Comprador:</strong> {{payerName}} ({{payerEmail}})</p>
<p><strong>Estado:</strong> {{status}}</p>

<h3>Resumen de pago:</h3>
<ul>
    <li><strong>Total bruto:</strong> ${{grossAmount}}</li>
    <li><strong>Comisión PayPal:</strong> ${{paypalFee}}</li>
    <li><strong>Total neto recibido:</strong> ${{netAmount}}</li>
</ul>

{{if error}}
    <div class="error">{{error}}</div>
{{endif}}
