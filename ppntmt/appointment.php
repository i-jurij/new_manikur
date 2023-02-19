<?php
//Copyright © 2023 I-Jurij (yjurij@gmail.com)
//Licensed under the my own license.

namespace Ppntmt;

class Appointment
{
  //properties for setting by user
  public int $lehgth_cal;
  public string $endtime;
  public string $tz;
  public int $period;
  public array $org_weekend;
  public array $rest_day_time;
  public array $holiday;
  public array $lunch;
  public array $worktime;
  public array $exist_app_date_time_arr;
  public string $view_date_format;
  public string $view_time_format;

  public function __construct()
  {
    $this->init();
  }

  protected function init() {
    $this->lehgth_cal = 31;
    $this->endtime = "17:00";
    $this->tz = "Europe/Simferopol";
    $this->org_weekend = array('Сб' => '14:00', 'Sat' => '14:00',
                      'Вс' => '', "Sun" => '',);
    $this->rest_day_time = array('2022-12-17' => array(), '2022-12-15' => ['16:00', '17:00', '18:00'], );
    $this->holiday =  array('1979-09-18', '2005-05-31',);
    $this->period = 60;
    $this->worktime = array('09:00', '19:00');
    $this->lunch = array("12:00", 40);
    $this->exist_app_date_time_arr = ['2022-12-14' => array('11:00' => '', '13:00' => '', '14:30' => null),
                                '2022-12-15' => array('13:00' => '30', '13:30' => '30', '15:00' => 40),
                                '2022-12-16' => ['09:00' => '140'],
                                '2022-12-19' => ['09:00' => '40', '09:40' => '30', '10:10' => '60'], ];
    $this->view_date_format = 'd.m';
    $this->view_time_format = 'H:i';
  }

  public function get_app() {
    $this->all_dates();
    $this->marked_dates();
    $this->round_period();
    $this->times();
    $this->weekend_times();
    $this->rest_times();
    $this->appointment_times();
    $this->result();
  }

  public function en_dayweek_to_rus($dayweek)
  {
    $cyr = ['Вс', 'Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб'];
    $lat = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $dayrus = str_replace($lat, $cyr, $dayweek);
    return $dayrus;
  }

  protected function pre_dates()
  {
    /*
    // variant 1
    $startDate = new \DateTimeImmutable();
    $endDate = new \DateTimeImmutable('+'.$this->lehgth_cal.' day');
    $interval = new \DateInterval('P1D');
    //$period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->modify('+1 day'));
    $period = new \DatePeriod($startDate, $interval, $endDate);
    foreach ($period as $date)
    {
      $engdayweek = $date->format('D');
      $rudayweek = $this->en_dayweek_to_rus($engdayweek);
      $dates[] = $rudayweek . "&nbsp;". $date->format('Y-m-d');
    }
    */
    /*
    //variant 2
    $startDate = new \DateTime();
    for ($i=0; $i < $this->lehgth_cal; $i++) {
      $engdayweek = $startDate->format('D');
      $rudayweek = $this->en_dayweek_to_rus($engdayweek);
      $dates[] = $rudayweek . "&nbsp;". $startDate->format('Y-m-d');
      $startDate->modify('+1 day');
    }
    */
    /*
    // variant 3
    $startDate = new \DateTime();
    $reccurence = $this->lehgth_cal;
    $interval = new \DateInterval('P1D');
    $period = new \DatePeriod($startDate, $interval, $reccurence);
    foreach ($period as $date)
    {
      $engdayweek = $date->format('D');
      $rudayweek = $this->en_dayweek_to_rus($engdayweek);
      $dates[] = $rudayweek . "&nbsp;". $date->format('Y-m-d');
    }
    */
    // variant 4
    $startDate = date('now');
    for ($i=0; $i < $this->lehgth_cal; $i++) {
      $engdayweek = date('D',strtotime($startDate) );
      $rudayweek = $this->en_dayweek_to_rus($engdayweek);
      $dates[] = $rudayweek . "&nbsp;". date('Y-m-d', strtotime($startDate));
      $startDate = date('Y-m-d', strtotime($startDate . ' +1 day'));
    }

    return $dates;
  }

  public function all_dates()
  {
    $now = new \DateTimeImmutable('now', new \DateTimeZone($this->tz));
    $endnow = new \DateTimeImmutable($this->endtime);
    if ($now > $endnow)
    {
      $this->lehgth_cal++;
      $res = $this->pre_dates();
      array_shift($res);
    }
    else
    {
      $res = $this->pre_dates();
    }
    return $res;
  }

  public function marked_dates()
  {
    foreach ($this->all_dates() as $value)
    {
      list( $name_of_day, $data ) = explode('&nbsp;', $value);
      if (  in_array($data, $this->holiday, true))
      {
        $value = $name_of_day.'&nbsp;'.$data.'&nbsp;disabled';
      }
      $r = explode('&nbsp;', $value);
      if (array_key_exists($name_of_day, $this->org_weekend) && !isset($r[2]) && empty($this->org_weekend[$name_of_day]))
      {
        $value = $name_of_day.'&nbsp;'.$data.'&nbsp;disabled';
      }
      $z = explode('&nbsp;', $value);
      if (array_key_exists($data, $this->rest_day_time) && !isset($r[2]) && !isset($z[2]) && empty($this->rest_day_time[$data]))
      {
        $value = $name_of_day.'&nbsp;'.$data.'&nbsp;disabled';
      }
      $app_days[] = $value;
    }
    foreach ($app_days as $key => $value)
    {
      $arr = explode('&nbsp;', $value);
      if ( !isset($arr[2]) ) //if not isset third element that contains "disabled"
      {
        $app_days[$key] = $arr[0].'&nbsp;'.$arr[1].'&nbsp;checked';//checked first work day
        break;//and exit the loop
      }
    }
    return $app_days;
  }

  protected function round_period()
  {
    $round_period = ($this->period > 10 && $this->period < 16) ? 15 : ceil($this->period / 10) * 10;
    return $round_period;
  }

  public function times()
  {
    $start = \DateTimeImmutable::createFromFormat('H:i', $this->worktime[0]);
    $end = \DateTimeImmutable::createFromFormat('H:i', $this->worktime[1]);
    $interval = new \DateInterval('PT'.$this->round_period().'M');
    $times_dtobj = new \DatePeriod($start, $interval, $end);
    foreach ($times_dtobj as $time)
    {
      $times[] = $time->format('H:i');
    }
    return $times;
  }

  public function weekend_times()
  {
    $timearr = $this->times();
    foreach ($this->marked_dates() as $data)
    {
      $arr = explode('&nbsp;', $data); //$arr[0] - weekday, $arr[1] - date, $arr[2] - disabled
      foreach ($timearr as $t)
      {
        $time = \DateTimeImmutable::createFromFormat('Y-m-d_H:i', $arr[1].'_'.$t);
        if ( !isset($arr[2]) || $arr[2] === 'checked' )
        {
            if (array_key_exists($arr[0], $this->org_weekend) && !empty($this->org_weekend[$arr[0]]) )
            {
              $weekend_time = \DateTimeImmutable::createFromFormat('Y-m-d_H:i', $arr[1].'_'.$this->org_weekend[$arr[0]]);
              if ($weekend_time <= $time)
              {
                $times[$arr[1]][] = $t.'&nbsp;disabled';
              }
              else
              {
                $times[$arr[1]][] = $t;
              }
            }
            else
            {
              $times[$arr[1]][] = $t;
            }
        }
      }
    }
    return $times;
  }

  public function rest_times()
  {
    $timearr = $this->weekend_times();
    //create array with all start-end rest hours
    foreach ($this->rest_day_time as $date => $times)
    {
      $res[$date] = array();
      if (isset($times) && !empty($times))
      {
        foreach ($times as $time)
        {
          $start = \DateTimeImmutable::createFromFormat('Y-m-d_H:i', $date.'_'.$time);
          $end = $start->add(new \DateInterval('PT'.$this->round_period().'M'));
          if (isset($pre_end) && $start == $pre_end )
          {
            if ($end < $this->worktime[1])
            {
              array_push($res[$date], $end);
            }
          }
          else
          {
            if ($end < $this->worktime[1])
            {
              array_push($res[$date], $start, $end);
            }
          }
          $pre_end = $end;
        }
      }
    }
    unset($date, $times, $time, $pre_end, $end, $start);

    //merge default datetime and start-end rest hours arrays
    foreach ($timearr as $date => $times )
    {
      if (array_key_exists($date, $this->rest_day_time) && isset($res[$date]) && !empty($res[$date]))
      {
        foreach ($res[$date] as $rest_time)
        {
          //допишем в массив времен времена записей, которых там еще нет
          if (!array_search($rest_time->format('H:i'), $times))
          {
            array_push($timearr[$date], $rest_time->format('H:i'));
          }
        }
      }
      //сортировка всех времен по возрастанию
      sort($timearr[$date], SORT_REGULAR);
    }
    unset($date, $times, $rest_time, $time, $time_pre, $arr, $dt);

    //проверим все времена и пометим все часы отдыха
    foreach ($timearr as $date => $times )
    {
      if (array_key_exists($date, $this->rest_day_time) && isset($this->rest_day_time[$date]) && !empty($this->rest_day_time[$date]))
      {
        foreach ($this->rest_day_time[$date] as $rest_time)
        {
          $start = \DateTimeImmutable::createFromFormat('Y-m-d_H:i', $date.'_'.$rest_time);
          $end = $start->add(new \DateInterval('PT'.$this->round_period().'M'));
          $start_end[$rest_time] = array('start' => $start, 'end' => $end);
        }
        unset($rest_time, $start, $end);

        foreach ($start_end as $rest_time)
        {
          foreach ($times as $key => $time)
          {
            $arr = explode('&nbsp;', $time);
            $dt = \DateTimeImmutable::createFromFormat('Y-m-d_H:i', $date.'_'.$arr[0]);
            if ($dt >= $rest_time['start'] && $dt < $rest_time['end']  && !isset($arr[2]))
            {
              $timearr[$date][$key] = $time.'&nbsp;disabled';
            }
          }
        }
        $start_end = array();
      }
    }
    unset($date, $times, $rest_time, $t, $time, $time_pre, $arr, $dt);
    return $timearr;
  }

  public function appointment_times()
  {
    //default date and time array
    $dt = $this->rest_times();
    foreach ($dt as $date => $times)
    { //если для данной даты есть записи - создаем массив времен записей (начало, конец)
      if (array_key_exists($date, $this->exist_app_date_time_arr))
      {
        $start_end_array = array();
        foreach ($this->exist_app_date_time_arr[$date] as $serv_time => $serv_len)
        {
          $serv_start = \DateTimeImmutable::createFromFormat('Y-m-d_H:i', $date.'_'.$serv_time);
          if (!empty($serv_len))
          {
            //if length of service > 5 then minutes, else hours
            //если длительность услуги меньше 5  - значит обозначено в часах
            $r = ( $serv_len > 5 ) ? 'M' : 'H';
            $serv = ( $serv_len > 5 ) ? $serv_len : ceil(($serv_len * 60) / 10) * 10;
            $serv_end = $serv_start->add(new \DateInterval('PT'.$serv.$r));
          }
          else
          {
            $serv_end = $serv_start->add(new \DateInterval('PT'.$this->round_period().'M'));
          }

          if (isset($pre_serv_end) && $serv_start == $pre_serv_end)
          {
            array_push($start_end_array, $serv_end);
          }
          else
          {
            array_push($start_end_array, $serv_start, $serv_end);
          }
          $pre_serv_end = $serv_end;
        }

        //объединим массив времен для записей и массив с началом и концом каждой записи
        foreach ($start_end_array as $val)
        {
          //допишем в массив времен времена записей, которых там еще нет
          if (!in_array($val->format('H:i'), $times))
          {
            array_push($dt[$date], $val->format('H:i'));
          }
        }

        //сортировка всех времен по возрастанию
        sort($dt[$date], SORT_REGULAR);
      }
    }

    //просмотрим все времена для каждой даты и пометим времена услуг
    foreach ($dt as $date => $times)
    {
      $start_end = array();
      if (array_key_exists($date, $this->exist_app_date_time_arr))
      { //и если для даты есть записи - создадим массив времен с ключами start\end и значениями начала и конца
        foreach ($this->exist_app_date_time_arr[$date] as $serv_time => $serv_len)
        {
          $serv_start = \DateTimeImmutable::createFromFormat('Y-m-d_H:i', $date.'_'.$serv_time);
          if (!empty($serv_len))
          {
            //if length of service > 5 then minutes, else hours
            //если длительность услуги меньше 5  - значит обозначено в часах
            $r = ( $serv_len > 5 ) ? 'M' : 'H';
            $serv = ( $serv_len > 5 ) ? $serv_len : ceil(($serv_len * 60) / 10) * 10;
            $serv_end = $serv_start->add(new \DateInterval('PT'.$serv.$r));
          }
          else
          {
            $serv_end = $serv_start->add(new \DateInterval('PT'.$this->round_period().$r));
          }
          $start_end[$serv_time] = array('start' => $serv_start, 'end' => $serv_end);
        }

        //пометим времена услуг
        foreach ($start_end as $sst => $ttime)
        {
          foreach ($times as $key => $time)
          {
            $arr = explode('&nbsp;', $time);
            $ddt = \DateTimeImmutable::createFromFormat('Y-m-d_H:i', $date.'_'.$arr[0]);
            if ( $ttime['start'] <= $ddt && $ttime['end'] > $ddt && !isset($arr[1]))
            {
              $dt[$date][$key] = $time.'&nbsp;disabled';
            }
          }
        }
      }
    }
    return $dt;
  }

  public function result()
  {
    $lunch_start = \DateTimeImmutable::createFromFormat('H:i', $this->lunch[0]);
    $lunch_end = $lunch_start->add(new \DateInterval('PT'.$this->lunch[1].'M'));
    $app_dt = $this->appointment_times();
    foreach ($app_dt as $date => $timess)
    {
      foreach ($timess as $key => $time)
      {
        $arr = explode('&nbsp;', $time);
        $dt = \DateTimeImmutable::createFromFormat('H:i', $arr[0]);
        if ($lunch_start == $dt && !isset($arr[1]))
        {
          $app_dt[$date][$key] = $time.'&nbsp;disabled';
        }
        elseif (isset($r) && $lunch_start > $r && $lunch_start < $dt)
        {
          array_splice($app_dt[$date], $key, 0, $lunch_start->format('H:i'));
        }
        if ($lunch_end < $dt && isset($r) && $lunch_end > $r && $dt != $lunch_start)
        {
          array_splice($app_dt[$date], $key, 0, $lunch_end->format('H:i'));
        }
        $r = $dt;
      }
    }
    return $app_dt;
  }

  public function html()
  {
    $view = '<div class="master_datetime" id="master_datetime">
              <div class="master_dates">';
    foreach ($this->marked_dates() as $date)
    {
      //разберем на части $date
      //$arr[0] - weekday, $arr[1] - date, $arr[2] - if isset: disabled or checked
      $arr = explode('&nbsp;', $date);
      $dis = (isset($arr[2])) ? $arr[2] : '';
      //list( $year,$month,$day) = explode('-', $arr[1]);
      $view_date = \DateTimeImmutable::createFromFormat('Y-m-d', $arr[1]);
      $view .= '<div class="master_date">
                  <input type="radio" class="dat" id="'.$arr[1].'d" name="date" value="'.$arr[0].'&nbsp;'.$arr[1].'" ' . $dis . ' required />
                  <label for="'.$arr[1].'d">'.$arr[0].'<br />'.$view_date->format($this->view_date_format).'</label>
                </div>';
    }
    $view .= '</div> ';

    foreach ($this->result() as $key => $times_of_date)
    {
      $view .= '<div class="master_times" style="display:none;" id="t' .  $key . 'd"> ';
      foreach ($times_of_date as $time)
      {
        $ar = explode('&nbsp;', $time);
        $t = str_replace(":", "", $ar[0]);
        $dis = (isset($ar[1])) ? $ar[1] : '';
        $view_time = \DateTimeImmutable::createFromFormat('Y-m-d H:i', $key.' '.$ar[0]);
        $view .= '<div class="master_time ">
                    <input type="radio" id="' .  $key .  $t . '" name="time" value="' .  $ar[0] . '" '.$dis.' required />
                    <label for="' .  $key . $t . '">' . $view_time->format($this->view_time_format) . '</label>
                  </div>';
      }
      $view .= '</div> ';
    }
    return $view;
  }
//end class
}

//Copyright © 2023 I-Jurij (yjurij@gmail.com)
//Licensed under the my own license.
