<?php

class ProductController
{
    private ProductGateway $productGateway;
    
    public function __construct($productGateway)
    {
        $this->productGateway = $productGateway;
    }
  

    /**
     * Cette méthode va permettre de récupérer tous les produits
     * @return string
     */
    public function processRequest(string $method , ?string $id): void 
    {
        // S'il y'a un id, on va chercher le produit spécifique
        // S'il n'y a pas d'id, on va chercher tous les produits
        if($id){
            $this->processRessourceRequest($method, $id);
        }else{
            $this->processCollectionRequest($method);
        }
    }

    /**
     * Cette méthode va permettre de traiter la requête pour un produit spécifique
     * @param string $method
     * @param string $id
     * @return void
     */
    private function processRessourceRequest(string $method , string $id): void 
    {
        $product = $this->productGateway->get($id);
        if (!$product) {
            http_response_code(404);
            echo json_encode(["message" => "Das Produkt wurde nicht gefunden"]);
            return;
        }
        switch ($method){
            case "GET":
                echo json_encode($product);
                break;
            case "PATCH": // {"name" : "Franck", "size" : 44, "is_available" : 0} body request
                $data = (array) json_decode(file_get_contents('php://input'), true);
                $errors = $this->getValidationErrors($data, false);

                if (!empty($errors)) {
                    http_response_code(422); // (422 Unprocessable Entity) indique que le serveur a compris le type de contenu de la requête et que la syntaxe de la requête est correcte mais que le serveur n'a pas été en mesure de réaliser les instructions demandées.
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $rows = $this->productGateway->update($product, $data);

                echo json_encode([
                    "message" => "product $id updated",
                    "rows" => $rows
                ]);
                break;

            case "DELETE":
                $rows= $this->productGateway->delete($id);
                echo json_encode([
                    "message" => " Das Produkt $id wurde gelöscht",
                    "rows" => $rows
                ]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, PATCH, DELETE");
                break;            
        }
        # echo 
    }

    /**
     * Cette méthode va permettre de traiter la requête pour tous les produits
     * @param string $method
     * @return void
     */
    private function processCollectionRequest(string $method): void 
    {
        switch ($method) {
            case 'GET':
                echo json_encode($this->productGateway->getAllProducts());
                break;
            case 'POST':
                #echo $this->createProduct();
                $data = (array) json_decode(file_get_contents('php://input'), true);
                $errors = $this->getValidationErrors($data);
                
                if(!empty($errors)){
                    http_response_code(422); // (422 Unprocessable Entity) indique que le serveur a compris le type de contenu de la requête et que la syntaxe de la requête est correcte mais que le serveur n'a pas été en mesure de réaliser les instructions demandées.
                    echo json_encode(["errors" => $errors]);
                    break;
                }

                $id = $this->productGateway->create($data);

                http_response_code(201);
                echo json_encode([
                    "message" => "product created",
                    "id" => $id
                ]);
                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST");
                break;
        }
      
    }

    private function getValidationErrors(array $data, bool $is_new_record = true): array
    {
        $errors = [];
        
            if ($is_new_record && empty($data["name"])) {
                $errors[] = "name is required";
            }
       
        

        if (array_key_exists("size", $data)){
            if(filter_var($data["size"], FILTER_VALIDATE_INT) === false ){
                $errors[] = "size must be an intiger";
            }
        }

        return $errors;
    }






}