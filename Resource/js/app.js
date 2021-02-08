/*CODIGO DE USUARIOS */
var data_User = null;
var usuarios = new Usuarios();
var loginUser = () => {
    usuarios.loginUser();
}
var sessionClose = () => {
    usuarios.sessionClose();
}
var restablecerUser = () => {
    usuarios.restablecerUser();
}
var archivo = (evt) => {
    usuarios.archivo(evt, "fotos");
}
var getRoles = () =>{
    usuarios.getRoles(null, 1);
}
$(function () {
    $("#registerUser").click(function () {

        return usuarios.registerUser();
        
    });
    $("#registerClose").click(function () {
        usuarios.restablecerUser();
    });
    $("#deleteUser").click(function () {
        usuarios.deleteUser(data_User);
        data_User = null;
    });
});
var getUsers = (page) => {
    let valor = document.getElementById("filtrarUser").value;
    usuarios.getUsers(valor, page);
}
var dataUser = (data) => {
    usuarios.editUser(data);
}
var deleteUser = (data) => {
    document.getElementById("userName").innerHTML = data.Email;
    data_User = data;
}


/*PRINCIPA*/
var principal = new Principal();


/*CLIENTES*/
var pageTickets = 0;
var pageClientes = 0;
var cliente = new Clientes();
$(function () {
    $("#registerCliente").click(function () {
        return cliente.registerCliente();
    });
    $("#clienteClose").click(function () {
        cliente.restablecerClientes(1);
    });
});
var getCreditos = () =>{
    cliente.getCreditos(null,1);
}
var fotoCliente = (evt) => {
    cliente.archivo(evt, "fotoCliente");
}
var getClientes= (page) =>{
    pageClientes = page;
    cliente.getClientes(page);
}
var getTickets= (page) =>{
    pageTickets = page;
    cliente.getTickets(page);
}
var exportarTicketClientes = () =>{
    cliente.exportarExcel(pageTickets,1);
}
var exportarClientes = ()=>{
    cliente.exportarExcel(pageClientes,2);
}

/*PROVEEDORES*/
var pageTicketsp = 0;
var pageProveedor = 0;
var proveedores =  new Proveedores();

$(function () {
    $("#registerProveedor").click(function () {
        return proveedores.registerProveedores();
    });
   
});
var fotoProvedor = (evt) => {
    proveedores.archivo(evt, "fotoProveedor");
}
var getProveedores= (page) =>{
    pageProveedor = page;
    proveedores.getProveedores(page);
}
var dataProveedor = (email) =>{
    proveedores.dataProveedor(email);
}
var getPtickets= (page) =>{
    pageTicketsp = page;
    proveedores.getTickets(page);
}
var exportarTicketProveedores = () =>{
    proveedores.exportarExcel(pageTicketsp,1);
}
var exportarProveedores = ()=>{
    proveedores.exportarExcel(pageProveedor,2);
}
/*COMPRAS DE PRODUCTOS*/
var pageCompras = 0;
var compras =  new Compras();
var imageCompras = (evt) => {
    compras.archivo(evt, "imageCompras");
}
var getCompraProveedores= (page) =>{
    compras.getProveedores(page);
}
$(function () {
    $("#detallesCompras").click(function () {
        return compras.detallesCompras();
    });
    $("#comprar").click(function () {
        compras.Comprar();
    });
});
var getCompras = (page)=>{
    pageCompras = page;
    compras.getCompras(page);
}
var exportarCompras = ()=>{
    compras.exportarCompras(pageCompras);
}

/*REGISTROS DE PRODUCTOS*/
var pageInventario = 0;
var producto = new Productos();
var getCompraTempo = (page)=>{
    producto.getCompras(page);
}
var imageProductoDetalle = (evt) => {
    producto.archivo(evt, "productoDetalles");
}
var getInventarioProducto = (page)=>{
    pageInventario = page;
    producto.getInventario(page);
}
var exportarInventarios = ()=>{
    producto.exportarInventarios(pageInventario);
}
$().ready(() => {
    let URLactual = window.location.pathname;
    usuarios.userData(URLactual);
    principal.linkPrincipal(URLactual);
    $("#validate").validate();
    /*$('.sidenav').sidenav();
    $('.modal').modal();
    $('select').formSelect();*/
    M.AutoInit();

    console.log(URLactual);

});