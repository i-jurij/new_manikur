<?php
if (!empty($data['res'])) {
    print $data['res'];
}
elseif ( !empty($data['category']) ) {
  echo 'ccc';
} elseif ( !empty($data['service']) ) {
    echo 'sss';
} else {
    //вывод списка страниц
    if (isset($data['service_page'])) {
        ?>
        <div class="content">
        <form action="" method="post" class="" id="change_page_form">
            <div class="form_radio_btn" style="width:85%;">
                <p class="">Выберите страницу для редактирования:</p>
                <?php
                foreach ($data['service_page'] as $value)
                {
                    echo '<label class="" for="' . $value ['page_id'] . '">
                            <input type="radio" name="page_for_edit" value="' . $value['page_id'] . '" id="' . $value ['page_id']. '" />
                            <span>' . $value['page_title'] . '</span>
                        </label>
                        ';
                }
                ?>
            </div>

            <div class="form_radio_btn" style="width:85%;">
                <p class="">Выберите действие:</p>
                <label for="add_usl">
                    <input type="radio" name="cat_edit" id="add_usl" value="add_usl" />
                    <span>Добавить категории</span>
                </label>
                <label for="del_usl">
                    <input type="radio" name="serv_edit" id="del_usl" value="del_usl" />
                    <span>Удалить категории</span>
                </label>
                <label for="add_usl">
                    <input type="radio" name="cat_edit" id="add_usl" value="add_usl" />
                    <span>Добавить услуги</span>
                </label>
                <label for="del_usl">
                    <input type="radio" name="serv_edit" id="del_usl" value="del_usl" />
                    <span>Удалить услуги</span>
                </label>
            </div>

            <br />js show form after shoose<br />

            <div class="">
                <div id="">
                <div class="" id="num0">
                    <label>Название категории:<br />
                        <input id="cat_name" type="text" name="cat_name[]" placeholder="Название категории" maxlength="100" required />
                    </label><br />
                    <label>Описание категории:<br />
                        <textarea id="cat_desc" name="cat_desc[]" placeholder="Описание категории" maxlength="500"></textarea>
                    </label><br />
                    <label>Изображение категории:<br />
                        <input type="hidden" name="MAX_FILE_SIZE" value="3076000" />
                        <input id="cat_img" type="file" name="cat_img[]" accept=".jpg,.jpeg,.png, .webp, image/jpeg, image/pjpeg, image/png, image/webp" required />
                    </label><br />
                </div>
                </div>
                <button type="button" class="buttons" id="addMore" onclick="">Добавить категорию</button>
            </div>

            del cat: list of cats for choosed page<br />

            add serv: list of cats for choosed page and form for serv add (cat can be or not added in form)<br />

            del serv: list of serv into cat and list other serv<br />

            <div class="margintb1">
                <button type="submit" name="submit" class="buttons" form="change_page_form" value="change" />Далее</button>
                <input type="reset" class="buttons" form="change_page_form" value="Сбросить" />
            </div>
        </form>
        </div>
        <?php
    } else {
        print '<div class="content"><p>Список страниц пуст.</p></div>';
    }
}
include_once APPROOT.DS."view".DS."js_back.html";
?>
