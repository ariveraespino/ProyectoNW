<?php

namespace Controllers\Maintenance\Cart;

use Controllers\PublicController;
use Views\Renderer;
use Dao\Cart\Cart as CartDao;
use Utilities\Site;

class Carrito extends PublicController 
{
    private array $viewData;

    public static function getCarrito(): array
    {
        if (isset($_SESSION["login"]["userId"])) {
            return CartDao::getAuthCart($_SESSION["login"]["userId"]);
        } elseif (isset($_SESSION["annonCartCode"])) {
            return CartDao::getAnonCart($_SESSION["annonCartCode"]);
        } else {
            return [];
    }
    }

    public function run(): void
    {
        $this->viewData["carrito"] = self::getCarrito();
        Renderer::render("maintenance/products/carrito", $this->viewData); 
    }
}