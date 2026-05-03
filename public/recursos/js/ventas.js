document.getElementById("formVenta").addEventListener("submit", function (event) {
    const producto = document.getElementById("producto_id").value;
    const cantidad = parseInt(document.getElementById("cantidad").value);

    if (producto === "") {
        alert("Debe seleccionar un producto.");
        event.preventDefault();
        return;
    }

    if (isNaN(cantidad) || cantidad <= 0) {
        alert("La cantidad debe ser mayor a 0.");
        event.preventDefault();
        return;
    }

    if (!confirm("¿Deseas registrar esta venta?")) {
        event.preventDefault();
    }
});