<?php

namespace Controllers\Maintenance\Products;

use Controllers\PublicController;
use Dao\Products\Pasteles as PastelesDAO;
use Views\Renderer;
use Utilities\Site;

use Dao\Cart\Cart;
use Utilities\Cart\CartFns;
use Utilities\Security;

class Pastelesu extends PublicController
{
    private array $viewData;

    public function run(): void
{

    // Logica para la carretilla
    
        if ($this->isPostBack()){
            if(Security::isLogged()){
                $usercod = Security::getUserId();
                $pastel_id = intval($_POST["pastel_id"]);
                    $pastel = Cart::getProductoDisponible($pastel_id);
                    if($pastel["cantidad"] - 1 >= 0){
                        Cart::addToAuthCart(
                        intval($_POST["pastel_id"]),
                        $usercod,
                        1,
                        $pastel["precio"]
                        );
                    }
            } else {
                    $cartAnonCod = CartFns::getAnnonCartCode();
                if(isset($_POST["addToCart"])){

                    $pastel_id = intval($_POST["pastel_id"]);
                    $pastel = Cart::getProductoDisponible($pastel_id);
                    if($pastel["cantidad"] - 1 >= 0){
                        Cart::addToAnonCart(
                        intval($_POST["pastel_id"]),
                        $cartAnonCod,
                        1,
                        $pastel["precio"]
                        );
                        echo "<script>alert('¡Agregado a la carretilla!');</script>";
                    }
                }
            }
        }

        $pasteles = Cart::getProductosDisponibles();
    //
    
     
     $tipPastel = $_GET['tip_pastel'] ?? null;
        //
        Site::addLink("public/css/btnAgregarCarrito.css");
        Site::addLink("public/css/titlePasteles.css");
        //

    if ($tipPastel) {
        $this->viewData["pasteles"] = PastelesDAO::getPastelesByTipo($tipPastel);
        
        Renderer::render("maintenance/products/pasteles_user", $this->viewData);
        
    } else {
        $this->viewData["pasteles"] = PastelesDAO::getPasteles();
        Renderer::render("maintenance/products/pasteles_user", $this->viewData);
        
    }
    
    
}
}