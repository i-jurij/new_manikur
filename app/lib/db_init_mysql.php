<?php 
namespace App\Lib;

class Db_init_mysql
{
    public $db;

    public function __construct($params = [
                                'type' => 'mariadb',
                                'host' => 'localhost',
                                'database' => 'name',
                                'username' => 'your_username',
                                'password' => 'your_password',
                                'charset' => 'utf8mb4',
                                'collation' => 'utf8mb4_general_ci',
                                'error' => \PDO::ERRMODE_EXCEPTION
                                ])
    {
        //init $db
        $this->db = new Medoo($params);
    }

    function create_tables()
    {
        //select from or created pages table if not exists
        $this->db->create("pages", [
            "page_id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "page_alias" => [
                "VARCHAR(100)",
                "UNIQUE"
            ],
            "page_templates" => [
                "VARCHAR(100)"
            ],
            "page_title" => [
                "VARCHAR(255)"
            ],
            "page_meta_description" => [
                "VARCHAR(100)"
            ],
            "page_meta_keywords" => [
                "VARCHAR(500)"
            ],
            "page_robots" => [
                "VARCHAR(100)"
            ],
            "page_h1" => [
                "VARCHAR(100)"
            ],
            "page_img" => [
                "TEXT"
            ],
            "page_content" => [
                "TEXT"
            ],
            "page_publish" => [
                "CHAR",
                "DEFAULT Y"
            ]
        ]);

        $this->db->create("adm_pages", [
            "page_id" => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            "page_alias" => [
                "VARCHAR(100)",
                "UNIQUE"
            ],
            "page_templates" => [
                "VARCHAR(100)"
            ],
            "page_title" => [
                "VARCHAR(255)"
            ],
            "page_meta_description" => [
                "VARCHAR(100)"
            ],
            "page_robots" => [
                "VARCHAR(100)",
                "DEFAULT NOINDEX"
            ],
            "page_h1" => [
                "VARCHAR(100)"
            ],
            "page_content" => [
                "TEXT"
            ],
            "page_access" => [
                "VARCHAR(10)"
            ]
        ]);

        $this->db->create("users", [
            "id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "username" => [
                "VARCHAR(25)", 
                "NOT NULL",
                "UNIQUE"
            ],
            "password" => [
                "VARCHAR(255)", 
                "NOT NULL"
            ],
            "email" => [
                "VARCHAR(100)"
            ],
            "email_status" => [
                "VARCHAR(10)"
            ],
            "status" => [
                "VARCHAR(10)"
            ]
        ]);

        $this->db->create("contacts", [
            "id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "contacts_type" => [
                "VARCHAR(25)"
            ],
            "contacts_data" => [
                "VARCHAR(100)",
                "UNIQUE"
            ]
        ]);
  
        $this->db->create("masters", [
            "id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "master_name" => [
                "VARCHAR(30)"
            ],
            "sec_name" => [
                "VARCHAR(30)"
            ],
            "master_fam" => [
                "VARCHAR(30)"
            ],
            "master_phone_number" => [
                "VARCHAR(20)",
                "UNIQUE"
            ],
            "spec" => [
                "VARCHAR(50)"
            ],
            "data_priema" => [
                "TEXT",
                "NOT NULL"
            ],
            "data_uvoln" => [
                "VARCHAR(30)"
            ]
        ]);
        

/*
        $this->db->insert("contacts", [
            [
                "contacts_type" => "telegram",
                "contacts_data" => "tg"
            ],
            [
                "contacts_type" => "vk",
                "contacts_data" => "vk"
            ],
            [
                "contacts_type" => "instagram",
                "contacts_data" => "inst"
            ],
            [
                "contacts_type" => "tlf",
                "contacts_data" => "+7 523 425 25 43"
            ],
            [
                "contacts_type" => "tlf",
                "contacts_data" => "0"
            ],
            [
                "contacts_type" => "adres",
                "contacts_data" => "Севастополь, ул. Такая-то, д.№00, офис №11"
            ]
        ]);

        $this->db->insert("users", [
            [
                "username" => "admin",
                "password" => password_hash("passw", PASSWORD_DEFAULT),
                "email" => "foo@bar.com",
                "email_status" => "0",
                "status" => "admin"
            ],
            [
                "username" => "moder",
                "password" => password_hash("moder", PASSWORD_DEFAULT),
                "email" => "foo@moder.com",
                "email_status" => "0",
                "status" => "moder"
            ],
            [
                "username" => "user",
                "password" => password_hash("user", PASSWORD_DEFAULT),
                "email" => "foo@user.com",
                "email_status" => "0",
                "status" => "user"
            ]
        ]);

        $this->db->insert("pages", [
            ["page_alias" => "home",
            "page_templates" => "first",
            "page_title" => "Красота спасет мир",
            "page_meta_description" => "Маникюр, визаж, парикмахерские услуги.",
            "page_meta_keywords" => "маникюр, ногти, ногтевая пластина, кутикула, лак, гель, наращивание, покрытие, визаж, грим, тени, помада, парикмахер, салон, парикмахерская, прическа, стрижка",
            "page_h1" => "Маникюр",
            "page_img" => ""
            ],
            ["page_alias" => "about",
            "page_templates" => "first",
            "page_title" => "О нас",
            "page_meta_description" => "Узнайте о нас больше.",
            "page_meta_keywords" => "образование, курсы, материалы, инструмент, маникюр, ногти, лак, наращивание, парикмахер, салон",
            "page_h1" => "О нас",
            "page_img" => "public/imgs/about.jpg"
            ],
        ]);

        $this->db->insert("adm_pages", [
            ["page_alias" => "master_app",
            "page_title" => "Записи к мастерам",
            "page_meta_description" => "Таблица записей к мастерам",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Записи к мастерам",
            "page_access" => "user"
            ],
            ["page_alias" => "date_app",
            "page_title" => "Записи по дням",
            "page_meta_description" => "Таблица записей к мастерам по дням",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Записи к мастерам по дням",
            "page_access" => "user"
            ],
            ["page_alias" => "recall",
            "page_title" => "Перезвоните клиенту",
            "page_meta_description" => "Список клиентов, которым необходимо перезвонить",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Перезвоните клиенту",
            "page_access" => "user"
            ],
            ["page_alias" => "recall_yes",
            "page_title" => "Перезвонили",
            "page_meta_description" => "Список клиентов, которым уже перезвонили",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Клиентам перезвонили",
            "page_access" => "user"
            ],
            ["page_alias" => "contacts",
            "page_title" => "Редактор контактов",
            "page_meta_description" => "Изменение контактных данных",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Редактор контактов",
            "page_access" => "moder"
            ],
            ["page_alias" => "grafik",
            "page_title" => "Графики работы мастеров",
            "page_meta_description" => "Составление графиков работы мастеров",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Графики работы мастеров",
            "page_access" => "user"
            ],
            ["page_alias" => "price",
            "page_title" => "Редактор расценок на услуги",
            "page_meta_description" => "Изменение расценок на услуги",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Редактор расценок на услуги",
            "page_access" => "moder"
            ],
            ["page_alias" => "masters",
            "page_title" => "Мастера",
            "page_meta_description" => "Изменение данных о мастерах",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Мастера",
            "page_access" => "admin"
            ],
            ["page_alias" => "pages_control",
            "page_title" => "Редактор страниц услуг",
            "page_meta_description" => "Изменение данных о мастерах",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Редактор страниц услуг",
            "page_access" => "admin"
            ],
            ["page_alias" => "map",
            "page_title" => "Изменение карты",
            "page_meta_description" => "Изменение карты проезда",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Изменение карты",
            "page_access" => "admin"
            ],
            ["page_alias" => "about",
            "page_title" => "Редактор страницы \"О нас\"",
            "page_meta_description" => "Редактор страницы \"О нас\"",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Редактор страницы \"О нас\"",
            "page_access" => "admin"
            ],
            ["page_alias" => "gallery",
            "page_title" => "Редактор галереи фото",
            "page_meta_description" => "Добавить, удалить фото, изменить ссылку на облачный архив фото",
            "page_robots" => "NOINDEX, NOFOLLOW",
            "page_h1" => "Редактор галереи фото",
            "page_access" => "moder"
            ],
        ]);

*/
    }
}
?>