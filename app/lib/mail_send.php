<?php
if (!function_exists('server_doc_root')) {
  function server_doc_root() {
    $sdr = $_SERVER['DOCUMENT_ROOT'];
    if (in_array('new_manikur', explode('/', $_SERVER['DOCUMENT_ROOT']))) { $path = $sdr;}
    else { $path = $sdr.DIRECTORY_SEPARATOR.'new_manikur'; }
    return $path;
  }
}

require_once server_doc_root().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'lib'.DIRECTORY_SEPARATOR.'function'.DIRECTORY_SEPARATOR.'func.php';
// Файлы phpmailer
require server_doc_root().DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'PHPMailer.php';
require server_doc_root().DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'SMTP.php';
require server_doc_root().DIRECTORY_SEPARATOR.'phpmailer'.DIRECTORY_SEPARATOR.'Exception.php';

// в переменную $badIP мы можем со временем вписать IP-адреса некоторых спамеров
//(например тех, которые заполнят Вашу форму вручную)
// IP будет указано в log файле. IP-адреса указываем в кавычках, через запятую,
//пример: ['185.189.114.123', '185.212.171.99',]
$badIP    = [];
$from     = "your_login@gmail.com";
$smtp = 'smtp.gmail.com';
$log = 'Your_Login_in_mail_server';
$ps = 'Your_password_in_mail_server';
$smtpsec = 'tls';
$port = 465;
$to       = "Your@yandex.ru";
$site = 'new_manikur.ru';
$spam     = $_POST["last_name"]; // принимаем данные из скрытого спам-поля
$ipAddr   = $_SERVER['REMOTE_ADDR']; // определяем IP-адрес пользователя
$today    = date('d-m-Y_H-i');
if (empty($_POST["phone_number"])){$phone_number = "";}else {$phone_number = test_input($_POST["phone_number"]);}
if (empty($_POST["name"])){$name = "";}else {$name = test_input($_POST["name"]);}
if (empty($_POST["send"])){$send = "";}else {$send = test_input($_POST["send"]);}
$subject  = "!!! Новая заявка !!! c ".$site;
$title = "Перезвоните клиенту с сайта ".$site;
$message  = '<h1>Перезвоните клиенту!</h1>';
$message .= '<ul>
              <li>Имя: <strong>' . $name . '</strong></li>
              <li>Номер телефона: <strong>'. $phone_number .'</strong></li>
              <li>Сообщение: '. $send .'</li>
            </ul>
            <hr>
            <p>
              <br><a href="new_manikur.ru">new_manikur.ru</a>
            </p>';
$subject  = "=?utf-8?B?".base64_encode($subject)."?=";
$headers  = "From: $from\r\nReply-to: $from\r\nContent-type: text/html; charset=utf-8\r\n";

// если не заполнено скрытое поле и если IP-адрес не находится в нашем чёрном списке
if(!in_array($ipAddr, $badIP) && empty($spam))
{
  $logText = strip_tags($message); // обрезаем лишние теги для log файла
	// а также если в поле с сообщением нет ни одного соответствия адресам сайтов
	// можем добавить любые другие сочетания букв, по аналогии, через пайп, например (\.ua) и прочее
	if(!preg_match("/(www)|(http)|(https)|(@)|(\.ru)|(\.com)|(\.ua)|(\.рф)/i", $send))
  {
    $body = $message;
    $mail = new PHPMailer\PHPMailer\PHPMailer();
    try
    {
        $mail->isSMTP();
        $mail->CharSet = "UTF-8";
        $mail->SMTPAuth   = true;
        //$mail->SMTPDebug = 2;
        $mail->Debugoutput = function($str, $level) {$GLOBALS['status'][] = $str;};
        // Настройки вашей почты
        $mail->Host       = $smtp; // SMTP сервера вашей почты
        $mail->Username   = $log; // Логин на почте
        $mail->Password   = $ps; // Пароль на почте
        $mail->SMTPSecure = $smtpsec;
        $mail->Port       = $port;
        $mail->setFrom($from, $site); // Адрес самой почты и имя отправителя
        // Получатель письма
        $mail->addAddress($to);
        //$mail->addAddress('youremail@gmail.com'); // Ещё один, если нужен
        // Отправка сообщения
        $mail->isHTML(true);
        $mail->Subject = $title;
        $mail->Body = $body;

        // Проверяем отравленность сообщения
        if ($mail->send()) {
          $result = "<p>Письмо отправлено</p>";
          $status = true;
          // записываем логи в файл (если файла нет, то он будет создан автоматически)
          //file_put_contents("tmp/recall_email.log", "\n{$today}\n{$logText}\n", FILE_APPEND); chmod("tmp/recall_email.log", 0600);
          file_put_contents(ROOT.DS.'log'.DS.'recall_email.log', "\n{$today}\n{$logText}\n", FILE_APPEND); 
          //chmod(ROOT.DS.'log'.DS.'recall_email.log', 0600);
        } else {
          $result = "<h2>Ошибка</h2>";
          $status = '<p>Письмо не отправлено</p><p>Проверьте входные данные: логин, пароль, почту в mail_send.php</p>';
          file_put_contents(server_doc_root().DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR."recall_email.log", "\n{$today}\n{$result}\n{$status}\n", FILE_APPEND);
          //chmod(ROOT.DS.'log'.DS."recall_email.log", 0600);
        }
    }
    catch (Exception $e)
    {
        $result = "<h2>Ошибка</h2>";
        $status = "<p>Письмо не было отправлено. Причина ошибки: {$mail->ErrorInfo}</p>";
        file_put_contents(server_doc_root().DIRECTORY_SEPARATOR.'log'.DIRECTORY_SEPARATOR."recall_email.log", "\n{$today}\n{$result}\n{$status}\n", FILE_APPEND); 
        //chmod(ROOT.DS.'log'.DS."recall_email.log", 0600);
    }
    // Отображение результата
    //echo json_encode(["result" => $result, "status" => $status]);
    //echo '<div class="back shad rad pad margin_rlb1">'.$result.$status.'</div>';
  }
}
?>
