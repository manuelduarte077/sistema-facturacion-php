<?php
declare (strict_types = 1);
class Anonymous 
{
    public function userClass($array){
        return new class($array)
        {
            public $NID;
            public $Nombre;
            public $Apellido;
            public $Email;
            public $Password;
            public $Telefono;
            public $Usuario;
            public $Roles;
            public $Imagen;
            function __construct($array){
                $this->NID = $array[0];
                $this->Nombre = $array[1];
                $this->Apellido = $array[2];
                $this->Telefono = $array[3];
                $this->Email = $array[4];
                $this->Password = $array[5];
                $this->Usuario = $array[6];
                $this->Roles = $array[7];
                $this->Imagen = $array[8];
            }
        };
        
    }
    public function clientesClass(array $array){
        return new class($array){
            var $NID;
            var $Nombre;
            var $Apellido;
            var $Email;
            var $Direccion;
            var $Telefono;
            var $Creditos;
            function __construct($array){
                $this->NID = $array[0];
                $this->Nombre = $array[1];
                $this->Apellido = $array[2];
                if(is_numeric($array[3])){
                    $this->Telefono = $array[3];
                }
                $this->Email = $array[4];
                $this->Direccion = $array[5];
                $this->Creditos = $array[6];
            }
        };
    }
    public function reportClientesClass(array $array){
        return new class($array){
            var $Deuda;
            var $FechaDeuda;
            var $Pago;
            var $FechaPago;
            var $Ticket;
            var $IdClientes;
            function __construct($array){
                $this->Deuda = $array[0];
                $this->FechaDeuda = $array[1];
                $this->Pago = $array[2];
                $this->FechaPago = $array[3];
                $this->Ticket = $array[4];
                $this->IdClientes = $array[5];
            }
        };
    }
    public function ticketClass(array $array){
        return new class($array){
            var $Propietario;
            var $Deuda;
            var $FechaDeuda;
            var $Pago;
            var $FechaPago;
            var $Ticket;
            var $Email;
            function __construct($array){
                $this->Propietario = $array[0];
                $this->Deuda = $array[1];
                $this->FechaDeuda = $array[2];
                $this->Pago = $array[3];
                $this->FechaPago = $array[4];
                $this->Ticket = $array[5];
                $this->Email = $array[6];
            }
        };
    }
    public function proveedoresClass(array $array){
        return new class($array){
            var $Proveedor;
            var $Telefono;
            var $Email;
            var $Direccion;
           
            function __construct($array){
                $this->Proveedor = $array[0];
                $this->Telefono = $array[1];
                $this->Email = $array[2];
                $this->Direccion = $array[3];
            }
        };
    }
    public function reportProveedores(array $array){
        return new class($array){
            var $Deuda;
            var $FechaDeuda;
            var $Pago;
            var $FechaPago;
            var $Ticket;
            var $IdProveedor;
            function __construct($array){
                $this->Deuda = $array[0];
                $this->FechaDeuda = $array[1];
                $this->Pago = $array[2];
                $this->FechaPago = $array[3];
                $this->Ticket = $array[4];
                $this->IdProveedor = $array[5];
            }
        };
    }
    public function TCompras(array $array){
        return new class($array){
            var $Descripcion;
            var $Cantidad;
            var $Precio;
            var $Importe;
            var $IdProveedor;
            var $Proveedor;
            var $Email;
            var $IdUsuario;
            var $Usuario;
            var $Role;
            var $Dia;
            var $Mes;
            var $Year;
            var $Fecha;
            var $Codigo;
            var $Credito;
            function __construct($array){
                if(0 < count($array)){
                    $this->Descripcion = $array[0];
                    $this->Cantidad = $array[1];
                    $this->Precio = $array[2];
                    $this->Importe = $array[3];
                    $this->IdProveedor = $array[4];
                    $this->Proveedor = $array[5];
                    $this->Email = $array[6];
                    $this->IdUsuario = $array[7];
                    $this->Usuario = $array[8];
                    $this->Role = $array[9];
                    $this->Dia = $array[10];
                    $this->Mes = $array[11];
                    $this->Year = $array[12];
                    $this->Fecha = $array[13];
                    $this->Codigo = $array[14];
                    $this->Credito = $array[15];
                }
            }
        };
    }
    public function TCompras_temp(array $array){
        return new class($array){
           // public $ID;
            public $Descripcion;
            public $Cantidad;
            public $Precio;
            public $Importe;
            public $IdProveedor;
            public $Proveedor;
            public $Email;
            public $Credito;
            public $Fecha;
            public $Codigo;
            function __construct($array){
                //$this->ID = $array[0];
                $this->Descripcion = $array[0];
                $this->Cantidad = $array[1];
                $this->Precio = $array[2];
                $this->Importe = $array[3];
                $this->IdProveedor = $array[4];
                $this->Proveedor = $array[5];
                $this->Email = $array[6];
                $this->Credito = $array[7];
                $this->Fecha = $array[8];
                $this->Codigo = $array[9];
            }
        };
    }
    public function TProductos(array $array){
        return new class($array){
            public $Codigo;
            public $Descripcion;
            public $Precio;
            public $Departamento;
            public $Catgoria;
            public $Descuento;
            public $Dia;
            public $Mes;
            public $Year;
            public $Fecha;
            public $Compra;
            public $Image;
            function __construct($array){
                $this->Codigo = $array[0];
                $this->Descripcion = $array[1];
                $this->Precio = $array[2];
                $this->Departamento = $array[3];
                $this->Catgoria = $array[4];
                $this->Descuento = $array[5];
                $this->Dia = $array[6];
                $this->Mes = $array[7];
                $this->Year = $array[8];
                $this->Fecha = $array[9];
                $this->Compra = $array[10];
                $this->Image = $array[11];
            }
        };
    }
    public function TBodega(array $array){
        return new class($array){
            public $Codigo;
            public $Existencia;
            public $Dia;
            public $Mes;
            public $Year;
            public $Fecha;
            function __construct($array){
                $this->Codigo = $array[0];
                $this->Existencia = $array[1];
                $this->Dia = $array[2];
                $this->Mes = $array[3];
                $this->Year = $array[4];
                $this->Fecha = $array[5];
            }
        };
    }
    public function TCajas(array $array){
        return new class($array){
            public $Caja;
            public $Estado;
            public $Asignada;
            public $Usuario;
            function __construct($array){
                if(0 < count($array)){
                    $this->Caja = $array[0];
                    $this->Estado = $array[1];
                    $this->Asignada = $array[2];
                    $this->Usuario = $array[3];
                } 
            }
        };
    }
    public function TCajas_registros(array $array){
        return new class($array){
            public $IdUsuario;
            public $Nombre;
            public $Apellido;
            public $Usuario;
            public $Role;
            public $IdCaja;
            public $Caja;
            public $Estado;
            public $Hora;
            public $Dia;
            public $Mes;
            public $Year;
            public $Fecha;
            function __construct($array){
                if(0 < count($array)){
                    $this->IdUsuario = $array[0];
                    $this->Nombre = $array[1];
                    $this->Apellido = $array[2];
                    $this->Usuario = $array[3];
                    $this->Role = $array[4];
                    $this->IdCaja = $array[5];
                    $this->Caja = $array[6];
                    $this->Estado = $array[7];
                    $this->Hora = $array[8];
                    $this->Dia = $array[9];
                    $this->Mes = $array[10];
                    $this->Year = $array[11];
                    $this->Fecha = $array[12];
                }
            }
        };
    }
    public function TTempo_ventas(array $array){
        return new class($array){
            public $Codigo;
            public $Descripcion;
            public $Precio;
            public $Cantidad;
            public $Importe;
            public $Caja;
            public $IdUsuario;
            function __construct($array){
                if(0 < count($array)){
                    $this->Codigo = $array[0];
                    $this->Descripcion = $array[1];
                    $this->Precio = $array[2];
                    $this->Cantidad = $array[3];
                    $this->Importe = $array[4];
                    $this->Caja = $array[5];
                    $this->IdUsuario = $array[6];
                }
            }
        };
    }
    public function TVentas(array $array){
        return new class($array){
            public $Codigo;
            public $Descripcion;
            public $Precio;
            public $Cantidad;
            public $Importe;
            public $Dia;
            public $Mes;
            public $Year;
            public $Fecha;
            public $Caja;
            public $IdUsuario;
            function __construct($array){
                if(0 < count($array)){
                    $this->Codigo = $array[0];
                    $this->Descripcion = $array[1];
                    $this->Precio = $array[2];
                    $this->Cantidad = $array[3];
                    $this->Importe = $array[4];
                    $this->Dia = $array[5];
                    $this->Mes = $array[6];
                    $this->Year = $array[7];
                    $this->Fecha = $array[8];
                    $this->Caja = $array[9];
                    $this->IdUsuario = $array[10];
                }
            }
        };
    }
}

?>