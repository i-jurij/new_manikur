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
// Файлы phpmailer
require ROOT.DS.'ppntmt'.DS.'appointment.php';

if (!empty($_POST['master']))
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

    // class Appointement
    $bmw = new Ppntmt\Appointment();
    // if necessary, set values to properties
    $bmw->lehgth_cal = 14;
    $bmw->endtime = "17:00";
    $bmw->tz = "Europe/Simferopol";
    $bmw->org_weekend = array('Сб' => '14:00', 'Sat' => '14:00',
                      'Вс' => '', "Sun" => '',);
    $bmw->rest_day_time = $rest_dts;
    $bmw->holiday =  array('1979-09-18', '2005-05-31',);
    $bmw->period = 60;
    $bmw->worktime = array('09:00', '19:00');
    $bmw->lunch = array("12:00", 40);
    $bmw->exist_app_date_time_arr = $exist_apps;
    $bmw->view_date_format = 'd.m';
    $bmw->view_time_format = 'H:i';
    // get date time
    $bmw->get_app();
    // output result
    print '<div class="back shad rad pad05 mar"><p>'.$bmw->html().'</p></div>';
}
?>
