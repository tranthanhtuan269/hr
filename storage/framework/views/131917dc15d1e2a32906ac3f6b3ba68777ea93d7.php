<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <style type="text/css">
            @import  url("https://fonts.googleapis.com/css?family=Muli:400,400i,700,700i&subset=vietnamese");
            @import  url("https://fonts.googleapis.com/css?family=Roboto+Condensed:400,400i,700,700i&subset=vietnamese");
            body {
                font-family: "Muli", Arial, sans-serif;
                display:flex;
                font-size: 14.5px; 
            }
            

            html,body {
                height:100%;
                width:100%;
                margin:0;
            }
            .container{
            margin: auto;
            }
            .form {
            width: 310px;
            padding: 15px;
            margin: 0 auto;
            text-align: center; 
            
            /*  -webkit-box-shadow: 0px 5px 10px 0px rgba(50, 50, 50, 0.75);
            -moz-box-shadow:    0px 5px 10px 0px rgba(50, 50, 50, 0.75);
            box-shadow:         0px 5px 10px 0px rgba(50, 50, 50, 0.75); */
            }
            .form .logo{margin-bottom: 25px;}
            .form h2 {
            color: #EC5F18;
            font-size: 1.38em;
            text-transform: uppercase;
            }
            .form h3{
            color: #0659D3;
            font-size: 1.225em;
            }
            .form input[type='password']{
            margin-top: 25px;
            }
            .form input[type='email'], .form input[type='password'] {
                font-size: 14px;
            outline: 0;
            width: 100%;
            border: 1px solid #cccccc;
            padding: 10px;
            box-sizing: border-box;

            }
            .login-form{
            margin-top: 35px;
            }
            .login-form input:hover {
            }
            .login-form input:focus {
            border-left: 2px solid #065AD4;
            }
            /* .login-form input:hover {
            border: 3px solid red;
            }
            */
            .login-form input[type='submit']{
                cursor: pointer;
                outline: 0;
                width: 276px;
                height: 76px;
                border: none;
                margin-top: 25px;
                background: url(<?php echo e(asset('images/login/dang_nhap.png')); ?>) ;    
            }




.label-show-modal{
    border-radius: 0;
    color: #0659D3;
    cursor: pointer;
    display: inline-block;
    padding: 10px 0 0 0;
    font-weight: bold;

}
.modal-show {
    background: rgba(0,0,0,0.7);
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    position: fixed;
    text-align: center;
    opacity: 0;
    transform: scale(0);
    visibility: hidden;
    transition: all 0.75s cubic-bezier(0.19, 1, 0.22, 1);
}
.modal-show-inner {
    position: relative;
    margin: 100px auto 20px;
    max-width: 500px;
    width: 80%;
    color: #333;
    background: #fff;
    padding: 10px;
}
.modal-show-inner label{
    position: absolute;
    width: 25px;
    height: 25px;
    background: red;
    text-align: center;
    border-radius: 450%;
    line-height: 25px;
    top: -12.5px;
    right: -12.5px;
    cursor: pointer;
    color: #fff;
}
input:checked ~ .modal-show {
    opacity: 1;
    transform: scale(1);
    visibility: visible;
    transition: all 0.75s cubic-bezier(0.19, 1, 0.22, 1);
}
.form-control {
    width: 80%;
    height: calc(2.25rem + 2px);
    padding: 0 10px;
    font-size: 1rem;
    line-height: 1.5;
    color: #495057;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid #ced4da;
    border-radius: .25rem;
    transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
}
.btn {
    display: inline-block;
    margin-bottom: 0;
    font-weight: normal;
    text-align: center;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    background-image: none;
    border: 1px solid transparent;
    white-space: nowrap;
    padding: 6px 12px;
    font-size: 14px;
    line-height: 1.42857143;
    border-radius: 4px;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    color: #fff !important;
    background-color: #EC5F18 !important;
}

.loading {
    position: fixed;
    z-index: 999;
    height: 2em;
    width: 2em;
    overflow: show;
    margin: auto;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    z-index: 9999999999999;
}


/* Transparent Overlay */

.loading:before {
    content: '';
    display: block;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.3);
}


/* :not(:required) hides these rules from IE9 and below */

.loading:not(:required) {
    /* hide "loading..." text */
    font: 0/0 a;
    color: transparent;
    text-shadow: none;
    background-color: transparent;
    border: 0;
}

.loading:not(:required):after {
    content: '';
    display: block;
    font-size: 10px;
    width: 1em;
    height: 1em;
    margin-top: -0.25em;
    -webkit-animation: spinner 1500ms infinite linear;
    -moz-animation: spinner 1500ms infinite linear;
    -ms-animation: spinner 1500ms infinite linear;
    -o-animation: spinner 1500ms infinite linear;
    animation: spinner 1500ms infinite linear;
    border-radius: 0.25em;
    -webkit-box-shadow: rgb(0, 191, 255) 1.5em 0 0 0, rgb(0, 191, 255) 1.1em 1.1em 0 0, rgb(0, 191, 255) 0 1.5em 0 0, rgb(0, 191, 255) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.25) -1.5em 0 0 0, rgba(0, 0, 0, 0.25) -1.1em -1.1em 0 0, rgb(0, 191, 255) 0 -1.5em 0 0, rgb(0, 191, 255) 1.1em -1.1em 0 0;
    box-shadow: rgb(0, 191, 255) 1.5em 0 0 0, rgb(0, 191, 255) 1.1em 1.1em 0 0, rgb(0, 191, 255) 0 1.5em 0 0, rgb(0, 191, 255) -1.1em 1.1em 0 0, rgb(0, 191, 255) -1.5em 0 0 0, rgb(0, 191, 255) -1.1em -1.1em 0 0, rgb(0, 191, 255) 0 -1.5em 0 0, rgb(0, 191, 255) 1.1em -1.1em 0 0;
}


/* Animation */

@-webkit-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@-moz-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@-o-keyframes spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}

@keyframes  spinner {
    0% {
        -webkit-transform: rotate(0deg);
        -moz-transform: rotate(0deg);
        -ms-transform: rotate(0deg);
        -o-transform: rotate(0deg);
        transform: rotate(0deg);
    }
    100% {
        -webkit-transform: rotate(360deg);
        -moz-transform: rotate(360deg);
        -ms-transform: rotate(360deg);
        -o-transform: rotate(360deg);
        transform: rotate(360deg);
    }
}
                .alert-errors{
                    color: red;
                    margin-top: 5px
                }


        </style>
        <title>HR - Login</title>
        <script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.4.1.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    </head>
    <body>
        <div class="wrapper">
            <input name="modal" type="checkbox" id="modal" style="display:none">
            <div class="modal-show">
                <div class="modal-show-inner">
                    <label for="modal">&#10006;</label>
                    <h2>Lấy lại mật khẩu</h2>
                    <div class="form-group">
                        <input type="email" class="form-control email" id="emailResetPass" placeholder="Nhập địa chỉ email">
                        <p class="alert-emailResetPass alert-errors"></p>
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn" id="sendResetPass"> Gửi </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="container" id="info">
            <div class="form">
                <div class="top-line"></div>
                <div class="logo">
                    <image src="<?php echo e(asset('images/login/logo.png')); ?>" alt="TOH SOFT">
                </div>
                <h3>Công ty phần mềm Tower Hà Nội</h3>
                <h2>Hệ thống quản lý nhân sự </h2>
                <form class="login-form" method="post" action="<?php echo e(url('login')); ?>" >
                    <?php if($errors->has('errorLogin')): ?>
                    <p style="color:red;top: 5px;"><?php echo e($errors->first('errorLogin')); ?></p>
                    <?php endif; ?>
                    <div class="row">
                        <input type="email" name="email" placeholder="Email" value="<?php echo e(old('email')); ?>" />
                        <?php if($errors->has('email')): ?>
                        <span style="color:red;padding-top: 5px; text-align:left"><?php echo e($errors->first('email')); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <input type="password" name="password" placeholder="Mật khẩu"/>
                        <?php if($errors->has('password')): ?>
                        <span style="color:red;padding-top: 5px; text-align:left"><?php echo e($errors->first('password')); ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="row">
                        <input type="submit"  value="">
                    </div>
                    <div class="row">
                        <label for="modal" class="label-show-modal">Quên mật khẩu</label>
                    </div>
                    <?php echo e(csrf_field()); ?>

                </form>
                <div class="bootom-line"></div>
            </div>
        </div>
        <div class="ajax_waiting"></div>
        <script>
            $( document ).ready(function() {
    
                var input = document.getElementById("emailResetPass");
                input.addEventListener("keyup", function(event) {
                if (event.keyCode === 13) {
                event.preventDefault();
                document.getElementById("sendResetPass").click();
                }
                });
    
                $('#sendResetPass').click(function(){
                    // $('.alert-errors').html('');
                    var email = $('.email').val().trim();
                    
                    if (email == '') {
                        $('.alert-emailResetPass').html('Xin vui lòng nhập email hợp lệ.');
                        return;
                    }

                    var data = {
                        email : email
                    }
                    $.ajaxSetup({
                        headers:
                            {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                    });
                    $.ajax({
                        method : "POST",
                        url : "<?php echo e(url('/')); ?>/forgotAjax",
                        data : data,
                        dataType : 'json',
                        beforeSend: function(r, a){
                            $(".ajax_waiting").addClass("loading");
                        },
                        complete: function(r, a){
                            $(".ajax_waiting").removeClass("loading");
                        },
                        success: function (response) {
                            if(response.status == 200){
                                Swal.fire({
                                    type: 'success',
                                    text: 'Chúng tôi đã gửi email cho bạn để đặt lại mật khẩu. Xin vui lòng vào email để kiểm tra.',
                                }).then((result) => {
                                    top.location.href="<?php echo e(url('/login')); ?>";
                                });
                            } else {
                                $('.alert-emailResetPass').html('Xin vui lòng nhập email hợp lệ.');
                            }
                        },
                        error: function (data) {
                            $.each(data.responseJSON.errors, function( index, value ) {
                                $('.alert-' + index).html(value);
                            });
                        }
                    });
                })
            })
        </script>



    </body>
</html>