<?php

class User 
{
    public $name;
    public $age;

    public function __construct($name, $age) 
    {
        $this->name = $name;
        $this->age = $age;
    }

    public function SetName($name)
    {
        $this->name = $name;  
        echo "Ім'я збережено\n"; 
    }

    public function SetAge($age)
    {
        if($age < 18 || $age > 101)
        {
            echo "Вік не коректний\n";
            return false;
        }
        $this->age = $age;
        echo "Вік збережено\n";
        return true;
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
        do 
        {
            echo "1. Змінити ім'я - 1\n"
            . "2. Змінити вік - 2\n"
            . "3. Вийти - 0\n";

            $choice = readline("Ваш вибір:");

            switch($choice)
            {
                case 1:
                    $this->NameChange();
                break;
                case 2:
                    $this->AgeChange();
                break;        
                case 0:
                    break(2);
                break;
                default:
                    echo "Такого пункту меню не існує";
                break;
            }

        } while(true);
    }

    public function GetName() : string 
    {
        return $this->user->name;
    }

    public function GetAge() : int
    {
        return $this->user->age;
    }

    public function NameChange()
    {
        $new_name = readline("Ведіть нове ім'я:");
        sleep(1);
        $this->user->SetName($new_name);
    }

    public function AgeChange()
    {
        $new_age = readline("Ведіть новий вік:");
        sleep(1);
        $this->user->SetAge($new_age);
    }
}

class Product 
{
    public $amount;
    public $price; 

    public function __construct($price, $amount)
    {
        $this->price = $price;
        $this->amount = $amount;
    }
}

class Auto extends Product
{
    public $model;
    public $requireAge;

    public function __construct($price, $model, $requireAge, $amount)
    {
        parent::__construct($price, $amount);
        $this->model = $model;
        $this->requireAge = $requireAge;
    }
}

class ProductController
{
    public $products;

    function __construct($products)
    {
        $this->products = $products;
    }

    public function Display()
    {
        echo "Номер Назва Ціна Кількість\n";
        foreach($this->products as $number => $product) {
            echo "$number. $product->model $product->price $product->amount \n";
        }
    }

    public function GetByIdWithCount($id, $count) 
    {
        $id = (int)$id;
        $count = (int)$count;
        $product =  $this->products[$id];
        
        if($product == null) 
        {
            echo "Продукт не існує\n";
            return null;
        }
        
        if($count <= 0)
        {
            echo "Кількість товару ведена помилково\n";
            return null;
        }

        if($product->amount < $count)
        {
            echo "На складі немає такої кількості товару\n";
            return null;
        }

        $this->products[$id]->amount -= $count;
        return $product;
    }
}

class ProductAutoProvider
{
    public function Initialize() : array
    {
        return array(
            1 => new Auto(123, "Nissan", 18, 20),
            2 => new Auto(111, "Mercedes-Benz", 19, 30),
            3 => new Auto(999, "Toyota", 20, 50)
        );
    }
}

class BasketController
{
    private $products;

    public function __construct()
    {
        $this->products = array();
    }

    public function Add($product, $count)
    {
        $product->amount = (int)$count;
        array_push($this->products, $product);
    }

    public function GetProductsPrice() : int
    {
        $sum = 0;
        for($i = 0; $i < count($this->products); $i++)
        {
            $sum += $this->products[$i]->price * $this->products[$i]->amount;
        }
        return $sum;
    } 

    public function Display()
    {
        for($i = 0; $i < count($this->products); $i++) {
            echo ($i + 1) . "  " . $this->products[$i]->model . "  " .  $this->products[$i]->price . " " . $this->products[$i]->amount ."  \n";
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

            echo "Виберіть продукт по його номеру щоб додати до корзини. Вийти - 0.\n";
            $choice = readline("Ваш вибір: ");

            switch($choice)
            {
                case 0:
                    break(2);
                break;
                default:
                    $product_count = readline("Ведіть кількість товару:");
                    $product = $this->product_controller->GetByIdWithCount($choice, $product_count);

                    if($product != null)
                    {
                        if($product->requireAge > $this->user_controller->GetAge())
                        {
                            echo "Недопустимий вік користувача при спробі додати цей товар до корзини";
                        } 
                        else 
                        {
                            $this->basket_controller->Add($product, $product_count);
                            sleep(2);
                            echo "Продукт номер $choice в розмірі $product_count одиниць додано до корзини\n";
                        }
                    }
                break;
            }
        } while(true);
    }

    public function UserSettings()
    {
        do 
        {
            echo "Ваше ім'я: " . $this->user_controller->GetName() . "\n";
            echo "Ваш вік: " . $this->user_controller->GetAge() . "\n";
            echo "Змінити ім'я або вік - 1. \n" . "Вийти - 0. \n";
            $choice = readline("Ваш вибір: ");

            switch($choice)
            {
                case 1:
                    $this->user_controller->Edit();
                break;
                case 0:
                    break(2);
                break;
                default:
                    echo "Такого пункту меню не існує\n";
                break;
            }

        } while(true);
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
                    echo "Такий пункт меню не знайдено! Спробуйте ще.\n";
                    break;
            }

        } while(true);
    }
}


$user = new User(null, null);
$init_name = readline("Ведіть ваше імя: ");
$user->SetName($init_name);
while(true) 
{
    $init_age = readline("Ведіть ваш вік: ");
    if($user->SetAge($init_age))
        break;
}

$shop_controller = new ShopController (
    new AutoShop(
        new ProductController((new ProductAutoProvider())->Initialize()),
        new UserController($user),
        new BasketController()
    )
);
$shop_controller->Display();

?>