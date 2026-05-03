document.getElementById("formProducto").addEventListener("submit", function (event) {
    const nombre = document.getElementById("nombre").value.trim();
    const precio = parseFloat(document.getElementById("precio").value);
    const stock = parseInt(document.getElementById("stock").value);

    if (nombre === "") {
        alert("El nombre del producto es obligatorio.");
        event.preventDefault();
        return;
    }

    if (isNaN(precio) || precio <= 0) {
        alert("El precio debe ser mayor a 0.");
        event.preventDefault();
        return;
    }

    if (isNaN(stock) || stock < 0) {
        alert("El stock no puede ser negativo.");
        event.preventDefault();
    }
});