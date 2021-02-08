class Productos extends Uploadpicture {
    constructor() {
        super();
    }

    getCompras(page) {
        $.post(
            URL + "Productos/getCompras",
            {search: $("#searchComprasPD").val(), page: page},
            (response) => {
                //console.log(response);
                try {
                    let item = JSON.parse(response);
                    $("#productoCompras").html(item.dataFilter);
                    $("#productoComprasPD").html(item.paginador);
                } catch (error) {
                    $("#productoComprasPD").html(response);
                }
            }
        );
    }

    dataProducto(idTemp) {
        $.post(
            URL + "Productos/getProducto",
            {idTemp: idTemp},
            (response) => {

                try {
                    let item = JSON.parse(response);
                    console.log(item);
                    document.getElementById("productoCompraImg").innerHTML = ['<img class=" responsive-img valign profile-image img" src="', URL + FOTOS + "compras/" + item[0].Codigo + ".png", '" title="', escape(item[0].Codigo), '"/>'].join('');
                    document.getElementById("Descripcion").value = item[0].Descripcion;
                    document.getElementById("productProveedor").innerHTML = item[0].Proveedor;
                    document.getElementById("productDescrip").innerHTML = item[0].Descripcion;
                    document.getElementById("productPrecio").innerHTML = item[0].Precio;
                    document.getElementById("productCantidad").innerHTML = item[0].Cantidad;
                    if (item[0].Credito) {
                        document.getElementById("productCredito").innerHTML = "<span class='green-text text-darken-3'>Activo</span>";
                    } else {
                        document.getElementById("productCredito").innerHTML = "<span class='deep-orange-text text-darken-4'>No disponible</span>";
                    }
                    document.getElementById("productImporte").innerHTML = item[0].Importe;
                    document.getElementById("productFecha").innerHTML = item[0].Fecha;
                    $("#barcode").barcode(
                        item[1], // Valor del codigo de barras
                        "code128" // tipo (cadena)
                    );
                } catch (error) {
                    $("#messageProductos").html(response);
                }
            }
        );
    }

    Registrar() {
        $.post(
            "registrarProducto",
            $('.registrarProducto').serialize(),
            (response) => {
                if (response == 0) {
                    window.location.href = URL + "Productos/productos";
                    console.log(response);

                } else {
                    $("#messageProductos").html(response);

                }
            }
        );
    }

    getInventario(page) {
        $.post(
            URL + "Productos/getInventario",
            {search: $("#productoInventario").val(), page: page},
            (response) => {
                try {
                    let item = JSON.parse(response);
                    $("#inventarioProductos").html(item.dataFilter);
                    $("#inventarioProductosPD").html(item.paginador);
                } catch (error) {
                    $("#inventarioProductosPD").html(response);
                }
            }
        );
    }

    detallesProducto(Id) {
        $.post(
            URL + "Productos/getDetalles",
            {Id: Id},
            (response) => {
                //console.log(response);
                try {
                    let item = JSON.parse(response);
                    console.log(item);
                    document.getElementById("productoDetalles").innerHTML = "<img class='imgProducto' src='data:image/jpeg;base64," + item.Image + "' />";
                    document.getElementById("detalleDescripcion").innerHTML = item.Descripcion;
                    $("#barcodeDetalles").barcode(
                        item.Codigo, // Valor del codigo de barras
                        "code11", // tipo (cadena)
                    );
                    document.getElementById("descripcionDetalles").value = item.Descripcion;
                    document.getElementById("precioDetalles").value = item.Precio.replace("$", "");
                    document.getElementById("descuentoDetalles").value = item.Descuento.replace("%", "");
                    document.getElementById("depatamentoDetalles").value = item.Departamento;
                    document.getElementById("categoriaDetalles").value = item.Catgoria;
                    document.getElementById("fechaDetalles").value = item.Fecha;
                } catch (error) {
                    document.getElementById("messageDetalles").innerHTML = response;
                }
            }
        );

    }

    actualizarProducto() {
        var data = new FormData();
        $.each($('input[type=file]')[0].files, (i, file) => {
            data.append('file', file);
        });
        data.append('Descripcion', $("#descripcionDetalles").val());
        data.append('Precio', $("#precioDetalles").val());
        data.append('Descuento', $("#descuentoDetalles").val());
        data.append('Departamento', $("#depatamentoDetalles").val());
        data.append('Categoria', $("#categoriaDetalles").val());
        $.ajax({
            url: "actualizarProducto",
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: (response) => {
                if (response == 0) {
                    window.location.href = URL + "Productos/inventario";
                } else {
                    $("#messageDetalles").html(response);
                }
            }
        });
    }

    exportarInventarios(page) {
        $.post(
            URL + "Productos/exportarInventarios",
            {search: $("#productoInventario").val(), page: page},
            (response) => {

            }
        );
    }
}