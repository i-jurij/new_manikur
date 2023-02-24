<?php
if (!empty($data['res'])) {
     print $data['res'];
} else {
    print 'Нет данных для отображения';
    print_r($data['app']);

}


if (isset($_GET['prev']))
{
  $date   = htmlentities($_GET['prev']);
}
elseif (isset($_GET['next'])) //$tomorrow = date("Y-m-d", time() + 86400);
{
  $date   = htmlentities($_GET['next']);
}
else
{
  $date = date("Y-m-d");
}
$prev = date("Y-m-d", strtotime($date.'- 1 days'));
$next = date("Y-m-d", strtotime($date.'+ 1 days'));

 ?>

<div class="content" style="overflow: auto;">
  <p>
    <a href="zapisi_sutki?prev=<?php echo $prev; ?>" class="shad rad pad_tb05_rl1 display_inline_block">< </a>
    <span class="shad rad pad_tb05_rl1 display_inline_block" style="width:10rem;"><?php echo date("d M Y", strtotime($date)); ?></span>
    <a href="zapisi_sutki?next=<?php echo $next; ?>" class="shad rad pad_tb05_rl1 display_inline_block"> ></a>
  </p>

  <table class="table margintb1">
    <thead>
      <tr>
        <th>&nbsp;</th>
        <?php foreach ($time as $value)
        {
          print '<th>'.$value.'</th>';
        } ?>
      </tr>
    </thead>
    <tbody>
      <?php

      foreach ($masters as $master)
      {
        //print '<pre>';
        //print_r($master); /* $m0 = img, $m1 = name, $m4 = sec name, $m2 = last name, $m3 = master phone, $m4 = master spec */
        //print '</pre>';
        echo '<tr>';
        echo '<th class="" id="' .  $master[7] . '">
                '.$master[1].' ' . $master[6] . ' ' . $master[2].'
              </th>';
        $id = "`" . str_replace("`", "``", $master[7]) . "`";
        //$sql = "SELECT ID FROM $id WHERE den >= CURRENT_DATE() AND tlf_client<>'' LIMIT 1";
        $sql = "SELECT ID FROM $id WHERE tlf_client<>'' LIMIT 1";
        //$np = $pdo->query($sql);
        if ($np->rowCount() == 0) //если пусто - ничего не делаем
        {
          $net = 1;
          //print 'К мастеру нет записей';
        }
        else // если строки есть, получим данные о записях на прием
        {
          //$sqll = "SELECT * FROM $id WHERE den >= CURRENT_DATE() AND tlf_client<>''";
          $sqll = "SELECT * FROM $id WHERE tlf_client<>''";
          //$stmt = $pdo->query($sqll);
          $zapisi = $stmt->fetchAll(PDO::FETCH_ASSOC);
          //print_r($zapisi);
        }
        $stmt = null;

        foreach ($time as $ti)
        {
          if ( isset($zapisi) )
          {
            foreach ($zapisi as $zap)
            {
              if ( $zap['vremia'] == $ti && $zap['den'] == $date )
              {
                $serv = str_replace('no_cat', '', str_replace('-', ' ', implode(' руб., ', unserialize($zap['usluga']))));
                $td = $serv.' руб.<br />'.$zap['nam_client'].'<br />'.$zap['tlf_client'];
                break;
              }
              else
              {
                $td = '';
              }
            }
          }
          else
          {
            $td = '';
          }

          echo '<td>'.$td.'</td>';
        }
        echo '</tr>';
      } ?>
    </tbody>
  </table>
</div>
