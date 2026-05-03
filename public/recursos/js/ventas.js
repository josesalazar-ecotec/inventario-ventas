function agregarItem() {
    const container = document.getElementById("items");

    const div = document.createElement("div");
    div.classList.add("fila-venta");

    div.innerHTML = `
        <select name="producto_id[]" onchange="calcularTotal()">
            <option value="">Seleccione producto</option>
            ${productos.map(p =>
                `<option value="${p.id}" data-precio="${p.precio}">
                    ${p.nombre} - PVP: $${parseFloat(p.precio).toFixed(2)} - Stock: ${p.stock}
                </option>`
            ).join("")}
        </select>

        <input 
            type="number" 
            name="cantidad[]" 
            min="1" 
            placeholder="Cantidad" 
            oninput="calcularTotal()"
        >

        <span class="precio">$0.00</span>
        <span class="subtotal">$0.00</span>
    `;

    container.appendChild(div);
}

function calcularTotal() {
    let total = 0;

    const filas = document.querySelectorAll(".fila-venta");

    filas.forEach(fila => {
        const select = fila.querySelector("select");
        const cantidadInput = fila.querySelector("input");
        const precioSpan = fila.querySelector(".precio");
        const subtotalSpan = fila.querySelector(".subtotal");

        if (!select || !cantidadInput || !precioSpan || !subtotalSpan) {
            return;
        }

        const precio = parseFloat(select.selectedOptions[0]?.dataset?.precio || 0);
        const cantidad = parseInt(cantidadInput.value) || 0;
        const subtotal = precio * cantidad;

        precioSpan.innerText = `$${precio.toFixed(2)}`;
        subtotalSpan.innerText = `$${subtotal.toFixed(2)}`;

        total += subtotal;
    });

    document.getElementById("total").innerText = total.toFixed(2);
}

document.getElementById("formVenta").addEventListener("submit", function (event) {
    const filas = document.querySelectorAll("#items .fila-venta");

    if (filas.length === 0) {
        alert("Debe agregar al menos un producto.");
        event.preventDefault();
        return;
    }

    if (!confirm("¿Deseas registrar esta venta?")) {
        event.preventDefault();
    }
});