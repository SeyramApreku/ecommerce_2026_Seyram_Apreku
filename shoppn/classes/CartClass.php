<?php
require_once __DIR__ . '/../core/db_class.php';

class CartClass extends Database
{
    public function isInCart($productId, $ip)
    {
        return (bool) $this->fetchOne(
            'SELECT p_id FROM cart WHERE p_id = ? AND ip_add = ?',
            [$productId, $ip]
        );
    }

    public function addToCart($productId, $qty, $ip, $customerId = null)
    {
        if (!is_int($qty) || $qty < 1 || $qty > 999) {
            throw new InvalidArgumentException('Invalid quantity.');
        }
        $conn = $this->getConnection();
        $conn->beginTransaction();
        try {
            // Lock this product while checking duplicates and inserting.
            $product = $this->fetchOne(
                'SELECT product_id FROM products WHERE product_id = ? FOR UPDATE',
                [$productId]
            );
            if (!$product) {
                throw new InvalidArgumentException('Product not found.');
            }
            if ($this->isInCart($productId, $ip)) {
                $conn->commit();
                return false;
            }
            $this->execute(
                'INSERT INTO cart (p_id, ip_add, qty, c_id) VALUES (?, ?, ?, ?)',
                [$productId, $ip, $qty, $customerId]
            );
            $conn->commit();
            return true;
        } catch (Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    public function getCartItems($ip)
    {
        return $this->fetchAll(
            'SELECT cart.*, p.product_title, p.product_price, p.product_image
             FROM cart JOIN products p ON cart.p_id = p.product_id
             WHERE cart.ip_add = ? ORDER BY cart.p_id DESC',
            [$ip]
        );
    }

    public function getCartCount($ip)
    {
        $row = $this->fetchOne(
            'SELECT COUNT(*) AS item_count FROM cart WHERE ip_add = ?',
            [$ip]
        );
        return (int) $row['item_count'];
    }

    public function getCartTotal($ip)
    {
        $totalInPesewas = 0;
        foreach ($this->getCartItems($ip) as $item) {
            $totalInPesewas += (int) round((float) $item['product_price'] * 100)
                * (int) $item['qty'];
        }
        return (float) ($totalInPesewas / 100);
    }

    public function removeFromCart($productId, $ip)
    {
        return $this->execute(
            'DELETE FROM cart WHERE p_id = ? AND ip_add = ?',
            [$productId, $ip]
        );
    }

    public function updateQty($productId, $newQty, $ip)
    {
        if (!is_int($newQty) || $newQty < 1 || $newQty > 999) {
            throw new InvalidArgumentException('Quantity must be between 1 and 999.');
        }
        return $this->execute(
            'UPDATE cart SET qty = ? WHERE p_id = ? AND ip_add = ?',
            [$newQty, $productId, $ip]
        );
    }

    public function updateQuantities($items, $ip)
    {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        try {
            foreach ($items as $productId => $qty) {
                if (!$this->isInCart($productId, $ip)) {
                    throw new InvalidArgumentException('An item is no longer in your cart.');
                }
                $this->updateQty($productId, $qty, $ip);
            }
            $conn->commit();
        } catch (Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }

    public function removeItems($productIds, $ip)
    {
        $conn = $this->getConnection();
        $conn->beginTransaction();
        try {
            foreach ($productIds as $productId) {
                if (!$this->isInCart($productId, $ip)) {
                    throw new InvalidArgumentException('An item is no longer in your cart.');
                }
                $this->removeFromCart($productId, $ip);
            }
            $conn->commit();
        } catch (Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }
}
