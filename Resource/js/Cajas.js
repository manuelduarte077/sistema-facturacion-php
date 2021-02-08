class Cajas {
    constructor() {

    }

    setCaja() {
        $.post(
            URL + "Cajas/setCaja",
            {caja: $("#caja").val()},
            (response) => {
                if (response == 0) {

                } else {
                    $("#mensajeCaja").html(response);
                }
            }
        );
    }

    getCajas(page) {
        $.post(
            URL + "Cajas/getCajas",
            {page: page},
            (response) => {
                console.log(response);
                try {
                    let item = JSON.parse(response);
                    $("#dataCajaFilter").html(item.dataFilter);
                    $("#paginadorCaja").html(item.paginador);
                } catch (error) {
                    $("#paginadorCaja").html(response);
                }
            }
        );
    }

    updateState(idcaja, state) {
        $.post(
            URL + "Cajas/updateState",
            {idcaja: idcaja, state: state},
            (response) => {
                console.log(response);
                this.getCajas(1);
            }
        );
    }
}