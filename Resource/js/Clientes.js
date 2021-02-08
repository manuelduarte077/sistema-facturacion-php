class Clientes extends Uploadpicture {
    constructor() {
        super();
        this.Funcion = 0;
        this.IdCliente = 0;
        this.Imagen = null;
    }

    getCreditos(credito, funcion) {
        let count = 1;
        $.post(
            URL + "Clientes/getCreditos",
            {},
            (response) => {
                try {
                    let item = JSON.parse(response);
                    //console.log(item);
                    if (0 < item.results.length) {
                        for (let i = 0; i < item.results.length; i++) {
                            switch (funcion) {
                                case 1:
                                    document.getElementById('creditos').options[i] = new Option(item.results[i].Creditos, item.results[i].IdCreditos);
                                    $('select').formSelect();
                                    break;

                                case 2:
                                    document.getElementById('creditos').options[i] = new Option(item.results[i].Creditos, item.results[i].IdCreditos);
                                    if (item.results[i].Creditos == credito) {
                                        // i++;
                                        document.getElementById('creditos').selectedIndex = i;
                                        // i--;
                                    }
                                    $('select').formSelect();
                                    break;
                            }

                        }
                    }
                } catch (error) {
                    document.getElementById("clienteMessage").innerHTML = response;
                }
            }
        );
    }

    registerCliente() {
        var valor = false;
        if (validarEmail($("#email").val())) {
            var data = new FormData();
            $.each($('input[type=file]')[0].files, (i, file) => {
                data.append('file', file);
            });
            let creditos = document.getElementById("creditos");

            var url = this.Funcion == 0 ? "Clientes/registerCliente" : "Clientes/editCliente";
            data.append('idCliente', this.IdCliente);
            data.append('nombre', $("#nombre").val());
            data.append('apellido', $("#apellido").val());
            data.append('nid', $("#nid").val());
            data.append('telefono', $("#telefono").val());
            data.append('email', $("#email").val());
            data.append('direccion', $("#direccion").val());
            data.append('creditos', creditos.options[creditos.selectedIndex].text);
            data.append('imagen', this.Imagen);
            $.ajax({
                url: URL + url,
                data: data,
                cache: false,
                contentType: false,
                processData: false,
                type: 'POST',
                success: (response) => {
                    console.log(response);
                    if (response == 0) {
                        this.restablecerClientes(1);
                        valor = false;
                    } else {
                        document.getElementById("clienteMessage").innerHTML = response;
                        valor = true;
                    }
                }
            });
        } else {
            document.getElementById("email").focus();
            document.getElementById("clienteMessage").innerHTML = 'Ingrese una dirección de correo electrónico válida';
            return valor;
        }
    }

    getClientes(page) {
        $.post(
            URL + "Clientes/getClientes",
            {search: $("#searchClientes").val(), page: page},
            (response) => {
                // console.log(response);
                try {
                    let item = JSON.parse(response);
                    $("#resultCliente").html(item.dataFilter);
                    $("#paginadorCliente").html(item.paginador);
                } catch (error) {
                    $("#paginadorCliente").html(response);
                }
            }
        );
    }

    getReporteCliente(email) {
        $.post(
            URL + "Clientes/getReporteCliente",
            {email: email},
            (response) => {

                try {
                    let item = JSON.parse(response);
                    // console.log(item);
                    if (0 != item.data) {
                        $("#clienteNombre").html(item.array.Nombre);
                        $("#clienteApellido").html(item.array.Apellido);
                        document.getElementById("clienteReporte").innerHTML = ['<img class=" responsive-img valign profile-image img" src="', URL + FOTOS + "clientes/" + item.array.Email + ".png", '" title="', escape(item.array.Email), '"/>'].join('');
                        $("#deuda").html(item.array.Deuda);
                        $("#fechadeuda").html(item.array.FechaDeuda);
                        $("#pago").html(item.array.Pago);
                        $("#fechapago").html(item.array.FechaPago);
                        $("#ticket").html(item.array.Ticket);
                        $("#clienteNombres").html("Cliente: " + item.array.Nombre + " " + item.array.Apellido);
                        $("#deudas").html(item.array.Deuda);
                        let credito = parseFloat(item.array.Creditos.replace("$", ""));
                        if (credito > 0) {
                            document.getElementById("creditoCliente").innerHTML = "<span>Credito: <span class='green-text text-darken-3'>Activo</span></span>"
                        } else {
                            document.getElementById("creditoCliente").innerHTML = "<span>Credito: <span class='red-text text-darken-4'>No activo</span></span>"
                        }
                        localStorage.setItem("reportCliente", response);
                    } else {
                        window.location.href = URL + "Clientes/clientes";
                    }
                } catch (error) {
                    $("#reporteClienteMessage").html(response);
                }
            }
        );
    }

    setPagos() {
        if (null != localStorage.getItem("reportCliente")) {
            $.post(
                URL + "Clientes/setPagos",
                {
                    pagos: $("#pagos").val()

                },
                (response) => {
                    //console.log(response);
                    if (response == 0) {
                        let cliente = JSON.parse(localStorage.getItem("reportCliente"));

                        this.getReporteCliente(cliente.array.Email);
                    } else {
                        $("#pagoCliente").html(response);
                    }
                }
            );
        }
        return false;
    }

    dataCliente(data) {
        this.Funcion = 1;
        this.IdCliente = data.IdClientes;
        this.Imagen = data.Email;
        document.getElementById("fotoCliente").innerHTML = ['<img class="responsive-img " src="', PATHNAME + "Resource/images/fotos/clientes/" + data.Email + ".png", '" title="', escape(data.Imagen), '"/>'].join('');
        document.getElementById("nombre").value = data.Nombre;
        document.getElementById("apellido").value = data.Apellido;
        document.getElementById("nid").value = data.NID;
        document.getElementById("telefono").value = data.Telefono;
        document.getElementById("email").value = data.Email;
        document.getElementById("direccion").value = data.Direccion;
        console.log(data);
        this.getCreditos(data.Creditos, 2);
    }

    getTickets(page) {
        $.post(
            URL + "Clientes/getTickets",
            {search: $("#searchTicket").val(), page: page},
            (response) => {
                console.log(response);
                try {
                    let item = JSON.parse(response);
                    $("#resultTicket").html(item.dataFilter);
                    $("#paginadorTicket").html(item.paginador);
                } catch (error) {
                    $("#paginadorTicket").html(item.paginador);
                }
            }
        );
    }

    exportarExcel(page, valor) {

        $.post(
            URL + "Clientes/exportarExcel",
            {search: $("#searchTicket").val(), page: page, valor: valor},
            (response) => {
                console.log(response);
            }
        );

    }

    restablecerClientes(funcion) {
        document.getElementById("fotoCliente").innerHTML = ['<img class="responsive-img " src="', PATHNAME + "Resource/images/fotos/clientes/default.png", '" title="', , '"/>'].join('');
        /*var instance = M.Modal.getInstance($('#modal1'));
        instance.close();*/
        switch (funcion) {
            case 1:
                window.location.href = URL + "Clientes/clientes";
                break;

            case 2:
                document.getElementById("nombre").value = "";
                document.getElementById("apellido").value = "";
                document.getElementById("nid").value = "";
                document.getElementById("telefono").value = "";
                document.getElementById("email").value = "";
                document.getElementById("direccion").value = "";
                this.getCreditos(null, 1);
                break;
        }

    }
}