<?php

namespace Controllers\Maintenance\Products;

use Controllers\PrivateController;
use Dao\Products\Pasteles as PastelesDAO;
use Views\Renderer;

class Pasteles extends PrivateController
{
    private array $viewData;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [];
        $this->viewData["isNewEnabled"] =
            parent::isFeatureAutorized($this->name . "\\new");
        $this->viewData["isUpdateEnabled"] =
            parent::isFeatureAutorized($this->name . "\\update");
        $this->viewData["isDeleteEnabled"] =
            parent::isFeatureAutorized($this->name . "\\delete");
    }

    public function run(): void
{
    
     
     $tipPastel = $_GET['tip_pastel'] ?? null;

    if ($tipPastel) {
        $this->viewData["pasteles"] = PastelesDAO::getPastelesByTipo($tipPastel);
        Renderer::render("maintenance/products/pasteles_user", $this->viewData);
        
    } else {
        $this->viewData["pasteles"] = PastelesDAO::getPasteles();
        Renderer::render("maintenance/products/pasteles", $this->viewData);
        
    }
    
    
}
}