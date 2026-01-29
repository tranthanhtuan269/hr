<h4 class="title-fuction">Danh mục</h4>
<p><a href="<?php echo e(url('toh_hrm/vay-von/index')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Tín dụng</a></p>
<script src="<?php echo e(asset('js/dropzone.js')); ?>"></script>
<?php /* <link rel="stylesheet" type="text/css" href="<?php echo e(asset('css/dropzone.css')); ?>"> */ ?>
<script>
    Dropzone.autoDiscover = false;
    var uploadedDocumentMap = {}
    check_action_droponejs = false;

    $(document).on("click",".dz-image img",function() {
        window.open('<?php echo e(url("images")); ?>' + '/' + $(this).attr('alt'), '_blank');
    });
    
</script>
<style>
    i.fa-times {
        color: red;
        font-size: 18px;
    }
    .dz-filename{
        height: 40px;
        overflow: hidden;
        padding: 0 5px;
    } 
    .dz-filename span{
        word-wrap: break-word;
    }    
    .dropzone {
        cursor: pointer;
        background: white;
        border-radius: 5px;
        border: 2px dashed rgb(0, 135, 247);
        border-image: none;
        max-width: 100%;
        padding: 30px;
        margin-left: auto;
        margin-right: auto;
    }

    /* .dz-success-mark,.dz-error-mark,.dz-filename{
        display: none;
    } */
    .dz-image img{
        height: 100px;
    }
    .dz-image {
        padding-bottom: 6px;
    }
    .dz-preview {
        width: 20%;
        float: left;
        text-align: center;
        padding-bottom: 10px;
    }
    .dz-default.dz-message {
        text-align: center;
        margin-bottom: 10px;
    }


    #ui-datepicker-div{
        z-index: 9999 !important;
    }
    #loanEstimate label{
        display: initial;
    }
    #loanEstimate  .tableFixHead {
        overflow-y: auto;
        height: 250px;
    }

    #loanEstimate  .tableFixHead table {
        border-collapse: collapse;
        width: 100%;
    }

    #loanEstimate  .tableFixHead th {
        position: sticky;
        top: -1px;
        background: #eee;
    }
</style>

<?php if(isset($time_complete_file)): ?>
    <p>
        <a href="javascript:void(0)" data-toggle="modal" data-target="#loanCompleteFile"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Hoàn tất hồ sơ </a> 
        <?php if($number_day_rest != ''): ?>
            (còn <?php echo e($number_day_rest); ?> ngày để hoàn tất)
        <?php endif; ?>
    </p>
        <div id="loanCompleteFile" class="modal fade" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Hoàn tất hồ sơ</h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-sm-12 form-group">
                                <label>Hồ sơ</label>
                                <div class="dropzone dz-clickable clearfix" id="myDropMenu">
                                    <div class="dz-default dz-message" data-dz-message="">
                                        <i class="fa fa-upload fa-4x" aria-hidden="true"></i>
                                        <?php /* <span>Kéo thả file ảnh của bạn vào đây để upload</span> */ ?>
                                    </div>
                                </div>
                                <!-- Dropzone Preview Template -->
                                <div id="preview-template-menu" style="display: none;">
                                    <div class="dz-preview dz-file-preview">
                                        <div class="dz-image"><img data-dz-thumbnail=""></div>
            
                                        <div class="dz-details">
                                            <?php /* <div class="dz-size"><span data-dz-size=""></span></div> */ ?>
                                            <div class="dz-filename"><span data-dz-name=""></span></div>
                                        </div>
                                        <div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress=""></span></div>
                                        <div class="dz-error-message"><span data-dz-errormessage=""></span></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 form-group">
                                <div class="alert alert-danger fade in alert-dismissible">
                                    <a href="#" class="close" data-dismiss="alert" aria-label="close" title="close">×</a>
                                    <p>Hồ sơ hoàn thiện: Giấy chứng nhận quyền sử dụng (sổ đỏ) hoặc hợp đồng mua bán nếu mua nhà theo tiến độ, đăng ký xe.</p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" onclick="loanCompleteFile()">Cập nhật</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            $(document).ready(function(){
                myDroponeMenu = new Dropzone("div#myDropMenu", 
                { 
                    paramName: "files", // The name that will be used to transfer the file
                    addRemoveLinks: true,
                    uploadMultiple: true,
                    autoProcessQueue: false,
                    parallelUploads: 50,
                    maxFilesize: 50, // MB
                    thumbnailWidth: null,
                    thumbnailHeight: null,
                    dictRemoveFile: '<i class="fa fa-times fa-3" aria-hidden="true"></i>',
                    // acceptedFiles: ".png, .jpeg, .jpg, .gif",
                    previewTemplate: document.querySelector('#preview-template-menu').innerHTML,
                    url: "<?php echo e(route('droponejs-file')); ?>",
                    headers: {
                        'X-CSRF-TOKEN': "<?php echo e(csrf_token()); ?>"
                    },
        
                    success: function(file, response){
              
                        $(".ajax_waiting").removeClass("loading");

                        Swal.fire({
                            type: 'success',
                            html: response.message,
                        }).then(function(result){
                            if(result.value){
                                location.reload();
                            }
                        })

                    },
                    accept: function(file, done) {
                        check_action_droponejs = true;
                        done();
                    },
                    error: function(file, message, xhr){
                        file.previewElement.remove()

                        Swal.fire({
                            type: 'warning',
                            html: message,
                        })
                        
                    },
                    sending: function(file, xhr, formData) {
                        
                    },
                    complete: function complete(file) {
                        if (file._removeLink) {
                            file._removeLink.innerHTML = this.options.dictRemoveFile;
                        }
                        if (file.previewElement) {
                            return file.previewElement.classList.add("dz-complete");
                        }
                    },
                    init: function() {
                        var thisDropzone = this;
                        str = '<?php echo e($file); ?>';
                        data = str.split(",");

                        $.each(data, function(key,value){
                            if (value) {
                                var mockFile = { name: value };
                                thisDropzone.options.addedfile.call(thisDropzone, mockFile);
                                var ext = value.split('.').pop();

                                if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                                    var image_path = "<?php echo e(asset('images/general/document.png')); ?>";
                                } else {
                                    var image_path = "<?php echo e(url('/images')); ?>" + "/" +value;
                                }

                                thisDropzone.options.thumbnail.call(thisDropzone, mockFile, image_path);
                                $('#loanCompleteFile').append('<input type="hidden" name="file_old[]" value="' + value + '" data-id="0">')
                                uploadedDocumentMap[value] = value
                            }
                        });

                        this.on('addedfile', function(file) {
                            if (file.size == 0) {
                                Swal.fire({
                                    type: 'warning',
                                    html: 'Xin vui lòng nhập file hợp lệ!',
                                })
                                
                                file.previewElement.remove()
                                return;
                            }

                            var ext = file.name.split('.').pop();

                            if (ext != "png" && ext != "jpeg" && ext != "jpg" && ext != "gif" && ext != "webp") {
                                $(file.previewElement).find(".dz-image img").attr("src", "<?php echo e(asset('images/general/document.png')); ?>");
                            }

                            if (file.name in uploadedDocumentMap) {
                                Swal.fire({
                                    type: 'warning',
                                    html: 'File đã tồn tại!',
                                })
                                file.previewElement.remove()
                            } else {
                                uploadedDocumentMap[file.name] = file.name
                            }

                        });


                        this.on('removedfile', function(file) {
                            file.previewElement.remove()
                            var name = uploadedDocumentMap[file.name]
                            $('#loanCompleteFile').find('input[name="file_old[]"][value="' + name + '"]').attr('data-id', 1)
                            delete uploadedDocumentMap[file.name];
                        });

                        // this.on('thumbnail', function(file, dataUri) {
                        //     arr_link_base64_menu.push(dataUri);
                        // });
                    },
                });
            });


            // $('#loanCompleteFile').on('hidden.bs.modal', function (e) {
            //     myDroponeMenu.removeAllFiles(true); 
            // })

            function loanCompleteFile(){

                $.ajaxSetup(
                {
                    headers:
                    {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var file_old_delete = $('#loanCompleteFile input[name^=file_old][data-id=1]').map(function(idx, elem) {
                    return $(elem).val();
                }).get()

                var file_old = $('#loanCompleteFile input[name^=file_old][data-id=0]').map(function(idx, elem) {
                    return $(elem).val();
                }).get()
                // console.log(file_old_delete)
                // console.log(file_old)
                var data = {
                            file_old : file_old,
                            file_old_delete : file_old_delete,
                        };

                $.ajax({
                    method: "POST",
                    url: '<?php echo e(url("toh_hrm/api/loan-complete-file")); ?>',
                    data:data, 
                    dataType: 'json',
                    beforeSend: function() {
                        $(".ajax_waiting").addClass("loading");
                    },
                    complete: function() {
                        if (check_action_droponejs == false) {
                            $(".ajax_waiting").removeClass("loading");
                        }
                    },
                    success: function (response) {
                        if(response.status == 200){
                            myDroponeMenu.processQueue()
                            
                            if (check_action_droponejs == false) {
                                Swal.fire({
                                    type: "success",
                                    html: response.message,
                                    allowOutsideClick: false
                                }).then(function(result){
                                    if(result.value){
                                        location.reload();
                                    }
                                })
                            }

                        } else if(response.status == 401) {
                            Swal.fire({
                                type: "warning",
                                html: response.message,
                                allowOutsideClick: false
                            })
                            
                            $('#loanRegister').modal('toggle');
                        } else{
                        
                            var obj_errors = response.message;
                            var txt_errors = '';
                            for (k of Object.keys(obj_errors)) {
                                txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                            }
                            Swal.fire({
                                type: 'warning',
                                html: txt_errors,
                                allowOutsideClick: false
                            })
                        }
                    },
                    error: function (error) {
                    
                        console.log(error)
                        var obj_errors = error.responseJSON;
                        var txt_errors = '';
                        for (k of Object.keys(obj_errors)) {
                            txt_errors += '<p style="text-align: left;text-align: justify">' + obj_errors[k][0] + '</p>';
                        }
                        Swal.fire({
                            type: 'warning',
                            html: txt_errors,
                        })
                    }
                });
                
            } 
        </script>
<?php endif; ?>

<?php if(in_array('vay-von-quan-ly',$arr_route)): ?>
    <p><a href="<?php echo e(url('toh_hrm/vay-von/quan-ly?selectDepart=0&status=-1')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Quản lý</a></p>
<?php endif; ?>

<?php if(Auth::user()->id == 1): ?>
    <p><a href="<?php echo e(url('toh_hrm/vay-von/diem-tin-nhiem')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Danh sách điểm tín nhiệm</a></p>
<?php endif; ?>

<?php /* <p><a href="<?php echo e(url('toh_hrm/vay-von/quan-ly-ho-so')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Phê duyệt hồ sơ</a></p> */ ?>
<?php if(in_array('vay-von-cau-hinh',$arr_route)): ?>
    <p><a href="<?php echo e(url('toh_hrm/vay-von/cau-hinh')); ?>"><i class="fa fa-angle-double-right" aria-hidden="true"></i> Cấu hình</a></p>
<?php endif; ?>

