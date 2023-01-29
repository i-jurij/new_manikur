<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
        <link rel="icon" href="<?php echo URLROOT.DS; ?>public/imgs/key.png" />

        <style>
          :root {
              color-scheme: light dark;
            --big-fontsize: 1.5rem;
            --base-fontsize: 1.2rem;
            --middle-fontsize: 1.3rem;
            --small-fontsize: 1rem;
            --base-lineheight: 1.8rem;
            --radius: 0.4rem;
          }

          @media (prefers-color-scheme: dark) {
            :root {
              --bgcolor: #9F091F;
              --bgcolor-content: #282C34;
              --bgcolor-button-active: black;

              --fontcolor-light: #E5B5BE;
              --fontcolor-dark: #1CBADF;

              --fontshadow: 1px 1px 1px rgba(0,0,0,0.9);
              --boxshadow: rgba(0, 0, 0, 0.6) 0px 2px 2px 0px, rgba(0, 0, 0, 0.6) 0px 3px 1px -2px, rgba(0, 0, 0, 0.6) 0px 1px 5px 0px;
              --boxshadow-active: rgba(0, 0, 0, 0.24) 1px 3px 3px 1px, rgba(0, 0, 0, 0.3) 1px 4px 2px -3px, rgba(0, 0, 0, 0.2) 1px 2px 6px 1px;
              --button-disabled-color: Maroon;
            }
          }

          @media (prefers-color-scheme: light) {
            :root {
              --bgcolor: #BE4874;
              --bgcolor-content: rgba(245,243,246,1);
              --bgcolor-button-active: white;

              --fontcolor-light: #BE4874;
              --fontcolor-dark: #1CBADF;

              --fontshadow: 1px 1px 1px rgba(0,0,0,0.3);
              --boxshadow: rgba(0, 0, 0, 0.14) 0px 2px 2px 0px, rgba(0, 0, 0, 0.2) 0px 3px 1px -2px, rgba(0, 0, 0, 0.12) 0px 1px 5px 0px;
              --boxshadow-active: rgba(0, 0, 0, 0.24) 1px 3px 3px 1px, rgba(0, 0, 0, 0.3) 1px 4px 2px -3px, rgba(0, 0, 0, 0.2) 1px 2px 6px 1px;
              --button-disabled-color: MediumVioletRed;
              }
            }

          html, body { height: 100%; }

          html {
            background-color: var(--bgcolor-content, white);
          }

          body {
            font-family: 'Liberation Sans', Arial, "Helvetica CY", Helvetica, "Nimbus Sans L", "Roboto", "Noto Sans", sans-serif;
            font-size: var(--base-fontsize);
            line-height: var(--base-lineheight);
            max-width: 1640px;
            margin: 0 auto;
            text-align: center;
            color: var(--fontcolor-light, red);
          }

            .main_section {
              width: 100%;
              height: 100%;
              position: absolute;
              top: 0;
              right: 0;
              bottom: 0;
              left: 0;
              margin: auto;
            }

            .form {
              width: 100%;
              max-width: 22rem;
              margin:0 auto;
              padding: 1rem;
              border-radius: 0.4rem;
              background: var(--bgcolor-content) none repeat scroll 0% 0%;
              box-shadow: var(--boxshadow);
            }

            .home_p {
              margin: 1rem auto;
              padding: 0 0 1rem 0;
            }

            .home_p a {
              display: inline-block;
              padding: 0 0.5rem;
              width: 8rem;
              text-align: left;
            }

            .home_p a img {
              vertical-align: middle;
              margin: 0 1rem 0 0;
            }

            .form-element {
              margin-bottom: 1rem;
            }

            .buttons {
              font-size: var(--base-fontsize);
              font-family: "Liberation Sans", sans-serif;
              min-width: 5rem;
              width: 100%;
              max-width: 20rem;
              border: 0px solid black;
                padding: 0.5rem 1rem;
                margin: 1rem auto 0 auto;
                text-align: center;
                text-decoration: none;
                color: inherit;
                cursor: pointer;
                border-radius: var(--radius);;
                background-color: var(--bgcolor-content);
                box-shadow: var(--boxshadow);
            }

            .buttons:hover,
            .buttons:focus,
            .buttons:active {
              background-color: var(--bgcolor-button-active);
            }

            .buttons:disabled {
              background-color: var(--bgcolor);
              color: var(--button-disabled-color);
            }

            .imgp {
              margin: 0 auto;
            }

            .div_center {
              display: inline-block;
              margin: 0 auto;
            }

            .captcha {
              display: none;
            }

            .captcha:checked + label img {
              border: 0.5rem solid red;
              border-radius: var(--radius);

            }
            .captcha:checked + label img.access {
              border: 0.5rem solid green;
              border-radius: var(--radius);
            }

            .captcha_img img {
              cursor: pointer;
              margin-right: 0.3rem;
              border: 0.5rem solid #f0e7de;
              border-radius: var(--radius);
              width: 5rem;
            }
        </style>
    </head>
    <body>
          <section class="main_section">
              <div>
                    <p class="home_p"><a class="buttons" href="<?php echo URLROOT; ?>/adm/exit"><img src="<?php echo URLROOT.DS.'public'.DS.'imgs'.DS; ?>home.png" alt="Back"/>Home</a></p>                   
                    <form method="post" action="" name="login_form" class="form" id="login_form">
                        <div class="form-element"><?php echo (isset($_SESSION['flash'])) ? $_SESSION['flash'] : "Enter data for log in"; ?></div>
                        <div class="form-element">
                          <input type="text" name="login" 
                                  value="<?php echo (isset($_POST["login"])) ? htmlentities($_POST["login"]) : null; // Заполняем поле по умолчанию ?>" 
                                  minlength="3" maxlength="25"
                                  placeholder="Name" 
                                  pattern="^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$"
                                  required />
                        </div>

                        <div class="form-element">
                          <input type="password" name="password" id="password" placeholder="Password" minlength="4" maxlength="120" required />
                        </div>

                        <div class="capcha"><div class="imgs div_center" style="width:21rem;"></div></div>

                        <button type="submit" class="buttons sub">Log In</button>
                    </form>
              </div>
          </section>

          <script type="text/javascript">
            function guidGenerator() {
              var S4 = function() {
                return (((1+Math.random())*0x10000)|0).toString(16).substring(1);
              };
              return (S4()+S4()+"-"+S4()+"-"+S4()+"-"+S4()+"-"+S4()+S4()+S4());
            }

            document.addEventListener("DOMContentLoaded", function(event) { 
              var uniqids = [];
              for (var i = 0; i < 6; i++)
              {
                uniqids[i] = guidGenerator(); //random id generated
              }
              var truee = uniqids[Math.floor(Math.random()*uniqids.length)]; //choice random id from ids array

              var strings = [];
              var imgs = [];

              for (var i = 0; i < uniqids.length; i++)
              {
                let ii = i+1;
                imgs[uniqids[i]] = '<img src="public/imgs/captcha_imgs/'+ii+'.jpg" style="width:5rem;" />';
                //console.log(imgs[uniqids[i]]);
                strings[i] = '<input id="captcha_'+uniqids[i]+'" class="captcha" name="dada" value="'+ii+'" type="radio" />\
                <label class="captcha_img" for="captcha_'+uniqids[i]+'">\
                <img src="public/imgs/captcha_imgs/'+ii+'.jpg" id="img_'+uniqids[i]+'"/>\
                </label>';
              }

              elem = document.querySelector('.capcha .imgs');
              elem.insertAdjacentHTML('beforebegin','<div><p class="imgp">'+imgs[truee]+'</p><small>Нажмите на рисунок, похожий на верхний</small></div>');

              for (var i = 0; i < strings.length; i++)
              {
                elem.innerHTML = elem.innerHTML + strings[i];
              }

              document.querySelector("#img_"+truee).className += "access";
              //elem.insertAdjacentHTML('afterend','<div><small>После выбора рисунка нажмите Log in.</small></div>');

              document.querySelector('button.sub').addEventListener('click', function(ev){
                let check = document.querySelector("#captcha_"+truee).checked;
                var re = /^[a-zA-Zа-яА-ЯёЁ0-9-_]{3,25}$/;
                var loginOK = re.exec(document.querySelector('input[name="login"]').value);
                var passOK = document.querySelector('#password').value.length;
                if (passOK > 3 && passOK < 121 && loginOK)
                {
                  if ( check == true )
                  {
                    document.querySelector('form#login_form').submit();
                  }
                  else
                  {
                    ev.preventDefault();
                    alert('Выберите, пожалуйста, соответствующий рисунок :)');
                  }
                }
                else
                {
                  ev.preventDefault();
                  alert('Неправильное имя или пароль :(');
                }
              });

            });
          </script>

    </body>
</html>
