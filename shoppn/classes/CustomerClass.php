<?php
require_once __DIR__ . '/../core/db_class.php';

class CustomerClass extends Database
{
    public function emailExists($email)
    {
        $row = $this->fetchOne(
            'SELECT customer_id FROM customer WHERE customer_email = ?',
            [$email]
        );

        return $row !== false;
    }

    public function addCustomer($name, $email, $passwordHash, $country, $city, $contact)
    {
        $this->execute(
            'INSERT INTO customer
             (customer_name, customer_email, customer_pass,
              customer_country, customer_city, customer_contact, user_role)
             VALUES (?, ?, ?, ?, ?, ?, 2)',
            [$name, $email, $passwordHash, $country, $city, $contact]
        );

        return (int) $this->getConnection()->lastInsertId();
    }

    public function getCustomerByEmail($email)
    {
        return $this->fetchOne(
            'SELECT * FROM customer WHERE customer_email = ?',
            [$email]
        );
    }

    public function login($email, $password)
    {
        $customer = $this->getCustomerByEmail($email);

        if (!$customer || !password_verify($password, $customer['customer_pass'])) {
            return false;
        }

        return $customer;
    }
}