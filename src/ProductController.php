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
                break;
            default:
                http_response_code(405);
                break;
        }
      
    }






}