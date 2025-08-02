<?php

namespace Controllers\Maintenance\Cart;

use Dao\Cart\Cart;

class CartUpdate extends \Controllers\PublicController
{
    public function run(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $pastel_id = intval($_POST['pastel_id'] ?? 0);
            $action = $_POST['action'] ?? '';

           
            $userId = $_SESSION["login"]["userId"] ?? null;
            $anonCod = $_SESSION["annonCartCode"] ?? null;

            if ($pastel_id > 0) {
                if ($action === 'restar') {
                    if ($userId) {
                       
                        Cart::removeFromCart($pastel_id, $userId);
                         Cart::aumentarCantidad($pastel_id);
                    } elseif ($anonCod) {
                       
                        Cart::removeFromAnonCart($pastel_id, $anonCod);
                        Cart::aumentarCantidad($pastel_id);
                    }
                } elseif ($action === 'sumar') {
                    if ($userId) {
                        Cart::addToCart($pastel_id, $userId); 
                         Cart::restarCantidad($pastel_id);
                    } elseif ($anonCod) {
                        Cart::addToAnonCartCantidad($pastel_id, $anonCod);
                        Cart::restarCantidad($pastel_id);
                
                    }
                }elseif ($action === 'eliminar') {
                    if ($userId) {
                        Cart:: deleteFromCart($pastel_id, $userId); 
                        
                    } elseif ($anonCod) {
                        Cart::deleteFromAnonCart($pastel_id, $anonCod);
                    }
                }
            }
        }

        header("Location: index.php?page=Maintenance-Cart-Carrito");
        exit;
    }
}