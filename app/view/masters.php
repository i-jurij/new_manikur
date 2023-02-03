<?php
//end
if (!empty($data['res'])) {
?>
<div class="content">
    <p>
    <?php
    print $data['res'];
    ?>
    </p>
</div>
<?php
} elseif (!empty($data['add_form'])) {
    ?>
	<form action="<?php echo URLROOT.'/masters/add'; ?>" method="post"  enctype="multipart/form-data" class=" content" id="form_recall">
        <div class="form-recall-main">
            <div class="pers">Добавить данные мастера:</div>
                <div class="form-recall-main-section">
                    <div class=" flex">
                        <input type="text" placeholder="Имя мастера" name="master_name" id="master_name" maxlength="30" required></input>
                        <input type="text" placeholder="Отчество мастера" name="sec_name" id="sec_name" maxlength="30"></input>
                        <input type="text" placeholder="Фамилия мастера" name="master_fam" id="master_fam" maxlength="30" required></input>
                        <input type="tel" name="master_phone_number"  id="master_number" class="number" title="Формат: +7 999 999 99 99" minlength="6" maxlength="17"
                                placeholder="+7 ___ ___ __ __" pattern="(\+?7|8)?\s?[\(]{0,1}?\d{3}[\)]{0,1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?" required>
                        </input>
                        <div id="error"><small></small></div>
                        <input type="text" placeholder="Основная специальность" name="spec" id="spec" maxlength="50" required></input>
                    </div>
            </div>

            <div class="form-recall-main-section">
                <button class="buttons form-recall-submit" type="submit" id="upload" form="form_recall">Добавить</button>
                <button class="buttons form-recall-reset" type="reset" onclick="Reset()" form="form_recall">Очистить</button>
            </div>
            <br class="clear" />

        </div>
	</form>
    <?php
} elseif (!empty($data['delete_form'])) {
    ?>
    <div class="content">
        <p>
        <?php
        print $data['delete_form'];
        ?>
        </p>
    </div>
    <?php
} elseif (!empty($data['uv_mastera'])) {
    ?>
    <div class="content">
        <form action="" method="post"  enctype="multipart/form-data" class="" id="uv_mastera">
            <p>
            <?php
            if (is_array($data['uv_mastera'])) {
                foreach ($data['uv_mastera'] as $uv_master)
            {
                $img = get_master_photo($uv_master['id']);
                echo '
                <article class="main_section_article ">
                    <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">
                    <img src="' . $img . '" alt="Фото ' . $uv_master['master_fam'] . '" class="main_section_article_imgdiv_img" />
                    </div>

                    <div class="main_section_article_content">
                        <h3>' . $uv_master['master_name'] . ' ' . $uv_master['sec_name'] . ' ' . $uv_master['master_fam'] . '</h3>
                        ' . $uv_master['master_phone_number'] . '<br /r>
                        ' . $uv_master['spec'] . '<br />
                        Добавлен: <br />' . $uv_master['data_priema'] . '<br />
                        Уволен: <br />' . $uv_master['data_uvoln'] . '<br />
                        <button type="submit" name="recover" class="buttons" value="' . $uv_master['id'] . '" form="uv_mastera">Вернуть в коллектив</button>
                    </div>
                </article>';
            }
            } elseif (is_string($data['uv_mastera'])) {
                print $data['uv_mastera'];
            }
            ?>
            </p>
        </form>
    </div>
    <?php
}
// step 1 change photo master - form for choose master with input name = "change_photo_form"
elseif (!empty($data['choose_master'])) {
    ?>
    <div class="content">
        <p>Выберите мастера</p>
        <p>
        <?php
        echo '<form action="" id="fotom" method="post" class="">';
        if (is_string($data['choose_master'])) {
            print test_input($data['choose_master']);
        } elseif ( is_array($data['choose_master']) && !empty($data['choose_master']) ) {
            foreach ($data['choose_master'] as $master)
            {
                $img = get_master_photo($master['id']);
                $postdata = $master['master_name'] . '_' . $master['sec_name'] . '_' .$master['master_fam'] . '_' . $master['id'];
                echo '<button type="submit" class="buttons" name="master" value="'.$postdata.'" form="fotom" >
                        <img src="'.$img.'" alt="Фото '.$master['master_fam'].'" width="128px"/>
                        <p>' . $master['master_name'] . ' ' . $master['sec_name'] . ' ' . $master['master_fam'] . '<br />'.$master['master_phone_number'].'</p>
                      </button>
                    ';
            }
        }
        echo '</form>';
        ?>
        </p>
    </div>
    <?php
}
// step 2 change photo master - form for change photo with input name = "change_photo"
elseif (!empty($data['change_photo'])) {
    list($name, $sec_name, $master_fam, $id) = explode('_', $data['change_photo']);
    ?>
    <div class="content">
        <p>
            <form action="" method="post"  enctype="multipart/form-data" class="" id="mcfoto">
                <p><?php echo $name . ' ' . $sec_name . ' ' . $master_fam; ?></p>
                <div>
                    <p>Выберите фото мастера</p>
                    <input type="hidden" name="MAX_FILE_SIZE" value="1024000" />
                    <input type="file" name="photom" id="mfoto" accept=".jpg,.jpeg,.png, .webp, image/jpeg, image/pjpeg, image/png, image/webp" />
                </div>
                <div class="mar pad">
                    <input type="hidden" value="<?php echo $master_fam . '_' . $id; ?>" name="change_photo" id="namemas" />
                    <button class="buttons" type="submit" form="mcfoto">Загрузить</button>
                    <button class="buttons" type="reset" onclick="Reset();">Очистить</button>
                </div>
            </form>
        </p>
    </div>
    <?php
} elseif (!empty($data['master'])) {

    ?>
    <div class="content">
        <p> <form action="" id="changem" method="post" class="">
            <p><b>Список работающих мастеров</b></p>
            <?php
            if (is_array($data['master']) && !empty($data['master'])) {
                foreach ($data['master'] as $master)
                {
                    echo '<button type="submit" class="buttons" name="master" value="'.$master['id'].'" form="changem" >
                            <p>' . $master['master_name'] . ' ' . $master['sec_name'] . ' ' . $master['master_fam'] . '<br />'.$master['master_phone_number'].'</p>
                        </button>
                        ';
                }
            } else {
                print "Список пуст.";
            }
            ?>
            <p><b>Список уволенных мастеров</b><p>
            <?php
            if (is_array($data['uv_master']) && !empty($data['uv_master'])) {
                foreach ($data['uv_master'] as $uv_master)
                {
                    echo '<button type="submit" class="buttons" name="master" value="'.$uv_master['id'].'" form="changem" >
                            <p>' . $uv_master['master_name'] . ' ' . $uv_master['sec_name'] . ' ' . $uv_master['master_fam'] . '<br />'.$uv_master['master_phone_number'].'</p>
                        </button>
                        ';
                }
            } else {
                print "Список пуст.";
            }
            ?>
        </form></p>
    </div>
    <?php
} elseif (!empty($data['change'])) {
    ?>
    <div class="content">
        <p>
        <form action="" method="post" class="" id="master_change_form">
              <div class="form-recall-main">
                <div class="pers"><?php echo $data['change']['master_name'] . ' ' . $data['change']['sec_name'] . ' ' . $data['change']['master_fam']; ?></div>

                <div class="form-recall-main-section">
                  <div class=" flex">
                    <input type="text" placeholder="Фамилия: <?php echo $data['change']['master_fam']; ?>" name="master_f" id="master_f" maxlength="30"></input>
                    <input type="tel" name="master_pn"  id="master_pn" class="number" title="Формат: +7 999 999 99 99" placeholder="+7 ___ ___ __ __" pattern="(\+?7|8)?\s?[\(]{0,1}?\d{3}[\)]{0,1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?\d{1}\s?[-]{0,1}?" ></input>
                    <div id="error"><small></small></div>
                    <input type="text" placeholder="Специальность: <?php echo $data['change']['spec']; ?>" name="master_spec" id="master_spec" maxlength="50"></input>
                    <input type="hidden" value="<?php echo $data['change']['id']; ?>" name="m_id" id="m_id" />
                 </div>

                 <div class="">
                  <p>Дата увольнения:</p>
                  <input type="text" placeholder="10.09.2020" name="data_uvoln" id="data_uvoln" maxlength="30"></input>
                </div>
              </div>

                <div class="form-recall-main-section">
                  <button class="buttons form-recall-submit" type="submit" form="master_change_form">Записать</button>
                  <button class="buttons form-recall-reset" type="reset" onclick="Reset()">Очистить</button>
                </div>
                <br class="clear" />

              </div>
            </form>
        </p>
    </div>
    <?php
}
// start
else {
    ?>
    <div id="mas_form" class="mas_form  margin_bottom_1rem">
      <a href="<?php echo URLROOT; ?>/masters/add_form" class="buttons">Добавить мастера</a>
      <a href="<?php echo URLROOT; ?>/masters/change_photo" class="buttons">Добавить или изменить фото</a>
      <a href="<?php echo URLROOT; ?>/masters/change" class="buttons">Изменить данные</a>
      <a href="<?php echo URLROOT; ?>/masters/uv_mastera" class="buttons" >Уволенные мастера</a>
      <a href="<?php echo URLROOT; ?>/masters/delete_form" class="buttons" >Удалить мастера</a>
    </div>
    <?php
}

?>

