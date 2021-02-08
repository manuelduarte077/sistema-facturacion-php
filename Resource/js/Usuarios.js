class Usuarios extends Uploadpicture {
    constructor() {
        super();
        this.Funcion = 0;
        this.IdUsuario = 0;
        this.Imagen = null;
    }

    loginUser() {
        if (validarEmail(document.getElementById("email").value)) {
            $.post(
                "Index/userLogin",
                $('.login').serialize(),
                (response) => {
                    console.log(response);
                    if (response == 1) {
                        document.getElementById("password").focus();
                        document.getElementById("indexMessage").innerHTML = "Ingrese el password";
                    } else {
                        if (response == 2) {
                            document.getElementById("password").focus();
                            document.getElementById("indexMessage").innerHTML = "Introduzca al menos 6 caracteres";
                        } else {
                            try {
                                var item = JSON.parse(response);
                                if (0 < item.IdUsuario) {
                                    localStorage.setItem("user", response);
                                    window.location.href = URL + "Principal/principal";
                                } else {
                                    document.getElementById("indexMessage").innerHTML = "Correo o  contraseña incorrectos";
                                }
                            } catch (error) {
                                document.getElementById("indexMessage").innerHTML = response;
                            }
                        }
                    }
                }
            );

        } else {
            document.getElementById("email").focus();
            document.getElementById("indexMessage").innerHTML = 'Ingrese una dirección de correo electrónico válida';
            //M.toast({ html: 'Ingrese una dirección de correo electrónico válida', classes: 'rounded' });
        }
    }

    userData(URLactual) {
        if (PATHNAME == URLactual) {
            localStorage.removeItem("user");
            document.getElementById('menuNavbar1').style.display = 'none';
            document.getElementById('menuNavbar2').style.display = 'none';
        } else {
            if (null != localStorage.getItem("user")) {
                let user = JSON.parse(localStorage.getItem("user"));
                if (0 < user.IdUsuario) {
                    document.getElementById('menuNavbar1').style.display = 'block';
                    document.getElementById('menuNavbar2').style.display = 'block';
                    document.getElementById("name1").innerHTML = user.Nombre + " " + user.Apellido;
                    document.getElementById("role1").innerHTML = user.Roles;
                    document.getElementById("name2").innerHTML = user.Nombre + " " + user.Apellido;
                    document.getElementById("role2").innerHTML = user.Roles;
                    document.getElementById("fotoUser1").innerHTML = ['<img class=" responsive-img valign profile-image img" src="', URL + FOTOS + "usuarios/" + user.Imagen, '" title="', escape(user.Imagen), '"/>'].join('');
                    document.getElementById("fotoUser2").innerHTML = ['<img class=" responsive-img valign profile-image img" src="', URL + FOTOS + "usuarios/" + user.Imagen, '" title="', escape(user.Imagen), '"/>'].join('');
                }
            }
        }
    }

    getRoles(role, funcion) {
        let count = 1;
        $.post(
            URL + "Usuarios/getRoles",
            {},
            (response) => {
                try {
                    let item = JSON.parse(response);
                    console.log(item);
                    document.getElementById('roles').options[0] = new Option("Seleccione un role", 0);
                    if (0 < item.results.length) {
                        for (let i = 0; i < item.results.length; i++) {

                            switch (funcion) {
                                case 1:
                                    document.getElementById('roles').options[count] = new Option(item.results[i].Role, item.results[i].IdRole);
                                    count++;
                                    $('select').formSelect();
                                    break;

                                case 2:
                                    document.getElementById('roles').options[count] = new Option(item.results[i].Role, item.results[i].IdRole);
                                    if (item.results[i].Role == role) {
                                        i++;
                                        document.getElementById('roles').selectedIndex = i;
                                        i--;
                                    }
                                    count++;
                                    $('select').formSelect();
                                    break;
                            }
                        }
                    }
                } catch (error) {

                }


            }
        );
    }

    registerUser() {
        let valor = false;
        if (validarEmail(document.getElementById("email").value)) {
            var data = new FormData();
            $.each($('input[type=file]')[0].files, (i, file) => {
                data.append('file', file);
            });
            var url = this.Funcion == 0 ? "Usuarios/registerUser" : "Usuarios/editUser";
            let roles = document.getElementById("roles");
            let role = roles.options[roles.selectedIndex].text;
            data.append('idUsuario', this.IdUsuario);
            data.append('nombre', document.getElementById("nombre").value);
            data.append('apellido', document.getElementById("apellido").value);
            data.append('nid', document.getElementById("nid").value);
            data.append('telefono', document.getElementById("telefono").value);
            data.append('email', document.getElementById("email").value);
            data.append('password', document.getElementById("password").value);
            data.append('usuario', document.getElementById("usuario").value);
            data.append('role', role);
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
                        restablecerUser();
                    } else {
                        valor = true;
                        document.getElementById("registerMessage").innerHTML = response;
                    }
                }
            });
        } else {
            document.getElementById("email").focus();
            document.getElementById("registerMessage").innerHTML = "Ingrese una dirección de correo electrónico válida";
            valor = true;
        }
        return valor;
    }

    getUsers(valor, page) {
        var valor = valor != null ? valor : "";
        $.post(
            URL + "Usuarios/getUsers",
            {
                filter: valor,
                page: page
            },
            (response) => {
                try {
                    let item = JSON.parse(response);
                    $("#resultUser").html(item.dataFilter);
                    $("#paginador").html(item.paginador);
                    //console.log(item);
                } catch (error) {
                    $("#paginador").html(response);
                }
                console.log(response);
            });
    }

    editUser(data) {
        this.Funcion = 1;
        this.IdUsuario = data.IdUsuario;
        this.Imagen = data.Imagen;
        document.getElementById("fotos").innerHTML = ['<img class="responsive-img " src="', PATHNAME + "Resource/images/fotos/usuarios/" + data.Imagen, '" title="', escape(data.Imagen), '"/>'].join('');
        document.getElementById("nombre").value = data.Nombre;
        document.getElementById("apellido").value = data.Apellido;
        document.getElementById("nid").value = data.NID;
        document.getElementById("telefono").value = data.Telefono;
        document.getElementById("email").value = data.Email;
        document.getElementById("usuario").value = data.Usuario;
        document.getElementById("password").value = "********";
        document.getElementById("password").disabled = true;
        this.getRoles(data.Roles, 2);
    }

    deleteUser(data) {
        $.post(
            URL + "Usuarios/deleteUser",
            {
                idUsuario: data.IdUsuario,
                email: data.Email
            },
            (response) => {
                if (response == 0) {
                    this.restablecerUser();
                } else {
                    document.getElementById("deteUserMessage").innerHTML = response;
                }
                console.log(response);
            });
    }

    restablecerUser() {
        this.Funcion = 0;
        this.IdUsuario = 0;
        this.Imagen = null;
        document.getElementById("fotos").innerHTML = ['<img class="responsive-img " src="', PATHNAME + "Resource/images/fotos/usuarios/default.png", '" title="', , '"/>'].join('');
        this.getRoles(null, 1);
        var instance1 = M.Modal.getInstance($('#modal1'));
        instance1.close();
        var instance2 = M.Modal.getInstance($('#modal2'));
        instance2.close();
        document.getElementById("nombre").value = "";
        document.getElementById("apellido").value = "";
        document.getElementById("nid").value = "";
        document.getElementById("telefono").value = "";
        document.getElementById("email").value = "";
        document.getElementById("password").value = "";
        document.getElementById("usuario").value = "";
        document.getElementById("password").disabled = false;
        window.location.href = URL + "Usuarios/usuarios";
    }

    sessionClose() {
        localStorage.removeItem("user");
        document.getElementById('menuNavbar1').style.display = 'none';
        document.getElementById('menuNavbar2').style.display = 'none';
    }
}  