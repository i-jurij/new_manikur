<?php
if (!function_exists('server_doc_root')) {
    function server_doc_root() {
      $sdr = $_SERVER['DOCUMENT_ROOT'];
      if (in_array('new_manikur', explode('/', $_SERVER['DOCUMENT_ROOT']))) { $path = $sdr;}
      else { $path = $sdr.DIRECTORY_SEPARATOR.'new_manikur'; }
      return $path;
    }
}
require_once server_doc_root().DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR.'configs'.DIRECTORY_SEPARATOR.'config.php';
require_once APPROOT.DS.'lib'.DS.'function'.DS.'func.php';

//присваиваем переменным значения для записи в бд
$dt=date("Y-m-d H:i:s");

if ( !empty($_POST['usluga']) )
{
    $usl = test_input($_POST['usluga']);
    $serv_arr = explode("plus", $usl);
    $page = $serv_arr[0];
    if ($serv_arr[1] != 'page_serv') {
        $cat = $serv_arr[1].': ';
    } else {
        $cat = '';
    }
    $serv = $serv_arr[2];
    $price_duration = explode('-', $serv_arr[3]);
    $price = $price_duration[0];
    $duration = $price_duration[1];
}

if ( !empty($_POST['master']) )
{
  $master2 = test_input($_POST['master']);

}

if ( !empty($_POST['date'])and !empty($_POST['time']) )
{
  $time1 = test_input($_POST['time']);
  list($name_of_day, $dat) = explode('&nbsp;', htmlentities($_POST['date']));
  $date = date('d.m.Y', strtotime($dat));
}

if ( !empty($_POST['zapis_phone_number']) )
{
  $phone = htmlentities($_POST['zapis_phone_number']);
}

if ( !empty($_POST['zapis_name']) )
{
  $name = htmlentities($_POST['zapis_name']);
}
else
{
  $name = '&hellip;';
}

if (!empty($usl) && !empty($master2) && !empty($time1) && !empty($date) && !empty($phone) && !empty($name) ) {
    $dbinit = '\App\Lib\\'.DBINITNAME;
    require_once APPROOT.DS.'lib'.DS.'db_init_sqlite.php';
    require_once APPROOT.DS.'lib'.DS.'medoo.php';
    $table = 'app_to_'. $master2;

	$db = new $dbinit;
    $m = $db->db->get("masters", ["master_name", "master_fam"], ["id" => $master2]);
    //delete records older then 1 year
    //$sql = "DELETE FROM `$table` WHERE dt <= date('now','-365 day')";
    //$soeddel = "DELETE FROM $tablec WHERE dt < NOW() - INTERVAL 1 YEAR";
    //чтобы при перезагрузке страницы не записывалось снова -
    //проверим нет ли записей на тот же день и время и, если нет - запишем данные
    $has_app = $db->db->has($table, [
        "AND" => [
            "den" => $dat,
            "vremia" => $time1
        ]
    ]);
    if ($has_app) {
        echo '<p style="margin: 0 auto;">Вы уже записаны на:</p>
          <div class="table_body" style="border-collapse: collapse;">
            <div class="table_row">
             <div class="table_cell" style="text-align:right;">Дата,<br /> время:</div>
             <div class="table_cell">'.$date.',&nbsp;'.$name_of_day.'<br />'.$time1.'</div>
            </div>
          </div>';
    } else {
        /*
        $sql = "INSERT INTO `$result` (den, vremia, denned, usluga, nam_client, tlf_client, dt) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $statement = $dbh->prepare($sql);
        $statement->execute(array($dat, $time1, $name_of_day, $usl, $name, $phone, $dt));
        */
        $statement = $db->db->insert($table, [
            "den" => $dat,
            "vremia" => $time1,
            "denned" => $name_of_day,
            "usluga" => $page.', '.$cat.' '.$serv.' '.$price,
            "name_client" => $name,
            "tlf_client" => $phone,
            "dt" => $dt,
            "serv_duration" => $duration
        ]);
        if ($statement->rowCount() > 0)
        {
          //echo "Данные внесены в таблицу";
          echo'<h3><?php echo $name; ?></h3>
                <p><b>Вы записались на:</b></p>
                <div class="table_body" style="border-collapse: collapse;">';

            if ($cat === 'page_serv') {
              $cat = '';
            }
                    else {
                        $cat = $cat.':';
                    }
            echo '<div class="table_row">
                    <div class="table_cell" style="text-align:right;">'.$page.', '.$cat.' '.$serv.'</div>
                    <div class="table_cell">'.$price.' руб.</div>
                  </div>';
          echo '<div class="table_row">
                  <div class="table_cell" style="text-align:right;">Мастер: </div>
                  <div class="table_cell">'.$m['master_name'].' '.$m['master_fam'].'</div>
                </div>
                <div class="table_row">
                  <div class="table_cell" style="text-align:right;">Дата,<br /> время:</div>
                  <div class="table_cell">'.$date.',&nbsp;'.$name_of_day.'<br />'.$time1.'</div>
                </div>
                <div class="table_row">
                  <div class="table_cell" style="text-align:right;">Ваш номер:</div>
                  <div class="table_cell">'.$phone.' </div>
                </div>';
          echo'</div>
              <h3>Спасибо за ваш выбор!</h3>';
        }
    }
} else {
    echo '<div class="back shad rad pad mar"><p>Недостаточно входных данных.</p></div>';
}
?>
