<?php
class CategoryDB {
    public static function getCategories() {
        $db = Database::getDB();
        $query = 'SELECT categoryID, categoryName 
                  FROM categories
                  ORDER BY categoryID';
        try {
            $statement = $db->prepare($query);
            $statement->execute();
            $rows = $statement->fetchAll();
            $statement->closeCursor();
            
            $categories = [];
            foreach ($rows as $row) {
                $categories[] = new Category($row['categoryID'],
                                             $row['categoryName']);
            }
            return $categories;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }

    public static function getCategory($category_id) {
        $db = Database::getDB();
        $query = 'SELECT categoryID, categoryName 
                  FROM categories
                  WHERE categoryID = :category_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
            $row = $statement->fetch();
            $statement->closeCursor();
            
            return new Category($row['categoryID'],
                                $row['categoryName']);
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    public static function addCategory($name) {
        $db = Database::getDB();
        $query = 'INSERT INTO categories
                     (categoryName)
                  VALUES
                     (:name)';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':name', $name);
            $statement->execute();
            $statement->closeCursor();
            return $db->lastInsertId();
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    public static function deleteCategory($category_id) {
        $db = Database::getDB();
        $query = 'DELETE FROM categories
                  WHERE categoryID = :category_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':category_id', $category_id);
            $row_count = $statement->execute();
            $statement->closeCursor();
            return $row_count;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
    public static function getProductCount($category_id) {
        $db = Database::getDB();
        $query = 'SELECT COUNT(*) AS productCount
                  FROM products
                  WHERE categoryID = :category_id';
        try {
            $statement = $db->prepare($query);
            $statement->bindValue(':category_id', $category_id);
            $statement->execute();
            $result = $statement->fetchAll();
            $statement->closeCursor();
            $productCount = $result[0]['productCount'];
            return $productCount;
        } catch (PDOException $e) {
            Database::displayError($e->getMessage());
        }
    }
}
?>