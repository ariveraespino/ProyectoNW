<?php

namespace Dao\Cart;

class Cart extends \Dao\Table
{
    public static function getProductosDisponibles()
    {
        $sqlAllProductosActivos = "SELECT * from pasteles;";
        $productosDisponibles = self::obtenerRegistros($sqlAllProductosActivos, array());

        //Sacar el stock de productos con carretilla autorizada
        $deltaAutorizada = \Utilities\Cart\CartFns::getAuthTimeDelta();
        $sqlCarretillaAutorizada = "select pastel_id, sum(crrctd) as reserved
            from carretilla where TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by pastel_id;";
        $prodsCarretillaAutorizada = self::obtenerRegistros(
            $sqlCarretillaAutorizada,
            array("delta" => $deltaAutorizada)
        );
        //Sacar el stock de productos con carretilla no autorizada
        $deltaNAutorizada = \Utilities\Cart\CartFns::getUnAuthTimeDelta();
        $sqlCarretillaNAutorizada = "select pastel_id, sum(crrctd) as reserved
            from carretillaanon where TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by pastel_id;";
        $prodsCarretillaNAutorizada = self::obtenerRegistros(
            $sqlCarretillaNAutorizada,
            array("delta" => $deltaNAutorizada)
        );
        $productosCurados = array();
        foreach ($productosDisponibles as $producto) {
            if (!isset($productosCurados[$producto["pastel_id"]])) {
                $productosCurados[$producto["pastel_id"]] = $producto;
            }
        }
        foreach ($prodsCarretillaAutorizada as $producto) {
            if (isset($productosCurados[$producto["pastel_id"]])) {
                $productosCurados[$producto["pastel_id"]]["cantidad"] -= $producto["reserved"];
            }
        }
        foreach ($prodsCarretillaNAutorizada as $producto) {
            if (isset($productosCurados[$producto["pastel_id"]])) {
                $productosCurados[$producto["pastel_id"]]["cantidad"] -= $producto["reserved"];
            }
        }
        $productosDisponibles = null;
        $prodsCarretillaAutorizada = null;
        $prodsCarretillaNAutorizada = null;

        
        return $productosCurados;
    }

    public static function getProductoDisponible($pastel_id)
    {
        $sqlAllProductosActivos = "SELECT * from pasteles where pastel_id=:pastel_id;";
        $productosDisponibles = self::obtenerRegistros($sqlAllProductosActivos, array("pastel_id" => $pastel_id));

        //Sacar el stock de productos con carretilla autorizada
        $deltaAutorizada = \Utilities\Cart\CartFns::getAuthTimeDelta();
        $sqlCarretillaAutorizada = "select pastel_id, sum(crrctd) as reserved
            from carretilla where pastel_id=:pastel_id and TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by pastel_id;";
        $prodsCarretillaAutorizada = self::obtenerRegistros(
            $sqlCarretillaAutorizada,
            array("pastel_id" => $pastel_id, "delta" => $deltaAutorizada)
        );
        //Sacar el stock de productos con carretilla no autorizada
        $deltaNAutorizada = \Utilities\Cart\CartFns::getUnAuthTimeDelta();
        $sqlCarretillaNAutorizada = "select pastel_id, sum(crrctd) as reserved
            from carretillaanon where pastel_id = :pastel_id and TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by pastel_id;";
        $prodsCarretillaNAutorizada = self::obtenerRegistros(
            $sqlCarretillaNAutorizada,
            array("pastel_id" => $pastel_id, "delta" => $deltaNAutorizada)
        );
        $productosCurados = array();
        foreach ($productosDisponibles as $producto) {
            if (!isset($productosCurados[$producto["pastel_id"]])) {
                $productosCurados[$producto["pastel_id"]] = $producto;
            }
        }
        foreach ($prodsCarretillaAutorizada as $producto) {
            if (isset($productosCurados[$producto["pastel_id"]])) {
                $productosCurados[$producto["pastel_id"]]["cantidad"] -= $producto["reserved"];
            }
        }
        foreach ($prodsCarretillaNAutorizada as $producto) {
            if (isset($productosCurados[$producto["pastel_id"]])) {
                $productosCurados[$producto["pastel_id"]]["cantidad"] -= $producto["reserved"];
            }
        }
        $productosDisponibles = null;
        $prodsCarretillaAutorizada = null;
        $prodsCarretillaNAutorizada = null;
        return $productosCurados[$pastel_id];
    }

    public static function addToAnonCart(int $pastel_id, string $anonCod, int $cantidad, float $precio)
    {
        $validateSql = "SELECT * from carretillaanon where anoncod = :anoncod and pastel_id = :pastel_id";
        $pastel = self::obtenerRegistros($validateSql, ["anoncod"=>$anonCod, "pastel_id"=>$pastel_id]);
        if($pastel){
            $updateSql = "UPDATE carretillaanon SET crrctd = crrctd + 1 WHERE anoncod = :anoncod and pastel_id = :pastel_id";
            self::restarCantidad($pastel_id);
            return self::executeNonQuery($updateSql, ["anoncod"=>$anonCod, "pastel_id"=>$pastel_id]);
        } else {
            self::restarCantidad($pastel_id);
        return self::executeNonQuery(
            "INSERT INTO carretillaanon (anoncod, pastel_id, crrctd, crrprc, crrfching) VALUES (:anoncod, :pastel_id, :crrctd, :crrprc, NOW());",
            ["anoncod" => $anonCod, "pastel_id" => $pastel_id, "crrctd" => $cantidad, "crrprc" => $precio]
        );
        }
    }

    public static function restarCantidad(int $pastel_id){
        $updateSql = "UPDATE pasteles SET cantidad = cantidad - 1 WHERE pastel_id = :pastel_id";
        return self::executeNonQuery($updateSql, ["pastel_id"=>$pastel_id]);
    }

    public static function getAnonCart(string $anonCod)
    {
        return self::obtenerRegistros("SELECT a.*, b.crrctd, b.crrprc, b.crrfching from pasteles a inner join carretillaanon b on a.pastel_id = b.pastel_id where b.anoncod=:anoncod;", ["anoncod"=>$anonCod]);
    }

    public static function getAuthCart(string $usercod)
    {
        return self::obtenerRegistros("SELECT a.*, b.crrctd, b.crrprc, b.crrfching from pasteles a inner join carretilla b on a.pastel_id = b.pastel_id where b.usercod=:usercod;", ["usercod"=>$usercod]);
    }

    public static function addToAuthCart(int $pastel_id, string $usercod, int $cantidad, float $precio)
    {
        $validateSql = "SELECT * from carretilla where usercod = :usercod and pastel_id = :pastel_id";
        $pastel = self::obtenerRegistros($validateSql, ["usercod"=>$usercod, "pastel_id"=>$pastel_id]);
        if($pastel){
            self::restarCantidad($pastel_id);
            $updateSql = "UPDATE carretilla SET crrctd = crrctd + 1 WHERE usercod = :usercod and pastel_id = :pastel_id";
            return self::executeNonQuery($updateSql, ["usercod"=>$usercod, "pastel_id"=>$pastel_id]);
        } else {
            self::restarCantidad($pastel_id);
        return self::executeNonQuery(
            "INSERT INTO carretilla (usercod, pastel_id, crrctd, crrprc, crrfching) VALUES (:usercod, :pastel_id, :crrctd, :crrprc, NOW());",
            ["usercod" => $usercod, "pastel_id" => $pastel_id, "crrctd" => $cantidad, "crrprc" => $precio]
        );
        }
    }

    public static function moveAnonToAuth( string $anonCod, int $usercod){
        $sqlstr = "INSERT INTO carretilla (usercod, pastel_id, crrctd, crrprc, crrfching) 
        SELECT :usercod, pastel_id, crrctd, crrprc, NOW() FROM carretillaanon WHERE anoncod = :anoncod
        ON DUPLICATE KEY UPDATE carretilla.crrctd = carretilla.crrctd + carretillaanon.crrctd;";

        $deleteSql = "DELETE FROM carretillaanon WHERE anoncod = :anoncod;";

        
        self::executeNonQuery($sqlstr, ["anoncod" => $anonCod, "usercod" => $usercod]);
        self::executeNonQuery($deleteSql, ["anoncod" => $anonCod]);
    }

    //Funcion para borrar la carretilla cuando el usuario complete la compra
    public static function deleteAuthCart(int $usercod){
        $deleteSql = "DELETE from carretilla WHERE usercod = :usercod;";
        self::executeNonQuery($deleteSql, ["usercod" => $usercod]);
        echo 'Se borro la carretilla';
    }

    public static function getProducto($pastel_id)
    {
        $sqlAllProductosActivos = "SELECT * from pasteles where pastel_id=:pastel_id;";
        $productosDisponibles = self::obtenerRegistros($sqlAllProductosActivos, array("pastel_id" => $pastel_id));
        return $productosDisponibles;
    }
}
