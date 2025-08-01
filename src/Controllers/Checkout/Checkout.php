<?php

namespace Controllers\Checkout;

use Controllers\PublicController;
use Dao\Cart\Cart as CartDao;
use Utilities\Security;

class Checkout extends PublicController{
    public function run():void
    {
        $viewData = array();
        $viewData = CartDao::getAuthCart($_SESSION["login"]["userId"]);

        // echo "<pre>";
        // var_dump($viewData);
        // echo "</pre>";
        // die();


        if ($this->isPostBack()) {
            $PayPalOrder = new \Utilities\Paypal\PayPalOrder(
                "test".(time() - 10000000),
                "http://localhost/ProyectoNW/index.php?page=Checkout_Error",
                "http://localhost/ProyectoNw/index.php?page=Checkout_Accept"
            );

            foreach ($viewData as $item){
                $nombre = $item["nombre"];
                $descripcion = $item["descripcion"];
                $sku = "PDR" . $item["pastel_id"];
                $precio = floatval($item["precio"]);
                $impuesto = round($precio * 0.15, 2);
                $cantidad = intval($item["crrctd"]);
                $tipo = "DIGITAL_GOODS";

                $PayPalOrder->addItem($nombre, $descripcion, $sku, $precio, $impuesto, $cantidad, $tipo);
            }

            // $PayPalOrder->addItem("Test", "TestItem1", "PRD1", 100, 15, 1, "DIGITAL_GOODS");
            // $PayPalOrder->addItem("Test 2", "TestItem2", "PRD2", 50, 7.5, 2, "DIGITAL_GOODS");
            $response = $PayPalOrder->createOrder();
            $_SESSION["orderid"] = $response[1]->result->id;

            $usercod = Security::getUserId();
            CartDao::deleteAuthCart($usercod);
            
            \Utilities\Site::redirectTo($response[0]->href);

            die();
        }

        \Views\Renderer::render("paypal/checkout", $viewData);
    }
}
?>
