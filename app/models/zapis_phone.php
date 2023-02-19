<?php
//проверка незанятости времени, если в процессе выбора другой клиент уже занял
//запрос к базе, чтобы получить все записи
//пробежаться по дате, временам, если занято - переменной присвоить true
//добавить в js ниже проверку, если var = true - алерт:
//только что заняли, выберите другое время, обновить страницу, submit disabled
//усли var = false - submit prop('disabled', false
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

if ( !empty($_POST['master']) and !empty($_POST['date']) and !empty($_POST['time']) )
{
    $master_id = test_input($_POST['master']);
    /*
    $m = unserialize(base64_decode($_POST['master']));
    $master_id = $m['id'];
    $master_img = $m['img'];
    $master_name = $m['master_name'];
    $master_fam = $m['master_fam'];
    $master_phone = $m['master_phone_number'];
    $master_spec = $m['spec'];
    */
    $date = htmlentities($_POST['date']);
    $time = test_input($_POST['time']);
    list( $name_of_day, $data ) = explode('&nbsp;', $date);
    $rest_dts = [];
    $exist_apps = [];

    $dbinit = '\App\Lib\\'.DBINITNAME;
    require_once APPROOT.DS.'lib'.DS.'db_init_sqlite.php';
    require_once APPROOT.DS.'lib'.DS.'medoo.php';
    $table = 'app_to_'. $master_id;
	$db = new $dbinit;
    $np = $db->db->select($table,
    ['den', 'vremia', 'tlf_client', 'serv_duration'],
    [ 'den[>=]' => date("Y-m-d") ]);
    if (!empty($np)) {
        // rearrange array
        foreach ($np as $key => $value) {
        if (empty($value['tlf_client'])) {
            if (empty($value['vremia'])) {
            $rest_dts[$value['den']] = [];
            } else {
            $rest_dts[$value['den']][] = $value['vremia'];
            }
        }
        if (!empty($value['tlf_client'])) {
            $exist_apps[$value['den']][$value['vremia']] = $value['serv_duration'];
        }
        }
    }

  if (!empty($exist_apps)) {
    foreach ($exist_apps as $key => $ddate)
    {
      if ($key == $data)
      {
          foreach ($ddate as $ttime)
          {
                if ($ttime == $time)
                {
                  $zaniato = true;
                  break;
                }
          }
          break;
      }
    }
  }

  if ( !isset($zaniato) or $zaniato = false ) //если таблица записей к мастеру пуста - выводим форму
  {
  ?>
    <div class="form-recall-main">
        <h3 class="back shad rad pad margin_bottom_1rem">Введите свое имя и номер телефона для связи</h3>
        <div class="form-recall-main-section">
            <div class="flex">
                <input type="text" placeholder="Ваше имя" name="zapis_name" id="zapis_name" maxlength="50"></input>
                <input type="tel" name="zapis_phone_number"  id="number" class="number"
                    title="Формат: +7 999 999 99 99" placeholder="+7 ___ ___ __ __"
                    minlength="6" maxlength="17"
                    pattern="(\\+?7|8)?\\s?[\(]{0,1}?\\d{3}[\\)]{0,1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?\\d{1}\\s?[-]{0,1}?"
                    required />
                <div id="error"><small></small></div>
            </div>
        </div>
    </div>

    <div class="form-recall-main">
        <p class="pers">
            Отправляя данную форму, вы даете согласие на
            <br>
            <a href="<?php echo URLROOT; ?>/persinfo/">
            обработку персональных данных
            </a>
        </p>
    </div>
  <?php
  }
  elseif (isset($zaniato) and $zaniato = true)
  {
    list( $ye,$mo,$da ) = explode('-', $data);
    echo '<div class="content pers">
            <div class="">' . $time . ' ' .$da.'.'.$mo.'.'.$ye.', '. $name_of_day .
            '<br /> недавно были заняты другим клиентом.<br />Выберите, пожалуйста другое время.
            </div>
          </div>';
  }
}

?>
