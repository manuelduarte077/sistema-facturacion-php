class Principal {
    constructor() {

    }
    linkPrincipal(link) {
        let url = "";
        let cadena = link.split("/");
        for (let i = 0; i < cadena.length; i++) {
            if (i >= 3) {
                url += cadena[i];
            }

        }
        switch (url) {
            case "Principalprincipal":
                document.getElementById("enlace1").classList.add('active');
                break;
            case "Usuariosusuarios":
                document.getElementById("enlace2").classList.add('active');
                document.getElementById('files').addEventListener('change', archivo, false);
                document.getElementById("fotos").innerHTML = ['<img class="responsive-img " src="', PATHNAME + "Resource/images/fotos/usuarios/default.png", '" title="', , '"/>'].join('');
                getRoles();
                getUsers(1);
                break;
            case "Clientesclientes":
                document.getElementById("fotoCliente").innerHTML = ['<img class="responsive-img " src="', PATHNAME + "Resource/images/fotos/clientes/default.png", '" title="', , '"/>'].join('');
                getCreditos();
                document.getElementById('files').addEventListener('change', fotoCliente, false);
                getClientes(1);
                break;
            case "Clientesreportes":
                var email = getParameterByName('email');
                if (email != null) {
                    if (validarEmail(email)) {
                        new Clientes().getReporteCliente(email);
                    } else {
                        window.location.href = URL + "Clientes/clientes";
                    }

                } else {
                    window.location.href = URL + "Clientes/clientes";
                }
                break;
            case "Proveedoresproveedores":
                getProveedores(1);
                break;
            case "Proveedoresregistrar":
                var email = getParameterByName('email');
                if (email != null && email != "") {
                    dataProveedor(email);
                }
                document.getElementById('files').addEventListener('change', fotoProvedor, false);
                break;
            case "Proveedoresreportes":
                var email = getParameterByName('email');
                if (email != null) {
                    if (validarEmail(email)) {
                        new Proveedores().getReporteProveedor(email);
                    } else {
                        window.location.href = URL + "Proveedores/proveedores";
                    }

                } else {
                    window.location.href = URL + "Proveedores/proveedores";
                }
                break;
            case "Comprascompras":
                new Compras().getProveedores(1);
                document.getElementById('files').addEventListener('change', imageCompras, false);
                break;
            case "Comprasdetalles":
                new Compras().detalles();
                break;
            case "Comprasproductos":
                new Compras().getCompras(1);
                break;
            case "Productosproductos":
                new Productos().getCompras(1);
                break;
            case "Productosregistrar":
                var IdTemp = getParameterByName('IdTemp');
                if (IdTemp != null && IdTemp != "") {
                    new Productos().dataProducto(IdTemp);
                }
                break;
            case "Productosinventario":
                new Productos().getInventario(1);
                break;
            case "Productosdetalles":
                var Id = getParameterByName('Id');
                if (Id != null && Id != "") {
                    new Productos().detallesProducto(Id);
                }
                document.getElementById('files').addEventListener('change', imageProductoDetalle, false);
                break;
            case "Cajascajas":
                new Cajas().getCajas(1);
                document.getElementById("enlace3").classList.add('active');
                break;
            case "Ventasventas":
                new Ventas().GetProductos(1);

                break;
        }
    }
}