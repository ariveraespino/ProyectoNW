<?php

namespace Controllers\Checkout;

use Controllers\PublicController;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpException;
use Dao\Factura\Factura; // Agregamos la importación de la nueva clase

class Accept extends PublicController
{
    public function run(): void
    {
        $dataview = array();
        $token = $_GET["token"] ?? "";
        $session_token = $_SESSION["orderid"] ?? "";

        if ($token !== "" && $token === $session_token) {
            try {
                // Intentar capturar la orden
                $response = \Utilities\Paypal\PayPalCapture::captureOrder($session_token);
                $result = $response->result;
            } catch (HttpException $e) {
                // Si ya fue capturada, obtener los datos con OrdersGetRequest
                $errorData = json_decode($e->getMessage(), true);
                if ($errorData['name'] === 'UNPROCESSABLE_ENTITY' &&
                    $errorData['details'][0]['issue'] === 'ORDER_ALREADY_CAPTURED') {
                    
                    $request = new OrdersGetRequest($session_token);
                    $orderResponse = \Utilities\Paypal\PayPalClient::client()->execute($request);
                    $result = $orderResponse->result;
                } else {
                    $dataview["error"] = "Ocurrió un error con PayPal: " . $e->getMessage();
                    \Views\Renderer::render("paypal/accept", $dataview);
                    return;
                }
            }

            // Información básica del comprador
            $payer = $result->payer;
            $dataview["payerName"] = $payer->name->given_name . " " . $payer->name->surname;
            $dataview["payerEmail"] = $payer->email_address;

            // Información del pedido
            $purchase = $result->purchase_units[0];
            $capture = $purchase->payments->captures[0];
            $dataview["status"] = $capture->status;
            $dataview["grossAmount"] = $capture->seller_receivable_breakdown->gross_amount->value;
            $dataview["paypalFee"] = $capture->seller_receivable_breakdown->paypal_fee->value;
            $dataview["netAmount"] = $capture->seller_receivable_breakdown->net_amount->value;
            $dataview["orderId"] = $result->id;

            // =========================================================================
            // LÓGICA AGREGADA: INSERCIÓN EN LA BASE DE DATOS
            // =========================================================================

            // Asegúrate de tener una variable de sesión con el ID del usuario
            $userId = $_SESSION["login"]["userId"] ?? null;

            if ($userId !== null) {
                try {
                    // Llama al método estático de la nueva clase Dao\Factura
                    $result = Factura::insertarFactura(
                        $dataview["orderId"],
                        $dataview["payerName"],
                        $dataview["status"],
                        $dataview["grossAmount"],
                        $userId,
                        $dataview["payerEmail"]
                    );

                    if ($result > 0) {
                        $dataview["success_message"] = "La factura se ha guardado correctamente.";
                    } else {
                        $dataview["error"] = "No se pudo guardar la factura.";
                    }
                } catch (\Exception $e) {
                    error_log("Error al insertar la factura: " . $e->getMessage());
                    $dataview["error"] = "Ocurrió un error interno al guardar la factura.";
                }

            } else {
                // Manejar el caso en el que no se encontró el usercod
                $dataview["error"] = "ID de usuario no encontrado en la sesión.";
            }

            // =========================================================================
            // FIN DE LA LÓGICA DE INSERCIÓN
            // =========================================================================

        } else {
            $dataview["error"] = "¡Token inválido o no disponible!";
        }

        \Views\Renderer::render("paypal/accept", $dataview);
    }
}