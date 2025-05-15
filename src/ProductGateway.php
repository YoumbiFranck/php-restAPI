<?php
class ProductGateway
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db->getConnection();
    }

    public function getAllProducts(): array
    {
        $stmt = $this->db->prepare("SELECT * FROM product");
        $stmt->execute();
        $data = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $row["is_available"] = (bool)$row["is_available"]; // Convert to boolean
            $data[] = $row;
        }
        return $data;
    }

    public function getProductById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): string 
    {
        $sql = "INSERT INTO product (name, size, is_available)
                VALUES (:name, :size, :is_available)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":name", $data["name"], PDO::PARAM_STR);
        $stmt->bindValue(":size", $data["size"] ?? 0, PDO::PARAM_INT);
        $stmt->bindValue(":is_available", (bool) ($data["is_available"] ?? false), PDO::PARAM_BOOL);

        $stmt->execute();
        return $this->db->lastInsertId();

    }

    // Wir gehen davon aus, dass man nur den Namen aktualisieren kann
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE product
                SET name = :name, size = :size, is_available = :is_available
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        #
        $stmt->bindValue(":name", $new["name"] ?? $current["name"], PDO::PARAM_STR);
        $stmt->bindValue(":size", $new["size"] ?? $current["size"], PDO::PARAM_INT);
        $stmt->bindValue(":is_available", $new["is_available"] ?? $current["is_available"], PDO::PARAM_BOOL);


        $stmt->bindValue(":id", $current["id"], PDO::PARAM_INT);
        
        $stmt->execute();

        return $stmt->rowCount(); // Die Anzahl der Zeilen die geändert wurden.
       
    }

    public function get(string $id): array | false
    {
        $sql = "SELECT * 
                FROM product 
                WHERE id = :id";
        
        $stmt = $this->db->prepare( $sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        
        //fetch the record as an associative Array 
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if($data !== false){
            $data["is_available"] = (bool) $data["is_available"];
        }
        
        return $data;

    }

    public function delete(string $id): int
    {
        $sql = "DELETE FROM product 
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount(); // number of row affected
    }
}