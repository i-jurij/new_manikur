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

if ( isset($_POST['usluga']) )
{
  foreach ($_POST['usluga'] as $value) {
    $usluga[] = htmlentities($value);
  }
  $usl = serialize($usluga);
  //echo $usluga3 .'<br />';
}

if ( isset($_POST['master']) )
{
  list($m0,$m1,$m2,$m3,$m4) = explode('#', $_POST['master']);
  //$master2 = htmlentities($_POST['master']);
  //echo $master2 .'<br />';
}

if ( isset($_POST['date'])and isset($_POST['time']) )
{
  $time1 = htmlentities($_POST['time']);
  list($name_of_day, $dat) = explode('&nbsp;', htmlentities($_POST['date']));
  //echo $dat .'<br />';echo $name_of_day .'<br />';
  $date = date('d.m.Y', strtotime($dat));
}

if ( isset($_POST['zapis_phone_number']) )
{
  //$phone = str_replace(' ', '&nbsp;', htmlentities($_POST['zapis_phone_number']));
  $phone = htmlentities($_POST['zapis_phone_number']);
  //echo $phone . '<br />';
}

if ( isset($_POST['zapis_name']) and $_POST['zapis_name'] != '' )
{
  $name = htmlentities($_POST['zapis_name']);
  //echo $name . '<br />';
}
else
{
  $name = '&hellip;';
}


//create or connect db
try {
  $dbh = new PDO("mysql:host=$databaseHost;dbname=$databaseName", $databaseUser, $databasePassword);
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //найдем таблицу с графиком мастера по его id в таблице masters
  $query_for_id = "SELECT id FROM `masters` WHERE master_name = :master_name AND master_fam = :master_fam AND master_phone_number = :master_phone_number ";
  $id = $dbh->prepare($query_for_id);
  $id->bindParam(':master_name', $m1);
  $id->bindParam(':master_fam', $m2);
  $id->bindParam(':master_phone_number', $m3);
  $id->execute();
  $result = $id->fetchColumn();
  $tablec = "`".str_replace("`","``",$result)."`";
  //ochistka bd ot zapisej
  //проверим, что таблица не пустая
  $sql = "SELECT id FROM $tablec LIMIT 1";
  $np = $dbh->query($sql);
  if ($np->rowCount() == 0) //если пусто - ничего не делаем
  {
    //echo'<span class="back_pad_mar">Таблица пуста</span>';
  }
  else { // если строки есть, удалим все старше 3х недель
    $soeddel = "DELETE FROM $tablec WHERE dt < NOW() - INTERVAL 4 WEEK";
    $stmt = $dbh->query($soeddel);
    if ($stmt->rowCount() > 0) {
      //echo '<span class="back_pad_mar">Таблица очищена от записей старше 4х недель</span>';
    }
  }
  //чтобы при перезагрузке страницы не записывалось снова -
  //проверим нет ли записей на тот же день и время и, если нет - запишем данные
  $msql2 = 'SELECT den, vremia FROM' . $tablec;
  $vib_dnt = $dbh->query($msql2);
  $mt = false;

  foreach( $vib_dnt as $dnt )
  {
    if ($dnt['den'] == $dat and $dnt['vremia'] == $time1 )
    {
     $mt = true;
     echo '<p style="margin: 0 auto;">Вы уже записаны на:</p>
          <div class="table_body" style="border-collapse: collapse;">
            <div class="table_row">
             <div class="table_cell" style="text-align:right;">Дата,<br /> время:</div>
             <div class="table_cell">'.$date.',&nbsp;'.$name_of_day.'<br />'.$time1.'</div>
            </div>
          </div>';
     break;
    }
  }

  if ($mt != true)
  {
    $sql = "INSERT INTO `$result` (den, vremia, denned, usluga, nam_client, tlf_client, dt) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $statement = $dbh->prepare($sql);
    $statement->execute(array($dat, $time1, $name_of_day, $usl, $name, $phone, $dt));
    if ($statement->rowCount() > 0)
    {
      //echo "Данные внесены в таблицу";
      echo'<h3><?php echo $name; ?></h3>
            <p><b>Вы записались на:</b></p>
            <div class="table_body" style="border-collapse: collapse;">';
      foreach ($_POST['usluga'] as $value)
      {
        list($page, $cat, $serv, $price) = explode('-',$value);
        if ($cat === 'no_cat') {
          $cat = '';
        }
				else {
					$cat = $cat.':';
				}
        echo '<div class="table_row">
                <div class="table_cell" style="text-align:right;">'.$page.', '.$cat.' '.$serv.'</div>
                <div class="table_cell">'.$price.' руб.</div>
              </div>';
      }
      echo '<div class="table_row">
              <div class="table_cell" style="text-align:right;">Мастер: </div>
              <div class="table_cell">'.$m1.' '.$m2.'</div>
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
  unset($dat, $time1, $name_of_day, $usl, $name, $phone, $dt);
} catch (PDOException $e) {
  print "Error!: " . $e->getMessage() . '<br /><br />';
  die();
}

$dbh = null;

?>
