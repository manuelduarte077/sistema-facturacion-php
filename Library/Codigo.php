<?php

class Codigo
{
    public static function Ticket($db, $table)
    {
        $count = 0;
        $numTicket = 0;

        do {
            $arrayTicket = range(1, rand(1, 10));
            foreach ($arrayTicket as $val) {
                $numTicket .= $val;
            }
            $where = " WHERE Ticket = :Ticket";
            $response = $db->select1("Ticket", $table, $where, array('Ticket' => (string)$numTicket));
            if (is_array($response)) {
                $response = $response['results'];
                if (0 < count($response)) {
                    $count = count($response);
                } else {
                    $count = 0;
                    return $numTicket;
                }
            } else {
                $count = 0;
                return $response;
            }
        } while (0 < $count);
    }

    public static function Tickets($db, $table, $propietario, $email)
    {
        $codigo = null;
        $numTicket = null;
        switch ($table) {
            case 'ticket':
                $where = " WHERE Propietario = :Propietario AND Email = :Email";
                $array = array(
                    'Propietario' => $propietario,
                    'Email' => $email
                );
                $response = $db->select1("Ticket", $table, $where, $array);
                if (is_array($response)) {
                    $response = $response['results'];
                    if (0 == count($response)) {
                        return $numTicket = "0000000001";
                    } else {
                        $data = end($response);
                        if ("9999999999" == $data["Ticket"]) {
                            return $numTicket = "0000000001";
                        } else {
                            $cod = (int)$data["Ticket"];
                            $cod++;
                            return $numTicket = str_pad($cod, 10, "0", STR_PAD_LEFT);
                        }

                    }

                } else {
                    return $response;
                }
                break;

            case 'compras':
                $where = " WHERE Year = :Year AND Email = :Email";
                $array = array(
                    'Year' => date("Y"),
                    'Email' => $email
                );
                $response = $db->select1("Codigo", $table, $where, $array);
                if (is_array($response)) {
                    $response = $response['results'];
                    if (0 == count($response)) {
                        return $numTicket = "0000000001";
                    } else {
                        $data = end($response);
                        if ("9999999999" == $data["Codigo"]) {
                            return $numTicket = "0000000001";
                        } else {
                            $cod = (int)$data["Codigo"];
                            $cod++;
                            return $numTicket = str_pad($cod, 10, "0", STR_PAD_LEFT);
                        }

                    }

                } else {
                    return $response;
                }
                break;
        }

    }

    public static function getCodeBarra($db, $table)
    {
        $count = 0;
        $numCodigo = 0;

        do {
            $arrayCodigo = range(1, rand(1, 10));
            foreach ($arrayCodigo as $val) {
                $numCodigo .= $val;
            }
            $where = " WHERE Codigo = :Codigo";
            $response = $db->select1("Codigo", $table, $where, array('Codigo' => (string)$numCodigo));
            if (is_array($response)) {
                $response = $response['results'];
                if (0 < count($response)) {
                    $count = count($response);
                } else {
                    $count = 0;
                    return $numCodigo;
                }
            } else {
                $count = 0;
                return $response;
            }
        } while (0 < $count);
    }
}


?>