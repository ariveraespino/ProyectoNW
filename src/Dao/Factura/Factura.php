<?php

namespace Dao\Factura;

use Dao\Table;

class Factura extends Table
{
    /**
     *
     * @param string $orden_ID El ID de la orden de PayPal.
     * @param string $comprador El nombre del comprador.
     * @param string $estado El estado de la transacción.
     * @param float $total_bruto El monto total bruto de la compra.
     * @param int $usercod El ID del usuario.
     * @param string $email_comprador El email del comprador.
     * @return int El número de filas afectadas por la inserción.
     */
    public static function insertarFactura(string $orden_ID, string $nom_comprador, string $estado, float $total_bruto, int $usercod, string $email_comprador)
    {
        
        $insertSql = "INSERT INTO factura (orden_ID, nom_comprador, estado, total_bruto, usercod, email_comprador) VALUES (:orden_ID, :nom_comprador, :estado, :total_bruto, :usercod, :email_comprador)";
        
       
        $params = [
            "orden_ID" => $orden_ID,
            "nom_comprador" => $nom_comprador,
            "estado" => $estado,
            "total_bruto" => $total_bruto,
            "usercod" => $usercod,
            "email_comprador" => $email_comprador
        ];

    
        return self::executeNonQuery($insertSql, $params);
    }
    public static function getFacturas(): array
    {
        
        $sqlstr = "SELECT * FROM factura;";
        return self::obtenerRegistros($sqlstr, []);
    }

    public static function getFacturasbyID(string $iduser): array
    {
        
        $sqlstr = "SELECT * FROM factura WHERE usercod = :iduser;";
        return self::obtenerRegistros($sqlstr, ["iduser"=>$iduser]);
    }
}   
