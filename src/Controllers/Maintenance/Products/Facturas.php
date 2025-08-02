<?php

namespace Controllers\Maintenance\Products;

use Controllers\PrivateController;
use Dao\Factura\Factura as FacturaDAO;
use Views\Renderer;

class Facturas extends PrivateController
{
    private array $viewData;

    public function run(): void
{
    
     
    $this->viewData["factura"] = FacturaDAO::getFacturas();
    Renderer::render("maintenance/products/facturas", $this->viewData);
    
}
}