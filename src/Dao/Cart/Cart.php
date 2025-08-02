<?php

namespace Dao\Cart;

class Cart extends \Dao\Table
{
    public static function getProductosDisponibles()
    {
        $sqlAllProductosActivos = "SELECT * from pasteles;";
        $productosDisponibles = self::obtenerRegistros($sqlAllProductosActivos, array());

        
        $deltaAutorizada = \Utilities\Cart\CartFns::getAuthTimeDelta();
        $sqlCarretillaAutorizada = "select pastel_id, sum(crrctd) as reserved
            from carretilla where TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by pastel_id;";
        $prodsCarretillaAutorizada = self::obtenerRegistros(
            $sqlCarretillaAutorizada,
            array("delta" => $deltaAutorizada)
        );
        
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

       
        $deltaAutorizada = \Utilities\Cart\CartFns::getAuthTimeDelta();
        $sqlCarretillaAutorizada = "select pastel_id, sum(crrctd) as reserved
            from carretilla where pastel_id=:pastel_id and TIME_TO_SEC(TIMEDIFF(now(), crrfching)) <= :delta
            group by pastel_id;";
        $prodsCarretillaAutorizada = self::obtenerRegistros(
            $sqlCarretillaAutorizada,
            array("pastel_id" => $pastel_id, "delta" => $deltaAutorizada)
        );
    
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
public static function removeFromAnonCart(int $pastel_id, string $anonCod)
{
    // Primero, obtener la cantidad actual
    $sqlGet = "SELECT crrctd FROM carretillaanon WHERE pastel_id = :pastel_id AND anoncod = :anoncod;";
    $resultado = self::obtenerUnRegistro($sqlGet, ["pastel_id" => $pastel_id, "anoncod" => $anonCod]);

    if (!$resultado) {
        // No existe el registro, nada que hacer
        return false;
    }

    $cantidadActual = (int)$resultado["crrctd"];
    if ($cantidadActual <= 1) {
        // Si queda 0 o menos, eliminar el registro
        $sqlDelete = "DELETE FROM carretillaanon WHERE pastel_id = :pastel_id AND anoncod = :anoncod;";
        return self::executeNonQuery($sqlDelete, ["pastel_id" => $pastel_id, "anoncod" => $anonCod]);
    } else {
        // Si queda más de 1, restar 1 a la cantidad
        $sqlUpdate = "UPDATE carretillaanon SET crrctd = crrctd - 1 WHERE pastel_id = :pastel_id AND anoncod = :anoncod;";
        return self::executeNonQuery($sqlUpdate, ["pastel_id" => $pastel_id, "anoncod" => $anonCod]);
    }
}

public static function addToAnonCartCantidad(int $pastel_id, string $anonCod)
    {
        $params = ["anoncod" => $anonCod, "pastel_id" => $pastel_id];

        // Verifica si el pastel ya está en el carrito anónimo
        $sqlGet = "SELECT 1 FROM carretillaanon WHERE anoncod = :anoncod AND pastel_id = :pastel_id;";
        $existe = self::obtenerUnRegistro($sqlGet, $params);

        if ($existe) {
            // Si ya está, aumenta la cantidad en 1
            $sqlUpdate = "UPDATE carretillaanon
                          SET crrctd = crrctd + 1, crrfching = NOW()
                          WHERE anoncod = :anoncod AND pastel_id = :pastel_id;";
            return self::executeNonQuery($sqlUpdate, $params);
        } else {
            // Si no está, inserta uno con cantidad 1 y precio actual del pastel
            $sqlInsert = "INSERT INTO carretillaanon (anoncod, pastel_id, crrctd, crrprc, crrfching)
                          SELECT :anoncod, p.pastel_id, 1, p.precio, NOW()
                          FROM pasteles p
                          WHERE p.pastel_id = :pastel_id;";
            return self::executeNonQuery($sqlInsert, $params);
        }
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

    public static function aumentarCantidad(int $pastel_id)
{
    $updateSql = "UPDATE pasteles SET cantidad = cantidad + 1 WHERE pastel_id = :pastel_id";
    return self::executeNonQuery($updateSql, ["pastel_id" => $pastel_id]);
}
public static function addToCart(int $pastel_id, int $usercod)
{
    
    $sqlGet = "SELECT crrctd FROM carretilla WHERE pastel_id = :pastel_id AND usercod = :usercod;";
    $registro = self::obtenerUnRegistro($sqlGet, [
        "pastel_id" => $pastel_id,
        "usercod" => $usercod
    ]);

    if ($registro) {
       
        $sqlUpdate = "UPDATE carretilla SET crrctd = crrctd + 1 WHERE pastel_id = :pastel_id AND usercod = :usercod;";
        return self::executeNonQuery($sqlUpdate, [
            "pastel_id" => $pastel_id,
            "usercod" => $usercod
        ]);
    }


    return 0;
}
public static function removeFromCart(int $pastel_id, int $usercod)
{
   
    $sqlGet = "SELECT crrctd FROM carretilla WHERE pastel_id = :pastel_id AND usercod = :usercod;";
    $registro = self::obtenerUnRegistro($sqlGet, [
        "pastel_id" => $pastel_id,
        "usercod" => $usercod
    ]);

    if ($registro) {
        $cantidad = intval($registro["crrctd"]);
        if ($cantidad > 1) {
        
            $sqlUpdate = "UPDATE carretilla SET crrctd = crrctd - 1 WHERE pastel_id = :pastel_id AND usercod = :usercod;";
            return self::executeNonQuery($sqlUpdate, [
                "pastel_id" => $pastel_id,
                "usercod" => $usercod
            ]);
        } else {
           
            $sqlDelete = "DELETE FROM carretilla WHERE pastel_id = :pastel_id AND usercod = :usercod;";
            return self::executeNonQuery($sqlDelete, [
                "pastel_id" => $pastel_id,
                "usercod" => $usercod
            ]);
        }
    }
    return 0;
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
   public static function deleteFromCart(int $pastel_id, string $anonCod)
{
    
    $sqlGetCantidad = "SELECT crrctd FROM carretilla WHERE pastel_id = :pastel_id AND usercod = :usercod;";
    $result = self::obtenerUnRegistro($sqlGetCantidad, [
        "pastel_id" => $pastel_id,
        "usercod" => $anonCod
    ]);

    if (!$result) {
        return false; 
    }

    $cantidad = intval($result["crrctd"]);

    $sqlUpdateStock = "UPDATE pasteles SET cantidad = cantidad + :cantidad WHERE pastel_id = :pastel_id;";
    self::executeNonQuery($sqlUpdateStock, [
        "cantidad" => $cantidad,
        "pastel_id" => $pastel_id
    ]);

   
    $sqlDelete = "DELETE FROM carretilla WHERE pastel_id = :pastel_id AND usercod = :usercod;";
    return self::executeNonQuery($sqlDelete, [
        "pastel_id" => $pastel_id,
        "usercod" => $anonCod
    ]);
}

public static function deleteFromAnonCart(int $pastel_id, string $anonCod)
{

    $sqlGetCantidad = "SELECT crrctd FROM carretillaanon WHERE pastel_id = :pastel_id AND anoncod = :anoncod;";
    $result = self::obtenerUnRegistro($sqlGetCantidad, [
        "pastel_id" => $pastel_id,
        "anoncod" => $anonCod
    ]);

    if (!$result) {
        return false; 
    }

    $cantidad = intval($result["crrctd"]);

 
    $sqlUpdateStock = "UPDATE pasteles SET cantidad = cantidad + :cantidad WHERE pastel_id = :pastel_id;";
    self::executeNonQuery($sqlUpdateStock, [
        "cantidad" => $cantidad,
        "pastel_id" => $pastel_id
    ]);


    $sqlDelete = "DELETE FROM carretillaanon WHERE pastel_id = :pastel_id AND anoncod = :anoncod;";
    return self::executeNonQuery($sqlDelete, [
        "pastel_id" => $pastel_id,
        "anoncod" => $anonCod
    ]);
}
   
  


}
