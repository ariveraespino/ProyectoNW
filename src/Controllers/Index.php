<?php
/**
 * PHP Version 7.2
 *
 * @category Public
 * @package  Controllers
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  MIT http://
 * @version  CVS:1.0.0
 * @link     http://
 */
namespace Controllers;

use Dao\Cart\Cart;
use Utilities\Site;
use Utilities\Cart\CartFns;
use Utilities\Security;

/**
 * Index Controller
 *
 * @category Public
 * @package  Controllers
 * @author   Orlando J Betancourth <orlando.betancourth@gmail.com>
 * @license  MIT http://
 * @link     http://
 */
class Index extends PublicController
{
    /**
     * Index run method
     *
     * @return void
     */
    public function run() :void
    {
        Site::addLink("public/css/products.css");

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
                    }
                }
            }
        }

        $pasteles = Cart::getProductosDisponibles();

        $viewData = [
            "pasteles" => $pasteles,
        ];
        \Views\Renderer::render("index", $viewData);
    }
}
?>
