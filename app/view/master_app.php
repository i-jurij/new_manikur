<div id="mas_form" class="mas_form  margin_bottom_1rem">
  <a href="#master_add" class="buttons">Добавить мастера</a>
  <a href="#master_change_photo" class="buttons">Добавить или изменить фото</a>
  <a href="#master_change" class="buttons">Изменить данные</a>

  <a href="#uv_mastera" class="buttons" >Уволенные мастера</a>
  <a href="#master_del" class="buttons" >Удалить мастера</a>
</div>

<div class="">

    <?php /*
    //подключение к бд, создание если ее нет, создание таблицы masters, если ее нет
    include_once server_doc_root().'admin/mastera-sql-connect.php'; ++
    include_once server_doc_root().'admin/mastera-add-master.php';
    include_once server_doc_root().'admin/mastera-spisok.php';
    include_once server_doc_root().'admin/mastera-change-master.php';
    include_once server_doc_root().'admin/mastera-change-master-photo.php';
    include_once server_doc_root().'admin/mastera-uvoleny.php';
    include_once server_doc_root().'admin/mastera-del-master.php';
*/
    ?>

</div>


<div class="content">
<?php
print '<pre>';
var_dump($data);
print '</pre>';
?>
</div>
