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
            $this->count = 0;
            if($product->amount == 0) {
                $this->count++;
                echo "$number. $product->model $product->price $product->amount \n";
            }
        }
    }

    public function GetById($id) 
    {
        $id = (int)$id;
        $product =  $this->products[$id];
        
        if($product == null) {
        echo "Product doesn't exist\n";
        return null;
        }
        else 
        {
            return $product;
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
            1 => new Auto(123, "bro", 18),
            2 => new Auto(111, "bruh", 19),
            3 => new Auto(999, "Bro))", 20)
        );
    }
}

class BasketController
{
    private $products;
    private $count;

    public function __construct()
    {
        $this->products = [];
        $this->count = 0;
    }

    public function Add($product)
    {
        $this->products[$this->count++] = $this->product;
    }

    public function GetProductsPrice() : int
    {
        $sum = 0;
        foreach($this->products as $number => $product) {
            $sum += $product->price; // Ціна всіх покупок ще поки не розраховується.
        }

        return $sum;
    } 

    public function Display()
    {
        foreach($this->products as $number => $product) {
            echo "$number. $product->model $product->price \n";
        }
    }
}

class Shop 
{
    private $product_controller;
    private $user_controller;
    private $basket_controller;

    public function __construct(
        $product_controller, 
        $user_controller,
        $basket_controller
        )
    {
        $this->product_controller = $product_controller;
        $this->user_controller = $user_controller;
        $this->basket_controller = $basket_controller;
    }

    public function DisplayProducts()
    {
        $this->product_controller->Display();
    }
}


class AutoShop extends Shop
{
    private $product_controller;
    private $basket_controller;
    private $user_controller;

    public function __construct(
        $product_controller, 
        $user_controller,
        $basket_controller
        ) 
    {
        $this->product_controller = $product_controller;
        $this->user_controller = $user_controller;
        $this->basket_controller = $basket_controller;
        parent::__construct($product_controller, $user_controller, $basket_controller);
    }

    public function Shopping()
    {
        do
        {
            parent::DisplayProducts();

            $choice = readline("Виберіть продукт по його номеру щоб додати до корзини. Вийти - 0:");

            switch($choice)
            {
                case 0:
                    break(2);
                break;
                default:
                    $product = $this->product_controller->GetById($choice);
                    if($product != null)
                    {
                        $this->basket_products[$this->$basket_count++] = $product;
                        sleep(2);
                        echo "Продукт номер $choice додано до корзини\n";
                    }
                break;
            }
        } while(true);
    }

    public function UserSettings()
    {

    }

    public function Check()
    {
        do
        {
            $global_price = $this->basket_controller->GetProductsPrice();
            echo "Кошик\n Ціна всіх покупок: $global_price\n";
            $this->basket_controller->Display();
            echo "Вийти 0.\n";

            $choice = readline("Ваш вибір:");

            switch($choice)
            {
                case 0:
                    break(2);
                break;
                default:
                    echo "Такого пункту меню не існує\n";
                break;
            }
        }   while(true);
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
            echo "1. Розпочати покупки – «1».\n"
            ."2. Отримати підсумковий рахунок – «2».\n"
            ."3. Налаштування профілю – «3».\n"
            ."4. Вихід із програми – «0».\n";
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
        new UserController(new User("bro", 123)),
        new BasketController()
    )
);
$shop_controller->Display();

?>