<?php
	if (!empty($data['page_list']) && is_array($data['page_list'])) {
		foreach ($data['page_list'] as $page) {
			if ($page['page_alias'] !== "home" && empty($page['page_admin'])) {
				$tit = (!empty($page['page_title'])) ? $page['page_title'] : 'Title';
				$desc = (!empty($page['page_meta_description'])) ? $page['page_meta_description'] : 'Description';

				print '<article class="main_section_article ">
				<a class="main_section_article_content_a" href="' . $page['page_alias'] . '/" >
				<div class="main_section_article_imgdiv">
				<img src="' . get_page_image($page['page_alias']).'" alt="Фото ' . $page['page_title'] . '" class="main_section_article_imgdiv_img" />
				</div>

				<div class="main_section_article_content">
					<h3>' . $tit . '</h3>
					<span>
					' . $desc . '
					</span>
				</div>
				</a>
			</article>'
			. PHP_EOL;
			}
		}
	} 
?>

<div class="content" style="text-align: left;"> 
<?php
	if (!empty($data['page_db_data'][0]['page_content'])) {
		if (is_array($data['page_db_data'][0]['page_content'])) {
			echo '<table>';
			foreach($data['page_db_data'][0]['page_content'] as $row)
			{
				if (is_array($row)) {
					echo '<tr>';
					foreach ($row as $value) {
						echo '<td>'.htmlentities($value).'</td>';
					}
					echo '</tr>';
				}
				else {
					print htmlentities($row).'<br />';
				}
			}
			echo '</table>';
		} else {
			print htmlentities($data['page_db_data'][0]['page_content']);
		}
	}

/*
print '<pre>';
print_r($data['page_list']);
print '</pre>';
*/
?>
</div>

<script type="text/javascript">
	document.addEventListener("DOMContentLoaded", function(event) { 
		let body = document.querySelector ( 'body' );
		body.innerHTML = body.innerHTML + '<p id="enter">FirstFramework</p>';
		
		document.querySelector("#enter").addEventListener('click', function(){
			let text = '<div class="content">\
						<div class="mar">\
							Для входа в панель управления нажмите "Да".<br />\
							Потребуется ввод логина и пароля.<br />\
							Нажмите "Нет", чтобы вернуться обратно.\
						</div>\
						<div class="margintb1" style="max-width:35rem;">\
							<a href="<?php echo URLROOT; ?>/adm" class="buttons display_inline_block" style="width:40%;">Да</a>\
							<a href="" class="buttons display_inline_block" style="width:40%;">Нет</a>\
						</div>\
						</div>';
			document.querySelector(".main_section .flex_top").innerHTML = text;
			window.scrollTo(0, 0);
		});
	});

</script>