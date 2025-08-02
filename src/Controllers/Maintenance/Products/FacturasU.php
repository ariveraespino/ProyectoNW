<?php

namespace Controllers\Maintenance\Products;

use Controllers\PrivateController;
use Dao\Factura\Factura as FacturaDAO;
use Views\Renderer;

class FacturasU extends PrivateController
{
    private array $viewData;


    public function run(): void
{
    
     
    $this->viewData["factura"] = FacturaDAO::getFacturasbyID($_SESSION["login"]["userId"]);
    Renderer::render("maintenance/products/facturas", $this->viewData);
    
}
}