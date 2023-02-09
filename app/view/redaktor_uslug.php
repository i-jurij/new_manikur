<?php
if (!empty($data['res'])) {
    print $data['res'];
}
elseif ( !empty($data['category']) ) {
  echo 'ccc';
} elseif ( !empty($data['service']) ) {
    echo 'sss';
} else {
?>
<div class="content">
  <p>Выберите страницу -> выберите действие -> нажмите Далее</p>
</div>
<?php
//вывод списка страниц
if (!empty($data['service_page'])) {
?>
<form action="" method="post" class="" id="change_page_form">
    <div class="form_radio_btn back rad shad pad" style="width:85%;">
        <p class="">Выберите страницу для редактирования:</p>
        <?php
        foreach ($data['service_page'] as $value)
        {
            echo '<label class="" for="' . $value . '">
                    <input type="radio" name="page_for_edit" value="' . $value['id'] . '" id="' . $value ['id']. '" />
                    <span>' . $value['title'] . '</span>
                </label>
                ';
        }
        ?>
    </div>

    <div class="">
        <p class="">Выберите действие:</p>
        <label for="add_usl">
            <input type="radio" name="page_edit" id="add_usl" value="add_usl" />
            <span>Добавить или удалить категории</span>
        </label>
        <label for="del_usl">
            <input type="radio" name="page_edit" id="del_usl" value="del_usl" />
            <span>Добавить или удалить услуги</span>
        </label>
    </div>

    <div class="margintb1">
        <button type="submit" name="submit" class="buttons" form="change_page_form" value="change" />Далее</button>
        <input type="reset" class="buttons" form="change_page_form" value="Сбросить" />
    </div>
</form>
        <?php
    } else {
        print '<div class="content"><p>Список страниц пуст.</p></div>';
    }
}
include_once APPROOT.DS."view".DS."js_back.html";
?>
