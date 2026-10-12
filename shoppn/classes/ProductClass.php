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

    public function addProduct(
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $imageFilename,
        $keywords
    ) {
        return $this->execute(
            'INSERT INTO products
             (product_cat, product_brand, product_title, product_price,
              product_desc, product_image, product_keywords)
             VALUES (?, ?, ?, ?, ?, ?, ?)',
            [$cat, $brand, $title, $price, $desc, $imageFilename, $keywords]
        );
    }

    public function updateProduct(
        $id,
        $cat,
        $brand,
        $title,
        $price,
        $desc,
        $imageFilename,
        $keywords
    ) {
        return $this->execute(
            'UPDATE products
             SET product_cat = ?, product_brand = ?, product_title = ?,
                 product_price = ?, product_desc = ?, product_image = ?,
                 product_keywords = ?
             WHERE product_id = ?',
            [$cat, $brand, $title, $price, $desc, $imageFilename, $keywords, $id]
        );
    }

    public function getProductById($id)
    {
        return $this->fetchOne(
            'SELECT p.*, c.cat_name, b.brand_name
             FROM products p
             JOIN categories c ON p.product_cat = c.cat_id
             JOIN brands b ON p.product_brand = b.brand_id
             WHERE p.product_id = ?',
            [$id]
        );
    }

    public function getAllProducts()
    {
        return $this->fetchAll(
            'SELECT p.*, c.cat_name, b.brand_name
             FROM products p
             JOIN categories c ON p.product_cat = c.cat_id
             JOIN brands b ON p.product_brand = b.brand_id
             ORDER BY p.product_id DESC'
        );
    }
}