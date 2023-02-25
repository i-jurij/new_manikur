<?php
if (!empty($data['app'])) {
  if (isset($_GET['prev'])) {
    $date   = htmlentities($_GET['prev']);
  } elseif (isset($_GET['next'])) { //$tomorrow = date("Y-m-d", time() + 86400);
    $date   = htmlentities($_GET['next']);
  } else {
    $date = date("Y-m-d");
  }
  $prev = date("Y-m-d", strtotime($date.'- 1 days'));
  $next = date("Y-m-d", strtotime($date.'+ 1 days'));
  ?>
    <p class="margin_rlb1">
      <a href="zapisi_sutki?prev=<?php echo $prev; ?>" class="back shad rad pad_tb05_rl1 display_inline_block">< </a>
      <span class="back shad rad pad_tb05_rl1 display_inline_block" style="width:10rem;"><?php echo date("d M Y", strtotime($date)); ?></span>
      <a href="zapisi_sutki?next=<?php echo $next; ?>" class="back shad rad pad_tb05_rl1 display_inline_block"> ></a>
    </p>

      <?php
      foreach ($data['app'] as $master => $master_app_data_arr) {
        print '<div class="zapis_usluga back shad rad pad margin_rlb1">';
            echo '<p><b>'.$master.'</b></p>';
              if ( !empty($master_app_data_arr) ) {
                foreach ($master_app_data_arr as $time => $master_app_data) {
                  if ( $master_app_data['den'] == $date ) {
                    echo '<article class="main_section_article">
                              <p>'.$master_app_data['vremia'].'</p>
                              <p>'.$master_app_data['usluga'].' руб.</p>
                              <p>'.$master_app_data['name_client'].': <span style="white-space:nowrap;">'.$master_app_data['tlf_client'].'</span></p>
                          </article>';
                  }
                }
              }
        print '</div>';

      }
} else {
    print 'Нет данных для отображения';
}
