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
                <button class="buttons form-recall-reset" type="reset" onclick="Reset()">Очистить</button>
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
        /*
        print '<pre>';
        var_dump($data);
        print '</pre>';
        */
        print $data['delete_form'];
        ?>
        </p>
    </div>
    <?php
} else {
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

