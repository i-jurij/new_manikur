<?php
namespace App\Lib;

class Db_init_sqlite
{
    public $db;

    public function __construct($params = [
                                'type' => 'sqlite',
                                'database' => APPROOT.DS.'db'.DS.DB_NAME.'.sqlite',
                                'charset' => 'utf8mb4',
                                'collation' => 'utf8mb4_general_ci',
                                'error' => \PDO::ERRMODE_EXCEPTION,
                                'port' => 3306
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

        $this->db->create("client_phone_numbers", [
            "id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "name" => [
                "TEXT"
            ],
            "phone_number" => [
                "TEXT",
                "NOT NULL"
            ],
            "send" => [
                "TEXT"
            ],
            "date_time" => [
                "TEXT",
                "NOT NULL"
            ],
            "recall" => [
                "INTEGER"
            ]      
        ]);
        $this->db->create("about", [
            "id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "article_title" => [
                "TEXT"
            ],
            "article_content" => [
                "TEXT",
                "NOT NULL"
            ],
            "article_image" => [
                "TEXT"
            ]
        ]);
        $this->db->create("map", [
            "id" => [
                "INTEGER PRIMARY KEY",
                "AUTOINCREMENT",
                "NOT NULL"
            ],
            "article_title" => [
                "TEXT"
            ],
            "article_content" => [
                "TEXT",
                "NOT NULL"
            ],
            "article_image" => [
                "TEXT"
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