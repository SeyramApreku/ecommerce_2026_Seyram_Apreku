<?php
require_once __DIR__ . '/../classes/CustomerClass.php';

class CustomerController
{
    private $customer;

    public function __construct()
    {
        $this->customer = new CustomerClass();
    }

    public function register($data)
    {
        if ($this->customer->emailExists($data['email'])) {
            return [
                'success' => false,
                'error' => 'Email already registered.'
            ];
        }

        $passwordHash = password_hash($data['password'], PASSWORD_BCRYPT);

        $customerId = $this->customer->addCustomer(
            $data['name'],
            $data['email'],
            $passwordHash,
            $data['country'],
            $data['city'],
            $data['contact']
        );

        return [
            'success' => true,
            'customer_id' => $customerId
        ];
    }

    public function login($email, $password)
    {
        $customer = $this->customer->login($email, $password);

        if ($customer === false) {
            return [
                'success' => false,
                'error' => 'Invalid email or password.'
            ];
        }

        return [
            'success' => true,
            'customer' => $customer
        ];
    }
}