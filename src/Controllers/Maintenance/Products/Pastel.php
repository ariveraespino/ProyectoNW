<?php

namespace Controllers\Maintenance\Products;

use Controllers\PrivateController;
use Dao\Products\Pasteles as PastelesDAO;
use Views\Renderer;

use Utilities\Site;
use Utilities\Validators;

const LIST_URL = "index.php?page=Maintenance-Products-Pasteles";

class Pastel extends PrivateController
{
    private array $viewData;
    private array $modes;

    public function __construct()
    {
        parent::__construct();
        $this->viewData = [
            "mode" => "",
            "id" => 0,
            "nombre" => "",
            "tipo" => "",
            "descripcion" => "",
            "url_img" => "",
            "precio" => 0,
            "cantidad" => 0,
            "estado_pastel" => "",
            "modeDsc" => "",
            "errors" => [],
            "cancelLabel" => "Cancel",
            "showConfirm" => true,
            "readonly" => ""
        ];
        $this->modes = [
            "INS" => "Nuevo Pastel",
            "UPD" => "Actualizando %s",
            "DEL" => "Eliminando %s",
            "DSP" => "Detalles de %s"
        ];
    }

    public function run(): void
    {
        $this->getQueryParamsData();
        if ($this->viewData["mode"] !== "INS") {
            $this->getDataFromDB();
        }
        if ($this->isPostBack()) {
            $this->getBodyData();
            if ($this->validateData()) {
                $this->processData();
            }
        }
        $this->prepareViewData();
        Renderer::render("maintenance/products/pastel", $this->viewData);
    }

    private function throwError(string $message, string $logMessage = "")
    {
        if (!empty($logMessage)) {
            error_log(sprintf("%s - %s", $this->name, $logMessage));
        }
        Site::redirectToWithMsg(LIST_URL, $message);
    }

    private function innerError(string $scope, string $message)
    {
        $this->viewData["errors"][$scope][] = $message;
    }

    private function getQueryParamsData()
    {
        if (!isset($_GET["mode"])) {
            $this->throwError("Modo inválido.");
        }
        $this->viewData["mode"] = $_GET["mode"];
        if (!isset($this->modes[$this->viewData["mode"]])) {
            $this->throwError("Modo no reconocido.");
        }
        if ($this->viewData["mode"] !== "INS") {
            if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
                $this->throwError("ID inválido.");
            }
            $this->viewData["id"] = intval($_GET["id"]);
        }
    }

    private function getDataFromDB()
    {
        $tmp = PastelesDAO::getPastelById($this->viewData["id"]);
        if ($tmp && count($tmp) > 0) {
            $this->viewData["nombre"] = $tmp["nombre"];
            $this->viewData["tipo"] = $tmp["tipo"];
            $this->viewData["descripcion"] = $tmp["descripcion"];
            $this->viewData["url_img"] = $tmp["url_img"];
            $this->viewData["precio"] = $tmp["precio"];
            $this->viewData["cantidad"] = $tmp["cantidad"];
            $this->viewData["estado_pastel"] = $tmp["estado_pastel"];
        } else {
            $this->throwError("No se encontró el pastel.");
        }
    }

    private function getBodyData()
    {
        foreach (["id", "nombre", "tipo", "descripcion", "precio", "cantidad", "estado_pastel", "xsrtoken"] as $key) {
            if (!isset($_POST[$key])) {
                $this->throwError("Faltan datos.");
            }
        }

        if (intval($_POST["id"]) !== $this->viewData["id"]) {
            $this->throwError("ID modificado ilegalmente.");
        }

        if ($_POST["xsrtoken"] !== $_SESSION[$this->name . "-xsrtoken"]) {
            $this->throwError("Token inválido.");
        }

        $this->viewData["nombre"] = $_POST["nombre"];
        $this->viewData["tipo"] = $_POST["tipo"];
        $this->viewData["descripcion"] = $_POST["descripcion"];
        $this->viewData["precio"] = intval($_POST["precio"]);
        $this->viewData["cantidad"] = intval($_POST["cantidad"]);
        $this->viewData["estado_pastel"] = $_POST["estado_pastel"];
    }

    private function validateData(): bool
    {
        if (Validators::IsEmpty($this->viewData["nombre"])) {
            $this->innerError("nombre", "Este campo es requerido.");
        }
        if (Validators::IsEmpty($this->viewData["tipo"])) {
            $this->innerError("tipo", "Este campo es requerido.");
        }
        if (strlen($this->viewData["nombre"]) > 100) {
            $this->innerError("nombre", "Máximo 100 caracteres.");
        }
        if (strlen($this->viewData["tipo"]) > 50) {
            $this->innerError("tipo", "Máximo 50 caracteres.");
        }
        if (strlen($this->viewData["descripcion"]) > 255) {
            $this->innerError("descripcion", "Máximo 255 caracteres.");
        }
        if (strlen($this->viewData["estado_pastel"]) > 3) {
            $this->innerError("estado_pastel", "Máximo 3 caracteres.");
        }

        return count($this->viewData["errors"]) === 0;
    }

    private function processData()
    {
        $fileName = $this->viewData["url_img"] ?: "default.jpg";

        if (isset($_FILES["url_img"]) && $_FILES["url_img"]["error"] === UPLOAD_ERR_OK) {
            $fileName = basename($_FILES["url_img"]["name"]);
            $uploadDir = "C:\\xampp\\htdocs\\Proyecto_Neg\\ProyectoNW\\public\\imgs\\hero\\";
            $uploadPath = $uploadDir . $fileName;

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            if (!move_uploaded_file($_FILES["url_img"]["tmp_name"], $uploadPath)) {
                $this->innerError("global", "Error al guardar la imagen.");
                return;
            }
        }

        switch ($this->viewData["mode"]) {
            case "INS":
                if (PastelesDAO::newPastel(
                    $this->viewData["nombre"],
                    $this->viewData["tipo"],
                    $this->viewData["descripcion"],
                    $fileName,
                    $this->viewData["precio"],
                    $this->viewData["cantidad"],
                    $this->viewData["estado_pastel"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Pastel creado exitosamente.");
                } else {
                    $this->innerError("global", "Error al crear el pastel.");
                }
                break;

            case "UPD":
                if (PastelesDAO::updatePastel(
                    $this->viewData["id"],
                    $this->viewData["nombre"],
                    $this->viewData["tipo"],
                    $this->viewData["descripcion"],
                    $fileName,
                    $this->viewData["precio"],
                    $this->viewData["cantidad"],
                    $this->viewData["estado_pastel"]
                ) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Pastel actualizado exitosamente.");
                } else {
                    $this->innerError("global", "Error al actualizar el pastel.");
                }
                break;

            case "DEL":
                if (PastelesDAO::deletePastel($this->viewData["id"]) > 0) {
                    Site::redirectToWithMsg(LIST_URL, "Pastel eliminado exitosamente.");
                } else {
                    $this->innerError("global", "Error al eliminar el pastel.");
                }
                break;
        }
    }

    private function prepareViewData()
    {
        $this->viewData["modeDsc"] = sprintf(
            $this->modes[$this->viewData["mode"]],
            $this->viewData["nombre"]
        );

        if (count($this->viewData["errors"]) > 0) {
            foreach ($this->viewData["errors"] as $scope => $errorsArray) {
                $this->viewData["errors_" . $scope] = $errorsArray;
            }
        }

        if ($this->viewData["mode"] === "DSP") {
            $this->viewData["cancelLabel"] = "Volver";
            $this->viewData["showConfirm"] = false;
        }

        if (in_array($this->viewData["mode"], ["DSP", "DEL"])) {
            $this->viewData["readonly"] = "readonly";
        }

        $this->viewData["estado_act"] = ($this->viewData["estado_pastel"] === "ACT") ? "checked" : "";
        $this->viewData["estado_int"] = ($this->viewData["estado_pastel"] === "INT") ? "checked" : "";
        $tipPastel = $_GET['tip_pastel'] ?? null;
        $this->viewData['tip_pastel'] = $tipPastel;
        $this->viewData["timestamp"] = time();
        $this->viewData["xsrtoken"] = hash("sha256", json_encode($this->viewData));
        $_SESSION[$this->name . "-xsrtoken"] = $this->viewData["xsrtoken"];
    }
}