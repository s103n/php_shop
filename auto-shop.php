<?php

class User 
{
    private $name;
    private $age;

    public function __construct($name, $age) 
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function Edit()
    {
        echo "TODO";
    }
}

class UserController
{
    private $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function Edit()
    {

    }
}

class UserValidation
{
    public function NameValidation($name) : boolean
    {
        //Todo validation
        return true;
    }    

    public function AgeValidation($name) : boolean
    {
        //Todo validation
        return true;
    }
}

class ProductValidation
{
    public function AutoAgeValidation($age, $requireAge)
    {
        if($age >= $requireAge)
        {
            return true;
        }
    }
}

class Product 
{
    public $amount;
    public $price; 

    public function __construct($price)
    {
        $this->price = $price;
        $this->amount = 0;
    }
}

class Auto extends Product
{
    public $model;
    public $requireAge;

    public function __construct($price, $model, $requireAge)
    {
        parent::__construct($price);
        $this->model = $model;
        $this->requireAge = $requireAge;
    }
}

class ProductController
{
    public $products;
    public $count;

    function __construct($products)
    {
        $this->products = $products;
        $this->count = 0;
    }

    public function Add($product)
    {
        $this->products[$this->count++] = $product;
    }

    public function Display()
    {
        foreach($this->products as $number => $product) {
            if($product->amount == 0) {
                echo "$number. $product->model "
                    . bcadd($product->price, "0")
                    . " "
                    . bcadd($product->amount, "0", 0)
                    . "\n";
            }
        }
    }
}

class ProductAutoProvider
{
    private $products;

    function __construct()
    {
    }

    public function Initialize() : array
    {
        return array(
            0 => new Auto(123, "bro", 18),
            1 => new Auto(111, "bruh", 19),
            2 => new Auto(999, "Bro))", 20)
        );
    }
}

class Shop 
{
    protected $product_controller;
    protected $user_controller;

    public function __construct(
        $product_controller, 
        $user_controller
        )
    {
        $this->product_controller = $product_controller;
        $this->user_controller = $user_controller;
    }

    public function DisplayProducts()
    {
        $this->product_controller->Display();
    }
}


class AutoShop extends Shop
{
    public function __construct(
        $product_controller, 
        $user_controller
        ) 
    {
        parent::__construct($product_controller, $user_controller);
    }

    public function Shopping()
    {
        parent::DisplayProducts();
    }

    public function UserSettings()
    {

    }

    public function Check()
    {

    }
}


class ShopController 
{
    private $shop;

    public function __construct($shop)
    {
        $this->shop = $shop;
    }

    public function Display()
    {
        do
        {
            echo "1. Розпочати покупки – «1».\n2. Отримати підсумковий рахунок – «2».\n3. Налаштування профілю – «3».\n4. Вихід із програми – «0».\n";
            $choice = readline("Ваш вибір: ");

            switch($choice)
            {
                case 1:
                    $this->shop->Shopping();
                    break;
                case 2:
                    $this->shop->Check();
                    break;
                case 3:
                    $this->shop->UserSettings();
                    break;
                case 0:
                    break(2);
                    break;
                default:
                    echo "Такий пункт меню не знайдено! Спробуйте ще.";
                    break;
            }

        } while(true);
    }
}


$shop_controller = new ShopController (
    new AutoShop(
        new ProductController((new ProductAutoProvider())->Initialize()),
        new UserController(new User("bro", 123))
    )
);
$shop_controller->Display();

?>