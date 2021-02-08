class Compras extends Uploadpicture {
    constructor() {
        super();
        this.data = null;
    }

    getProveedores(page) {
        $.post(
            URL + "Compras/getProveedores",
            {search: $("#searchCompraPD").val(), page: page},
            (response) => {
                // console.log(response);
                try {
                    let item = JSON.parse(response);
                    $("#compraProveedores").html(item.dataFilter);
                    $("#compraPD").html(item.paginador);
                } catch (error) {
                    $("#compraPD").html(response);
                }
            }
        );
    }

    dataProveedor(data) {
        this.data = data;
        // console.log(data);
        $("#Proveedor").val(data.Proveedor);
    }

    detallesCompras() {
        var valor = true;
        if (this.data != null) {
            var data = new FormData();
            $.each($('input[type=file]')[0].files, (i, file) => {
                data.append('file', file);
            });
            var credito = document.getElementById("Credito").checked;
            var url = "Compras/detallesCompras";
            data.append('Descripcion', $("#Descripcion").val());
            data.append('Cantidad', $("#Cantidad").val());
            data.append('Precio', $("#Precio").val());
            data.append('IdProveedor', this.data.IdProveedor);
            data.append('Proveedor', $("#Proveedor").val());
            data.append('Email', this.data.Email);
            data.append('Credito', credito);
            $.ajax({
                url: URL + url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: (response) => {
                    try {
                        let item = JSON.parse(response);
                        localStorage.setItem("Compra", JSON.stringify(new Array(
                            $("#Descripcion").val(),
                            $("#Cantidad").val(),
                            $("#Precio").val(),
                            this.data.Proveedor,
                            $("#Proveedor").val(),
                            this.data.Email,
                            credito,
                            item.Codigo,
                            item.results
                        )));
                        window.location.href = URL + "Compras/detalles";

                    } catch (error) {
                        document.getElementById("messageCompras").innerHTML = response;
                    }
                }
            });
            valor = false;
        } else {
            valor = true;
            document.getElementById("messageCompras").innerHTML = "Selecione un proveedor";
        }
        return valor;
    }

    detalles() {
        var item = JSON.parse(localStorage.getItem("Compra"));
        console.log(item);
        document.getElementById("dProveedor").innerHTML = "Proveedor: " + item[3];
        document.getElementById("dProducto").innerHTML = item[0];
        document.getElementById("dPrecio").innerHTML = "$" + numberDecimales(item[2].replace("$", ""));
        document.getElementById("dCantidad").innerHTML = item[1];
        var precio = item[2].replace("$", "").replace(",", "");
        var importe = precio * item[1];
        if (item[6] && item[8] != null) {
            document.getElementById("dCredito").innerHTML = '<span class="green-text text-darken-3">Activo</span>';
            var deuda = importe + parseFloat(item[8].Deuda.replace("$", "").replace(",", ""));
            $("#deuda").html("$" + numberDecimales(deuda));
            $("#fechadeuda").html(getFechas());
            $("#pago").html(item[8].Pago);
            $("#fechapago").html(item[8].FechaPago);
            $("#ticket").html(item[8].Ticket);
            $("#proveedorNombre").html("Proveedor: " + item[3]);
            document.getElementById('tickets').style.display = 'block';
            document.getElementById('btnTicket').style.display = 'block';
        } else {
            document.getElementById("dCredito").innerHTML = '<span class="deep-orange-text text-darken-4">No disponible</span>';
            document.getElementById('tickets').style.display = 'none';
            document.getElementById('btnTicket').style.display = 'none';
        }
        if (item[8] == null) {
            document.getElementById('btnTicket').style.display = 'none';
            document.getElementById('comprar').style.display = 'none';
            document.getElementById("dFecha").innerHTML = item[9];
        } else {
            document.getElementById("dFecha").innerHTML = getFechas();
        }
        document.getElementById("dImporte").innerHTML = "$" + numberDecimales(importe);


        document.getElementById("imageDetalles").innerHTML = ['<img class="responsive-img " src="', PATHNAME + "Resource/images/fotos/compras/" + item[7] + ".png", '" title="', escape(item[7]), '"/>'].join('');
    }

    Comprar() {
        $.post(
            URL + "Compras/comprar",
            {},
            (response) => {
                if (response == 0) {
                    window.location.href = URL + "Compras/Compras";
                } else {
                    document.getElementById("detallesMessage").innerHTML = response;
                }

                // console.log(response);
            }
        );

    }

    getCompras(page) {
        $.post(
            URL + "Compras/getCompras",
            {search: $("#searchCompras").val(), page: page},
            (response) => {
                try {
                    let item = JSON.parse(response);
                    $("#dataFilterCompras").html(item.dataFilter);
                    $("#paginadorCompras").html(item.paginador);
                } catch (error) {
                    $("#paginadorCompras").html(response);
                }
            }
        );
    }

    getCompra(data) {
        console.log(data);
        localStorage.setItem("Compra", JSON.stringify(new Array(
            data.Descripcion,
            data.Cantidad,
            data.Precio,
            data.Proveedor,
            data.IdProveedor,
            data.Email,
            data.Credito,
            data.Codigo,
            null,
            data.Fecha
        )));
        window.location.href = URL + "Compras/detalles";
    }

    exportarCompras(page) {
        $.post(
            URL + "Compras/exportarCompras",
            {search: $("#searchCompras").val(), page: page},
            (response) => {

            }
        );
    }
}