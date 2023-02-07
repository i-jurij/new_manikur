<div class="content">
<?php
if (!empty($data['res'])){
print_r($data['res']);
} else {
?>
<form action="<?php echo URLROOT.'/redaktor_map/go/'; ?>" method="post" name="map_form" id="map_form" class="margin_bottom_1rem" enctype="multipart/form-data"> 
    <p>
        Вставьте ссылку на iframe карты из Google Map или Яндекс карт. <br />
        Как получить ссылку <a href="https://pr-cy.ru/news/p/8403-kak-prosto-dobavit-kartu-yandeks--ili-google-na-sayt">читайте здесь.</a>
        <a href="https://www.internet-technologies.ru/articles/newbie/kak-sozdat-yandeks-kartu-dlya-svoego-sayta.html">или здесь.</a>
    </p>
      <input type="text" name="map_iframe" placeholder="<iframe src=xxx></iframe>" maxlength="500" />

    <p>Или добавьте изображение карты</p>
    <input type="file" name="map_img" />
</form>
  <div class="mar " id="form-buttons">
    <button class="buttons" form="map_form" type="reset" >Очистить</button>
    <button class="buttons" type="submit" form="map_form">Готово</button>
  </div>
<?php
}?>
</div>
