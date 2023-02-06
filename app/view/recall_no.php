<div class="adm_recall_content">

  <div class="back_pad_mar">
    Выберите номера по которым уже перезвонили, поставив галочку. <br />
    Нажмите кнопку "Перезвонили", чтобы убрать их из списка,<br />
    или "Сбросить", чтобы снять выбранное.
  </div>

  <form action="" method="post" class="">
    <div class="margintb05">
      <input type="submit" class="buttons" name="submit" value="Перезвонили"/>
      <input type="reset" class="buttons" value="Cбросить"/>
    </div>
    <div class="flex adm_recall_article_container">
      <?php
      print $data['res'];
      ?>
  </div>
 </form>
</div>
