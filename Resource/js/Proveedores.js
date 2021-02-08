class Proveedores extends Uploadpicture {
    constructor() {
        super();
        this.Funcion = 0;
        this.IdProveedor = 0;
        this.Imagen = null;
    }
    registerProveedores() {
        var data = new FormData();
        $.each($('input[type=file]')[0].files, (i, file) => {
            data.append('file', file);
        });
        var url = this.Funcion == 0 ? "Proveedores/registerProvedores" : "Proveedores/editProveedor";
        data.append('idProveedor', this.IdProveedor);
        data.append('proveedor', $("#proveedor").val());
        data.append('telefono', $("#telefono").val());
        data.append('email', $("#email").val());
        data.append('direccion', $("#direccion").val());
        data.append('imagen', this.Imagen);
        $.ajax({
            url: URL + url,
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            type: 'POST',
            success: (response) => {
                if (response == 0) {
                    this.restablecerProveedores();
                } else {
                    document.getElementById("messageProveedor").innerHTML = response;
                }
                //console.log(response);
            }
        });
        return false;
    }
    getProveedores(page) {
        $.post(
            URL + "Proveedores/getProveedores",
            { search: $("#searchProveedores").val(), page: page },
            (response) => {
               // console.log(response);
                try {
                    let item = JSON.parse(response);
                    $("#resultProveedores").html(item.dataFilter);
                    $("#paginadorProveedores").html(item.paginador);
                } catch (error) {
                    $("#paginadorProveedores").html(response);
                }
            }
        );
    }
    dataProveedor(email) {
        $.post(
            URL + "Proveedores/dataProveedor",
            { email: email },
            (response) => {
                switch (response) {
                    case "1":
                        window.location.href = URL;
                        break;
                    case "2":
                        window.location.href = URL + "Proveedores/proveedores";
                        break;
                    default:
                        try {
                            let item = JSON.parse(response);
                            this.Funcion = 1;
                            this.IdProveedor = item.results[0].IdProveedor;
                            this.Imagen = item.results[0].Email;
                            document.getElementById("fotoProveedor").innerHTML = ['<img class="responsive-img " src="', PATHNAME + "Resource/images/fotos/proveedores/" + item.results[0].Email + ".png", '" title="', escape(item.results[0].Email), '"/>'].join('');

                            document.getElementById("proveedor").value = item.results[0].Proveedor;
                            document.getElementById("telefono").value = item.results[0].Telefono;
                            document.getElementById("email").value = item.results[0].Email;
                            document.getElementById("direccion").value = item.results[0].Direccion;
                        } catch (error) {
                            $("#messageProveedor").html(response);
                        }
                        break;
                }
               // console.log(response);
            }
        );
    }
    getReporteProveedor(email) {
        $.post(
            URL + "Proveedores/getReporteProveedor",
            { email: email },
            (response) => {
                try {
                    let item = JSON.parse(response);
                    if (0 != item.data) {
                        $("#proveedorNombre").html(item.array.Proveedor);
                        document.getElementById("proveedorReporte").innerHTML = ['<img class=" responsive-img valign profile-image img" src="', URL + FOTOS + "proveedores/" + item.array.Email + ".png", '" title="', escape(item.array.Email), '"/>'].join('');
                        $("#deuda").html(item.array.Deuda);
                        $("#fechadeuda").html(item.array.FechaDeuda);
                        $("#pago").html(item.array.Pago);
                        $("#fechapago").html(item.array.FechaPago);
                        $("#ticket").html(item.array.Ticket);
                        $("#proveedorNombres").html("Proveedor: " + item.array.Proveedor);
                        $("#deudas").html(item.array.Deuda);
                        localStorage.setItem("reportProveedor", response);
                    } else {
                        window.location.href = URL + "Proveedores/proveedores";
                    }
                } catch (error) {
                    $("#reporteProveedorMessage").html(response);
                }
               // console.log(response);
            }
        );
    }
    setPagos() {
        $.post(
            URL + "Proveedores/setPagos",
            {
                pagos: $("#pagos").val()

            },
            (response) => {
                console.log(response);
                if (response == 0) {
                    let proveedor = JSON.parse(localStorage.getItem("reportProveedor"));

                    this.getReporteProveedor(proveedor.array.Email);
                } else {
                    $("#pagoProveedor").html(response);
                }
            }
        );

        return false;
    }
    getTickets(page) {
        $.post(
            URL + "Proveedores/getTickets",
            { search: $("#searchTicket").val(), page: page },
            (response) => {
                console.log(response);
                try {
                    let item = JSON.parse(response);
                    $("#resultTicketP").html(item.dataFilter);
                    $("#paginadorTicketP").html(item.paginador);
                } catch (error) {
                    $("#paginadorTicketP").html(item.paginador);
                }
            }
        );
    }
    exportarExcel(page,valor) {
        $.post(
            URL + "Proveedores/exportarExcel",
            { search: $("#searchTicket").val(), page: page,valor:valor },
            (response) => {
                console.log(response);
            }
        );

    }
    restablecerProveedores() {
        this.Funcion = 0;
        this.IdProveedor = 0;
        this.Imagen = null;
        window.location.href = URL + "Proveedores/proveedores";
    }
}