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
            "page_title" => [
                "VARCHAR(100)"
            ],
            "page_meta_description" => [
                "VARCHAR(255)"
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
            ],
            "page_access" => [
                "VARCHAR(10)"
            ],
            "page_admin" => [
                "INTEGER"
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
 
        $contacts = $this->db->select("contacts", "*");
        if(!$contacts){ // SELECT failed 
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
                    "contacts_data" => "+0 111 222 33 44"
                ],
                [
                    "contacts_type" => "tlf",
                    "contacts_data" => "0"
                ],
                [
                    "contacts_type" => "adres",
                    "contacts_data" => "City, Street, House, Office"
                ]
            ]);
        }

        $admin = $this->db->select("users", "username", [
                "status" =>  ["admin"]
            ]);
        if(!$admin){ // SELECT failed 
            $this->db->insert("users", [
                [
                "username" => "admin",
                "password" => password_hash("passw", PASSWORD_DEFAULT),
                "email" => "foo@bar.com",
                "email_status" => "0",
                "status" => "admin"
                ]
            ]);
        }
        $moder = $this->db->select("users", "username", [
            "status" =>  ["moder"]
        ]);
        if(!$moder){ // SELECT failed 
            $this->db->insert("users", [
                [
                "username" => "moder",
                "password" => password_hash("moder", PASSWORD_DEFAULT),
                "email" => "foo@moder.com",
                "email_status" => "0",
                "status" => "moder"
                ]
            ]);
        }
        $user = $this->db->select("users", "username", [
            "status" =>  ["user"]
        ]);
        if(!$user){ // SELECT failed 
            $this->db->insert("users", [
                [
                "username" => "user",
                "password" => password_hash("user", PASSWORD_DEFAULT),
                "email" => "foo@user.com",
                "email_status" => "0",
                "status" => "user"
                ]
            ]);
        }
    }
}
?>