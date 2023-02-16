<div class="adm_content">
<?php
if (!empty($data['res'])) {
  print '<p class="back shad rad pad margin_bottom_1rem">'.$data['res'].'</p>';
} else {
  //del adm from page menu
    foreach ($data['page_list'] as $key => $file) {
      if ($file['page_alias'] !== "adm" && !empty($file['page_admin'])) {
        $pages[] = $file;
      }
    }
  unset($key, $file); $data['page_list'] = [];

  $session = new \App\Lib\Session;

  //check for user status and rebuild data
  //for admin out all page include create delete page
  // !!! DON`T REMOVE THIS START !!! //////////
  if ( password_verify("admin", $session->get("status")) ) {
    //default button for create controller, model, view and insert data of page to db
    echo '<p class="margin_bottom_1rem">
            <a href="' . URLROOT . '/create_delete_page/" class="buttons">Страницы</a>
            <a href="' . URLROOT . '/change_pass/" class="buttons">Пользователи</a>
            <a href="' . URLROOT . '/logs_view/" class="buttons">Просмотр логов</a>
            <a href="' . URLROOT . '/adm/clear/" class="buttons">Чистка логов</a>
          </p>';
  }
  // !!! DON`T REMOVE THIS END !!! ///////////

  //for moder: you can rewrite this
  if ( password_verify("moder", $session->get("status")) ) {
    $allow = ['moder', 'user'];
    foreach ($allow as $value) {
      foreach ($pages as $file) {
        if (in_array($value, $file)) {
          $res[] = $file;
        }
      }
    }
    $pages = $res;
  }
  //for user: you can rewrite this
  if ( password_verify("user", $session->get("status")) ) {
    foreach ($pages as $file) {
      if (in_array('user', $file)) {
        $res[] = $file;
      }
    }
    $pages = $res;
  }

  // print pages list from db
  /* if (!empty($pages) && is_array($pages)) {
    foreach ($pages as $page) {
      echo '<a href="' . URLROOT . '/' . $page['page_alias'] . '">' . $page['page_title'] . '</a> ';
    }
  }
  */

  //or you can rebuild page array again
	if (!empty($pages) && is_array($pages)) {
		foreach ($pages as $page) {
      if ($page['page_alias'] == 'recall_no' or $page['page_alias'] == 'recall_yes' or $page['page_alias'] == 'master_app' or $page['page_alias'] == 'date_app')
      {
        $zvonki_zapisi[] = $page['page_alias'] . '#' . $page['page_title'];
      }
      elseif ( $page['page_alias'] == 'contacts' or $page['page_alias'] == 'grafiki' or $page['page_alias'] == 'price_edit' or $page['page_alias'] == 'masters' )
      {
        $redaktors[] = $page['page_alias'] . '#' . $page['page_title'];
      }
      else
      {
        $oth[] = $page['page_alias'] . '#' . $page['page_title'];
      }
		}
	}

//and print page list
    $arr = array('zvonki_zapisi', 'redaktors', 'oth');
    foreach ($arr as $value) {
        if (!empty($$value)) {
            echo '<div class="pad margin_bottom_1rem">';
            foreach ($$value as $value11)
            {
                list($alias, $title) = explode('#', $value11);
                echo '<a href="' . URLROOT . '/' . $alias . '/" class="buttons">' . $title . '</a> ';
            }
            echo "</div>";
        }
    }
}
?>
</div>
