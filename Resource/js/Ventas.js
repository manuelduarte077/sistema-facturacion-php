class Ventas {

    SearchProducto() {
        $.post(
            URL + "Ventas/SearchProducto",
            {searchCode: $("#searchCode").val()},
            (response) => {
                console.log(response);
                if (response == 0) {
                    this.GetProductos(1);
                }
            }
        );
    }

    GetProductos(page) {
        $.post(
            URL + "Ventas/GetProductos",
            {page: page},
            (response) => {
                try {
                    let item = JSON.parse(response);
                    $("#vtMonto").html(numberDecimales(item.productos["importe"]));
                    $("#vtImporte").val(item.productos["importe"]);
                    $("#tempoVenta").html(item.dataFilter);
                    $("#tempoVentaPD").html(item.paginador);
                } catch (error) {
                    $("#tempoVentaPD").html(response);
                }
                console.log(response);

            }
        );
    }

    pagos(evt, input) {
        if (evt != null) {
            if (filterFloat(evt, input)) {
                var importe = parseFloat($("#vtImporte").val());
                var key = window.Event ? evt.which : evt.keyCode;
                var chark = String.fromCharCode(key);
                var tempValue = input.value + chark;
                var pago = parseFloat(tempValue);
                var deuda = importe - pago;
                $("#vtDeuda").text("$" + numberDecimales(deuda));
                if (pago > importe) {
                    var cambio = pago - importe;
                    $("#vtCambio").text("$" + numberDecimales(cambio));
                    $("#vtDeuda").text("$0.0000000");
                    $("#tvCheckbox").prop("disabled", false);
                    $("#tvCheckbox").attr("checked", false);
                    $("#textNid").val("")
                    $("#textNid").prop("disabled", "disabled");
                } else {
                    $("#vtCambio").text("$0.0000000");
                    $("#tvCheckbox").prop("disabled", false);
                    $("#textNid").removeAttr("disabled");
                }
                return true;
            } else {
                return false;
            }
        } else {
            $("#vtDeuda").text("$0.0000000");
        }
    }
}