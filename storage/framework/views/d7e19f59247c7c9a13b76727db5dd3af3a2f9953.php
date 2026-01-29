<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $__env->yieldContent('title'); ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/normalize.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/bootstrap.min.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/fontawesome.css')); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/style.css')); ?>">
        <link rel="shortcut icon" href="<?php echo e(asset('images/favicon.ico')); ?>" /> 

        <script src="<?php echo e(asset('js/jquery.min.js')); ?>"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>

    </head>
    <body>

            <div class="main-info">
              
                    <p class="name-company text-center">Công ty phần mềm Tower Hà Nội</p>
                    <p class="name-website text-center">HỆ THỐNG QUẢN LÝ NHÂN SỰ</p>
             
            </div>

            <div id="resetPass">
                <div style="width: 350px; margin: 0 auto; padding-top: 10px">
                    <form>
                        <h3 class="text-center">Đổi mật khẩu</h3>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" class="form-control email" value="<?php echo e(Request::get('email')); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Code</label>
                            <input type="text" class="form-control code" value="<?php echo e(Request::get('reset_code')); ?>" disabled>
                        </div>
                        <div class="form-group">
                            <label>Password</label>
                            <input type="password" class="form-control password">
                            <p class="alert-password alert-errors"></p>
                        </div>
                        <div class="form-group">
                            <label>New password</label>
                            <input type="password" class="form-control confirmpassword">
                            <p class="alert-confirmpassword alert-errors"></p>
                        </div>
                    </form>
                    <div style="text-align: center;">
                        <p class="alert-reset" style="color: #00b8ff;text-align: center;"></p>
                        <button type="button" class="btn btn-primary reset-pass">Cập nhật</button>
                    </div>
                </div>
            </div>
            <style type="text/css">
                .alert-errors{
                    color: red;
                    font-size: small;
                    padding-top: 3px;
                }

            </style>


        <script>
            $( document ).ready(function() {
                $('.reset-pass').click(function(){
                    $('.alert-errors').html('');
                    var email = $('.email').val();
                    var code = $('.code').val();
                    var password = $('.password').val();
                    var confirmpassword = $('.confirmpassword').val();
                    var data = {
                        email : email,
                        code : code,
                        password : password,
                        confirmpassword : confirmpassword
                    }
                    $.ajaxSetup({
                        headers:
                            {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                    });
                    $.ajax({
                        method : "POST",
                        url : "<?php echo e(url('/')); ?>/resetpass-ajax",
                        data : data,
                        dataType : 'json',
                        success: function (response) {
                            if(response.status == 200){
                                Swal.fire({
                                    type: 'success',
                                    text: 'Thay đổi mật khẩu thành công.',
                                }).then((result) => {
                                    top.location.href="<?php echo e(url('/')); ?>";
                                });
                            } else{
                                alert('Mã code đã hết hạn, xin vui lòng lấy mã code khác.')
                            }
                        },
                        error: function (data) {
                            $.each(data.responseJSON, function( index, value ) {
                                $('.alert-' + index).html(value);
                            });
                        }
                    });
                })
            })
        </script>
    </body>
</html>


