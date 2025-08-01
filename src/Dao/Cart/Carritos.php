<?php

namespace Dao\Cart;

use Dao\Table;

class Carritos extends Table
{
    public static function getCarrito(): array
    {
        if (isset($_SESSION["usercod"])) {
            return self::getAuthCart($_SESSION["usercod"]);
        } elseif (isset($_SESSION["anoncod"])) {
            return self::getAnonCart($_SESSION["anoncod"]);
        } else {
            return [];
    }
    }

    public function run(): void
    {
        $this->viewData["carrito"] = getCarrito();
        Renderer::render("maintenance/products/carrito", $this->viewData);
        
    }

    
}