<?php
namespace App\Models;
class Recall extends Home
{
    public function rec() {
        if (!empty($_POST['phone_number'])) {
            // в переменную $badIP мы можем со временем вписать IP-адреса некоторых спамеров
            //(например тех, которые заполнят Вашу форму вручную)
            // IP будет указано в log файле. IP-адреса указываем в кавычках, через запятую,
            //пример: ['185.189.114.123', '185.212.171.99',]
            $badIP    = [];
            $to       = "ваш_email";
            $site = "адрес_вашего_сайта";
            $from     = "mail@".$site;
            $spam     = $_POST["last_name"]; // принимаем данные из скрытого спам-поля
            $ipAddr   = $_SERVER['REMOTE_ADDR']; // определяем IP-адрес пользователя
            $today    = date('d-m-Y_H-i');
            if (empty($_POST["phone_number"])){$phone_number = "";}else {$phone_number = test_input($_POST["phone_number"]);}
            if (empty($_POST["name"])){$name = "";}else {$name = test_input($_POST["name"]);}
            if (empty($_POST["send"])){$send = "";}else {$send = test_input($_POST["send"]);}
            $this->data['res'] = "Заявка на звонок из формы \"Перезвоните мне\"<hr>"."\n";
            $this->data['res'] .= "<b>Имя:</b><br>{$name}<hr>"."\n"."<b>Телефон:</b><br>{$phone_number}<hr>"."\n"."<b>Сообщение:</b><br>{$send}<hr>"."\n";
            
            // если не заполнено скрытое поле и если IP-адрес не находится в нашем чёрном списке
            if(!in_array($ipAddr, $badIP) && empty($spam))
            {
                $logText = strip_tags($this->data['res']); // обрезаем лишние теги для log файла
                // а также если в поле с сообщением нет ни одного соответствия адресам сайтов
                // можем добавить любые другие сочетания букв, по аналогии, через пайп, например (\.ua) и прочее
                if(!preg_match("/(www)|(http)|(https)|(@)|(\.ru)|(\.com)|(\.ua)|(\.рф)/i", $send))
                {
                    //проверим, что такого номера за последние N часов не было и запишем в бд
                    $date_time=date('Y-m-d H:i:s') ; // this to get current date as text .
                    //    $date_time = "STR_TO_DATE(".$date_time.", '%d/%m/%Y %H:%i:%s')"  ;
                    //выборка из бд за последние 2 часа
                    $time = date("Y-m-d H:i:s", strtotime("-2 hour"));
                    $vib_date_time = $this->db->db->select("client_phone_numbers", "phone_number",  
                                                            [ "date_time[>]" => $time ]);
                    /*
                    $sql2 = "SELECT phone_number FROM $table WHERE date_time > NOW() - INTERVAL 2 HOUR";
                    $vib_date_time = $pdo->query($sql2);
                    */
                    
                    //дальше сравним номер из ПОСТ с полученными номерами из бд, если совпадений нет - записываем
                    if (!empty($vib_date_time)) {
                        foreach( $vib_date_time as $b ){
                            if($b == $phone_number) {
                                $this->data['res'] = '<div class="zapis_usluga back shad rad pad margin_rlb1">Ваша заявка принята. <br />Ожидайте звонка...</div>';
                                $z = false;
                                break;
                            } else {
                                $z = true;
                            }
                        }
                    } else {
                        $z = true;
                    }
                    if ($z) {
                        /*
                        $sql3 = "INSERT INTO `client_phone_numbers` (name, phone_number, send, date_time, recall) VALUES (?, ?, ?, ?, ?)";
                        $stmt = $pdo->prepare($sql3);
                        // через массив передаем значения параметрам по позиции
                        $rowsNumber = $stmt->execute(array($name, $phone_number, $send, $date_time, '0'));
                        // если добавлена как минимум одна строка
                        if($rowsNumber > 0 ){
                        //      echo "Data successfully added: name= $name  tel= $phone_number send= $send date= $date_time";
                        }
                        */
                        $ins = $this->db->db->insert("client_phone_numbers", [
                                    "name" => $name,
                                    "phone_number" => $phone_number,
                                    "send" => $send,
                                    "date_time" => $date_time
                        ]);
                        if ($ins->rowCount() > 0) {
                            $this->data['res'] = '<div class="content pers">
                                                        <h3>' . $name . '</h3><h3 class="centr">мы вам перезвоним:</h3>
                                                            <div class="table_body">
                                                                <div class="table_row">
                                                                    <span class="table_cell" style="text-align:right;">По номеру:</span>
                                                                    <span class="table_cell">'
                                                                    . $phone_number .
                                                                    '</span>
                                                                </div>
                                            
                                                                <div class="table_row">
                                                                    <span class="table_cell" style="text-align:right;">Ваше сообщение:</span>
                                                                    <span class="table_cell">'
                                                                        . $send .
                                                                    '</span>
                                                                </div>
                                                            </div>
                                                            <a href="'.URLROOT.'" ><button class="buttons" type="button" autofocus>На главную</button></a>
                                                        </div>
                                                    ' ;
                        } else {
                            $this->data['res'] = 'Извините, возникла ошибка, ваши данные не занесены в базу.<br />
                                                    Пожалуйста, отправьте заявку еще раз.<br />';
                        }
                        //MAIL SEND
                        //include_once APPROOT.DS."lib".DS."mail_send_for_include.php";
                    }                    
                    // записываем логи в файл (если файла нет, то он будет создан автоматически)
                    //file_put_contents(server_doc_root()."log/recall_sql.log", "\n{$today}\n{$logText}\n", FILE_APPEND);
                    //chmod("log/recall_sql.log", 0600);
                   
                }
                else // если в поле с сообщением были признаки сайтов - записываем логи
                {
                file_put_contents(ROOT.DS."log'.DS.'spam.log", "\n{$today}\nIP:{$ipAddr}\n{$logText}\n", FILE_APPEND);
                chmod(ROOT.DS."log'.DS.'spam.log", 0600);
                $this->data['res'] .= ' <div class="zapis_usluga back shad rad pad margin_rlb1">
                                            В полях для ввода нельзя размещать ссылки на интернет сайты.<br />
                                            Пожалуйста, свяжитесь с нами по телефону.
                                        </div>';
                }
            }
        } else {
            $this->data['res'] = 'No data available.';
        }
        return $this->data;
    }
}
