<?php

namespace App;

class PayPal
{
    private $_apiContext;
    private $shopping_cart;
    private $_ClientId = 'AfnphSHkhvDcPiN_GZwCsWUs9lEkbyOI48GlXOMQw0vHJ3UODuJ9FjSrKuIKdZN-NjVMwcyqtMFGYFg1';
    private $_ClientSecret = 'EO_r7aubM-z_3EPw4AV8TQGNyOtVyLAj1a0BFv1NZefXfqaoBbxA_lO0ys_7lrNzBnYFfJ6bVjimGzc8';

    public function __construct($shopping_cart)
    {
        $this->_apiContext = \PaypalPayment::ApiContext(
            $this->_ClientId,
            $this->_ClientSecret
        );

        $config = config('paypal_payment');

        $flatConfig = array_dot($config);
        $this->_apiContext->setConfig($flatConfig);

        $this->shopping_cart = $shopping_cart;
    }

    public function generate()
    {
        $payment = \PaypalPayment::payment()
            ->setIntent('sale')
            ->setPayer($this->payer())
            ->setTransactions([$this->transaction()])
            ->setRedirectUrls($this->redirectURLs());

        try {
            $payment->create($this->_apiContext);
        } catch (\Exception $ex) {
            dd($ex);
            exit(1);
        }

        return $payment;
    }

    public function payer()
    {
        //Returns payment's info
        return \PaypalPayment::payer()->setPaymentMethod('paypal');
    }

    public function redirectURLs()
    {
        //Returns transaction's URLs
        $baseURL = url('/');

        return \PaypalPayment::redirectUrls()
            ->setReturnUrl("$baseURL/payments/store")
            ->setCancelUrl("$baseURL/cart");
    }

    public function transaction()
    {
        return \PaypalPayment::transaction()
            ->setAmount($this->amount())
            ->setItemList($this->items())
            ->setDescription('Your shopping in eGame')
            ->setInvoiceNumber(uniqid());
    }

    public function items()
    {
        $items = [];

        $articles = $this->shopping_cart->articles()->get();

        foreach ($articles as $article) {
            array_push($items, $article->paypalItem());
        }
        return \PaypalPayment::itemList()->setItems($items);
    }

    public function amount()
    {
        return \PaypalPayment::amount()
            ->setCurrency('EUR')
            ->setTotal($this->shopping_cart->totalEUR());
    }

    public function execute($paymentId, $payerId)
    {
        $payment = \PaypalPayment::getById($paymentId, $this->_apiContext);

        $execution = \PaypalPayment::PaymentExecution()->setPayerId($payerId);

        return $payment->execute($execution, $this->_apiContext);
    }
}
