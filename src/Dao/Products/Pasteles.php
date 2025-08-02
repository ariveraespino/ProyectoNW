<?php

namespace Dao\Products;

use Dao\Table;

class Pasteles extends Table
{
    public static function getPasteles(): array
    {
        $sqlstr = "SELECT * FROM pasteles;";
        return self::obtenerRegistros(
            $sqlstr,
            []
        );
    }

  public static function getPastelesByTipo(string $tipo): array
{
    $sqlstr = "SELECT * FROM pasteles WHERE tipo = :tipo AND estado_pastel = 'ACT' AND cantidad >0 ;";
    return self::obtenerRegistros($sqlstr, ["tipo" => $tipo]);
}

    public static function getPastelById(int $id)
    {
        $sqlstr = "SELECT * FROM pasteles WHERE pastel_id = :id;";
        return self::obtenerUnRegistro($sqlstr, ["id" => $id]);
    }

    public static function newPastel(
        string $nombre,
        string $tipo,
        string $descripcion,
        string $url_img,
        int $precio,
        int $cantidad,
        string $estado_pastel
    ) {
        $sqlstr = "INSERT INTO pasteles (nombre, tipo, descripcion, url_img, precio, cantidad, estado_pastel) 
                   VALUES (:nombre, :tipo, :descripcion, :url_img, :precio, :cantidad, :estado_pastel);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "nombre" => $nombre,
                "tipo" => $tipo,
                "descripcion" => $descripcion,
                "url_img" => $url_img,
                "precio" => $precio,
                "cantidad" => $cantidad,
                "estado_pastel" => $estado_pastel
            ]
        );
    }

    public static function updatePastel(
        int $id,
        string $nombre,
        string $tipo,
        string $descripcion,
        string $url_img,
        int $precio,
        int $cantidad,
        string $estado_pastel
    ) {
        $sqlstr = "UPDATE pasteles 
                   SET nombre = :nombre, tipo = :tipo, descripcion = :descripcion, url_img = :url_img, 
                       precio = :precio, cantidad = :cantidad, estado_pastel = :estado_pastel 
                   WHERE pastel_id = :id;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "nombre" => $nombre,
                "tipo" => $tipo,
                "descripcion" => $descripcion,
                "url_img" => $url_img,
                "precio" => $precio,
                "cantidad" => $cantidad,
                "estado_pastel" => $estado_pastel,
                "id" => $id
            ]
        );
    }

    public static function deletePastel(int $id)
    {
        $sqlstr = "DELETE FROM pasteles WHERE pastel_id = :id;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "id" => $id
            ]
        );
    }
}