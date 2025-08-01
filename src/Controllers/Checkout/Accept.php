<?php

namespace Controllers\Checkout;

use Controllers\PublicController;
use PayPalCheckoutSdk\Orders\OrdersGetRequest;
use PayPalHttp\HttpException;

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

        } else {
            $dataview["error"] = "¡Token inválido o no disponible!";
        }

        \Views\Renderer::render("paypal/accept", $dataview);
    }
}
