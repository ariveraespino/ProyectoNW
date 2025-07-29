<?php

namespace Controllers\Maintenance\Products;

use Controllers\PublicController;
use Dao\Products\Pasteles as PastelesDAO;
use Views\Renderer;

class Pastelesu extends PublicController
{
    private array $viewData;


    public function run(): void
{
    
     
     $tipPastel = $_GET['tip_pastel'] ?? null;

    if ($tipPastel) {
        $this->viewData["pasteles"] = PastelesDAO::getPastelesByTipo($tipPastel);
        Renderer::render("maintenance/products/pasteles_user", $this->viewData);
        
    } else {
        $this->viewData["pasteles"] = PastelesDAO::getPasteles();
        Renderer::render("maintenance/products/pasteles_user", $this->viewData);
        
    }
    
    
}
}