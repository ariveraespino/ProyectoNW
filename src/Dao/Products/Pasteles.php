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
        $sqlstr = "SELECT * FROM pasteles WHERE tipo = :tipo;";
        return self::obtenerRegistros($sqlstr, ["tipo" => $tipo]);
    }

    public static function getPastelById(int $id)
    {
        $sqlstr = "SELECT * FROM pasteles WHERE pastel_id = :id;";
        return self::obtenerUnRegistro($sqlstr, ["id" => $id]);
    }

    public static function newPastel(string $nombre, string $tipo, string $descripcion, string $url_img, int $precio)
    {
        $sqlstr = "INSERT INTO pasteles (nombre, tipo, descripcion, url_img, precio) 
                   VALUES (:nombre, :tipo, :descripcion, :url_img, :precio);";
        return self::executeNonQuery(
            $sqlstr,
            [
                "nombre" => $nombre,
                "tipo" => $tipo,
                "descripcion" => $descripcion,
                "url_img" => $url_img,
                "precio" => $precio
            ]
        );
    }

    public static function updatePastel(int $id, string $nombre, string $tipo, string $descripcion, string $url_img, int $precio)
    {
        $sqlstr = "UPDATE pasteles 
                   SET nombre = :nombre, tipo = :tipo, descripcion = :descripcion, url_img = :url_img, precio = :precio 
                   WHERE pastel_id = :id;";
        return self::executeNonQuery(
            $sqlstr,
            [
                "nombre" => $nombre,
                "tipo" => $tipo,
                "descripcion" => $descripcion,
                "url_img" => $url_img,
                "precio" => $precio,
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