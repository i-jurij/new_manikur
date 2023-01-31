<div class="">

    <?php /*
    //подключение к бд, создание если ее нет, создание таблицы masters, если ее нет
    include_once server_doc_root().'admin/mastera-sql-connect.php'; ++
    
    include_once server_doc_root().'admin/mastera-add-master.php'; 
        //добавление мастера второй шаг, если есть пост - запись в бд
    if ( isset($_POST['master_fam']) and $_POST['master_fam'] != '' and isset($_POST['master_phone_number']) and $_POST['master_phone_number'] != '' )
    {
        include_once 'admin/mastera-add-master-sql.php';
        echo $flash ;
    echo'<a href="mastera" ><button class="buttons" type="button">В меню</button></a>';
    }
    //первый шаг добавления мастера - вывести форму
    else
    {
    include_once server_doc_root().'admin/mastera-add-master-form.php';
    include_once server_doc_root().'pages/files/js_back_refresh.html';
    } 
    */

    /*
    include_once server_doc_root().'admin/mastera-spisok.php';
    include_once server_doc_root().'admin/mastera-change-master.php';
    include_once server_doc_root().'admin/mastera-change-master-photo.php';
    include_once server_doc_root().'admin/mastera-uvoleny.php';
    include_once server_doc_root().'admin/mastera-del-master.php';
*/
    ?>

</div>
<?php
//end
if (!empty($data['res'])) {
?>
<div class="content">
    <p>
    <?php
    /*
    print '<pre>';
    var_dump($data);
    print '</pre>';
    */
    print $data['res'];
    ?>
    </p>
</div>
<?php
} elseif (!empty($data['add_form'])) {
    ?>
	<form action="<?php echo URLROOT.'/masters/add'; ?>" method="post"  enctype="multipart/form-data" class="form-recall" id="form_recall">
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
                <button class="buttons form-recall-reset" type="reset" onclick="Reset()" form="form_recall">>Очистить</button>
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
                $img = get_master_photo($uv_master['master_fam'], $uv_master['id']);
                echo '
                <article class="main_section_article ">
                    <div class="main_section_article_imgdiv" style="background-color: var(--bgcolor-content);">
                    <img src="' . $img . '" alt="Фото ' . $uv_master['master_fam'] . '" class="main_section_article_imgdiv_img" />
                    </div>

                    <div class="main_section_article_content">
                        <h3>' . $uv_master['master_name'] . ' ' . $uv_master['sec_name'] . ' ' . $uv_master['master_fam'] . '</h3>
                        <p>' . $uv_master['master_phone_number'] . '</p>
                        <p>' . $uv_master['spec'] . '</p>
                        <p>Добавлен: <br />' . $uv_master['data_priema'] . '</p>
                        <p>Уволен: <br />' . $uv_master['data_uvoln'] . '</p>
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
        foreach ($data['choose_master'] as $master)
        {
            $img = get_master_photo($master['master_fam'], $master['id']);
            $postdata = $master['master_name'] . '_' . $master['sec_name'] . '_' .$master['master_fam'] . '_' . $master['id'];
            echo '<button type="submit" class="buttons" name="master" value="'.$postdata.'" form="fotom" >
                    <img src="'.$img.'" alt="Фото '.$master['master_fam'].'" width="128px"/>
                    <p>' . $master['master_name'] . ' ' . $master['sec_name'] . ' ' . $master['master_fam'] . '<br />'.$master['master_phone_number'].'</p>
                  </button>
                ';
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

