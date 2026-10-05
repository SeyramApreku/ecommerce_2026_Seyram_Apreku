<?php
require_once __DIR__ . '/../core/db_class.php';

class ProductClass extends Database
{
    public function addBrand($name)
    {
        return $this->execute(
            'INSERT INTO brands (brand_name) VALUES (?)',
            [$name]
        );
    }

    public function getAllBrands()
    {
        return $this->fetchAll(
            'SELECT * FROM brands ORDER BY brand_name ASC'
        );
    }

    public function getBrandById($id)
    {
        return $this->fetchOne(
            'SELECT * FROM brands WHERE brand_id = ?',
            [$id]
        );
    }

    public function updateBrand($id, $name)
    {
        return $this->execute(
            'UPDATE brands SET brand_name = ? WHERE brand_id = ?',
            [$name, $id]
        );
    }

    public function addCategory($name)
    {
        return $this->execute(
            'INSERT INTO categories (cat_name) VALUES (?)',
            [$name]
        );
    }

    public function getAllCategories()
    {
        return $this->fetchAll(
            'SELECT * FROM categories ORDER BY cat_name ASC'
        );
    }

    public function getCategoryById($id)
    {
        return $this->fetchOne(
            'SELECT * FROM categories WHERE cat_id = ?',
            [$id]
        );
    }

    public function updateCategory($id, $name)
    {
        return $this->execute(
            'UPDATE categories SET cat_name = ? WHERE cat_id = ?',
            [$name, $id]
        );
    }
}