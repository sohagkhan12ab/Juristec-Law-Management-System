<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Settings')); ?>

<?php $__env->stopSection(); ?>

<?php

    use App\Models\Utility;
    $color = isset($settings['color']) ? $settings['color'] : 'theme-4';
    $logo = Utility::get_file('uploads/logo');

    $logo_light = Utility::getValByName('company_logo_light');
    $logo_dark = Utility::getValByName('company_logo_dark');
    $company_favicon = Utility::getValByName('company_favicon');
    $lang = Utility::getValByName('default_language');
    $file_type = config('files_types');
    $setting = Utility::settings();

    $meta_image = Utility::get_file('uploads/metaevent');

    $local_storage_validation = $setting['local_storage_validation'];
    $local_storage_validations = explode(',', $local_storage_validation);

    $s3_storage_validation = $setting['s3_storage_validation'];
    $s3_storage_validations = explode(',', $s3_storage_validation);

    $wasabi_storage_validation = $setting['wasabi_storage_validation'];
    $wasabi_storage_validations = explode(',', $wasabi_storage_validation);
    $chatgpt_key = Utility::getValByName('chatgpt_key');
    $chatgpt_enable = !empty($chatgpt_key);
    $flag = (!empty($setting['color_flag'])) ? $setting['color_flag'] : '';
?>

<?php $__env->startPush('custom-script'); ?>
    <script>
        $(document).on("click", '.send_email', function(e) {

            e.preventDefault();
            var title = $(this).attr('data-title');

            var size = 'md';
            var url = $(this).attr('data-url');

            if (typeof url != 'undefined') {
                $("#commanModel .modal-title").html(title);
                $("#commanModel .modal-dialog").addClass('modal-' + size);
                $("#commanModel").modal('show');

                $.post(url, {
                    _token: '<?php echo e(csrf_token()); ?>',
                    mail_driver: $("#mail_driver").val(),
                    mail_host: $("#mail_host").val(),
                    mail_port: $("#mail_port").val(),
                    mail_username: $("#mail_username").val(),
                    mail_password: $("#mail_password").val(),
                    mail_encryption: $("#mail_encryption").val(),
                    mail_from_address: $("#mail_from_address").val(),
                    mail_from_name: $("#mail_from_name").val(),
                }, function(data) {
                    $('#commanModel .extra').html(data);
                });
            }
        });


        $(document).on('submit', '#test_email', function(e) {
            e.preventDefault();
            $("#email_sending").show();
            var post = $(this).serialize();
            var url = $(this).attr('action');
            $.ajax({
                type: "post",
                url: url,
                data: post,
                cache: false,
                beforeSend: function() {
                    $('#test_email .btn-create').attr('disabled', 'disabled');
                },
                success: function(data) {
                    if (data.is_success) {
                        show_toastr('success', data.message, 'success');
                    } else {
                        show_toastr('Error', data.message, 'error');
                    }
                    $("#email_sending").hide();
                },
                complete: function() {
                    $('#test_email .btn-create').removeAttr('disabled');
                },
            });
        });

        var themescolors = document.querySelectorAll(".themes-color > a");
        for (var h = 0; h < themescolors.length; h++) {
            var c = themescolors[h];
            c.addEventListener("click", function(event) {
                var targetElement = event.target;
                if (targetElement.tagName == "SPAN") {
                    targetElement = targetElement.parentNode;
                }
                var temp = targetElement.getAttribute("data-value");
                removeClassByPrefix(document.querySelector("body"), "theme-");
                document.querySelector("body").classList.add(temp);
            });
        }

        if ($('#cust-theme-bg').length > 0) {
            var custthemebg = document.querySelector("#cust-theme-bg");
            custthemebg.addEventListener("click", function() {
                if (custthemebg.checked) {
                    document.querySelector(".dash-sidebar").classList.add("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.add("transprent-bg");
                } else {
                    document.querySelector(".dash-sidebar").classList.remove("transprent-bg");
                    document
                        .querySelector(".dash-header:not(.dash-mob-header)")
                        .classList.remove("transprent-bg");
                }
            });
        }

        if ($('#cust-darklayout').length > 0) {
            var custthemedark = document.querySelector("#cust-darklayout");
            custthemedark.addEventListener("click", function() {
                if (custthemedark.checked) {
                    $('#style').attr('href', '<?php echo e(env('APP_URL')); ?>' + '/public/assets/css/style-dark.css');
                    $('#custom-dark').attr('href', '<?php echo e(env('APP_URL')); ?>' + '/public/assets/css/custom-dark.css');
                    $('.dash-sidebar .main-logo a img').attr('src', '<?php echo e($logo . $logo_light); ?>');

                } else {
                    $('#style').attr('href', '<?php echo e(env('APP_URL')); ?>' + '/public/assets/css/style.css');
                    $('.dash-sidebar .main-logo a img').attr('src', '<?php echo e($logo . $logo_dark); ?>');
                }
            });
        }

        var scrollSpy = new bootstrap.ScrollSpy(document.body, {
            target: '#useradd-sidenav',
            offset: 300,
        })

        $(document).ready(function() {
            $(".list-group-item").first().addClass('active');

            $(".list-group-item").on('click', function() {
                $(".list-group-item").removeClass('active')
                $(this).addClass('active');
            });
        })

        function check_theme(color_val) {
            $('input[value="' + color_val + '"]').prop('checked', true);
            $('a[data-value]').removeClass('active_color');
            $('a[data-value="' + color_val + '"]').addClass('active_color');
        }

        $(document).on('change', '[name=storage_setting]', function() {
            if ($(this).val() == 's3') {
                $('.s3-setting').removeClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').addClass('d-none');
            } else if ($(this).val() == 'wasabi') {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').removeClass('d-none');
                $('.local-setting').addClass('d-none');
            } else {
                $('.s3-setting').addClass('d-none');
                $('.wasabi-setting').addClass('d-none');
                $('.local-setting').removeClass('d-none');
            }
        });
    </script>

    <script type="text/javascript">
        function enablecookie() {
            const element = $('#enable_cookie').is(':checked');
            $('.cookieDiv').addClass('disabledCookie');
            if (element == true) {
                $('.cookieDiv').removeClass('disabledCookie');
                $("#cookie_logging").attr('checked', true);
            } else {
                $('.cookieDiv').addClass('disabledCookie');
                $("#cookie_logging").attr('checked', false);
            }
        }
    </script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><?php echo e(__('Settings')); ?></li>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <style>
        .list-group-item.active {
            border: none !important;
        }
    </style>

    <div class="row ">
        <!-- [ sample-page ] start -->
        <div class="col-sm-12">
            <div class="row g-0">
                <div class="col-xl-3 border-end border-bottom">
                    <div class="card shadow-none bg-transparent sticky-top" style="top:70px">
                        <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">
                            <a href="#useradd-1" class="list-group-item list-group-item-action"><?php echo e(__('Brand Settings')); ?>

                                <div class="float-end dark"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-2" class="list-group-item list-group-item-action"><?php echo e(__('Email Settings')); ?>

                                <div class="float-end dark"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-3"
                                class="list-group-item list-group-item-action"><?php echo e(__('Payment Settings')); ?>

                                <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-4" class="list-group-item list-group-item-action"><?php echo e(__('SEO Settings')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-5"
                                class="list-group-item list-group-item-action"><?php echo e(__('ReCaptcha Settings')); ?>

                                <div class="float-end "><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-6" class="list-group-item list-group-item-action"><?php echo e(__('Cache Settings')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-7"
                                class="list-group-item list-group-item-action"><?php echo e(__('Storage Settings')); ?>

                                <div class="float-end dark"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-8" class="list-group-item list-group-item-action"><?php echo e(__('Cookie Settings')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#useradd-9" class="list-group-item list-group-item-action"><?php echo e(__('Pusher Settings')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#location-list" class="list-group-item list-group-item-action"
                                id="contry-city-state"><?php echo e(__('Country/ State/ City Settings')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                            <a href="#chatgpt-settings" id="chatgpt-tab"
                                class="list-group-item list-group-item-action"><?php echo e(__('Chat GPT Key Settings')); ?>

                                <div class="float-end"><i class="ti ti-chevron-right"></i></div>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9" data-bs-spy="scroll" data-bs-target="#useradd-sidenav" data-bs-offset="0"
                    tabindex="0">

                    <!--Business Setting-->
                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-1">
                        <?php echo e(Form::model($settings, ['route' => 'business.setting', 'method' => 'POST', 'enctype' => 'multipart/form-data'])); ?>

                        <div class="card-header">
                            <h5><?php echo e(__('Brand Settings')); ?></h5>
                            <small class="text-muted"><?php echo e(__('Edit your brand details')); ?></small>
                        </div>

                        <div class="card-body pb-0">
                            <div class="row">
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card shadow-none border rounded-0">
                                        <div class="card-header">
                                            <h5><?php echo e(__('Logo dark')); ?></h5>
                                        </div>
                                        <div class="card-body ">
                                            <div class=" setting-card">
                                                <div
                                                    class="d-flex flex-column justify-content-between align-items-center h-100">
                                                    <div class="logo-content mt-4">
                                                        <a href="<?php echo e($logo . '/' . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : '1-logo-dark.png')); ?>"
                                                            target="_blank">
                                                            <img id="blah" alt="your image"
                                                                src="<?php echo e($logo . '/' . (isset($logo_dark) && !empty($logo_dark) ? $logo_dark : '1-logo-dark.png') . '?' . time()); ?>"
                                                                width="200px" class="big-logo img_setting">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-5">
                                                        <label for="company_logo">
                                                            <div class=" bg-primary company_logo_update m-auto"> <i
                                                                    class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                            </div>
                                                            <input type="file" name="company_logo_dark" id="company_logo"
                                                                class="form-control file"
                                                                data-filename="company_logo_update"
                                                                onchange="document.getElementById('blah').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                    <?php $__errorArgs = ['company_logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="row">
                                                            <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                            </span>
                                                        </div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card shadow-none border rounded-0">
                                        <div class="card-header">
                                            <h5><?php echo e(__('Logo Light')); ?></h5>
                                        </div>
                                        <div class="card-body ">
                                            <div class=" setting-card">
                                                <div
                                                    class="d-flex flex-column justify-content-between align-items-center h-100">
                                                    <div class="logo-content mt-4">
                                                        <a href="<?php echo e($logo . '/' . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png')); ?>"
                                                            target="_blank">
                                                            <img id="blah1" alt="your image"
                                                                src="<?php echo e($logo . '/' . (isset($logo_light) && !empty($logo_light) ? $logo_light : 'logo-light.png') . '?' . time()); ?>"
                                                                width="200px" class="big-logo img_setting">
                                                        </a>
                                                    </div>
                                                    <div class="choose-files mt-5">
                                                        <label for="company_logo_light">
                                                            <div class=" bg-primary dark_logo_update m-auto"> <i
                                                                    class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                            </div>
                                                            <input type="file" name="company_logo_light"
                                                                id="company_logo_light" class="form-control file"
                                                                data-filename="dark_logo_update"
                                                                onchange="document.getElementById('blah1').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                    <?php $__errorArgs = ['company_logo_light'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="row">
                                                            <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                            </span>
                                                        </div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-sm-6 col-md-6 dashboard-card">
                                    <div class="card shadow-none border rounded-0">
                                        <div class="card-header">
                                            <h5><?php echo e(__('Favicon')); ?></h5>
                                        </div>
                                        <div class="card-body pt-0">
                                            <div class=" setting-card">
                                                <div
                                                    class="d-flex flex-column justify-content-between align-items-center h-100">
                                                    <div class="logo-content mt-4">
                                                        <a href="<?php echo e($logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png')); ?>"
                                                            target="_blank">
                                                            <img id="blah2" alt="your image"
                                                                src="<?php echo e($logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?' . time()); ?>"
                                                                width="80px" class="big-logo img_setting">
                                                        </a>

                                                    </div>
                                                    <div class="choose-files mt-4">
                                                        <label for="company_favicon">
                                                            <div class="bg-primary company_favicon_update m-auto"> <i
                                                                    class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                            </div>
                                                            <input type="file" name="company_favicon"
                                                                id="company_favicon" class="form-control file"
                                                                data-filename="company_favicon_update"
                                                                onchange="document.getElementById('blah2').src = window.URL.createObjectURL(this.files[0])">
                                                        </label>
                                                    </div>
                                                    <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                        <div class="row">
                                                            <span class="invalid-logo" role="alert">
                                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                                            </span>
                                                        </div>
                                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?php echo e(Form::label('title_text', __('Title Text'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::text('title_text', Utility::getValByName('title_text'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Title Text'),
                                            ])); ?>

                                            <?php $__errorArgs = ['title_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-title_text" role="alert">
                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <?php echo e(Form::label('footer_text', __('Footer Text'), ['class' => 'form-label'])); ?>

                                            <?php echo e(Form::text('footer_text', Utility::getValByName('footer_text'), [
                                                'class' => 'form-control',
                                                'placeholder' => __('Enter Footer Text'),
                                            ])); ?>

                                            <?php $__errorArgs = ['footer_text'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-footer_text" role="alert">
                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group">
                                            <?php echo e(Form::label('default_language', __('Default Language'), ['class' => 'form-label'])); ?>

                                            <div class="changeLanguage">

                                                <select name="default_language" id="default_language"
                                                    class="form-control select">
                                                    <?php $__currentLoopData = \App\Models\Utility::languages(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $code => $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <option <?php if($lang == $code): ?> selected <?php endif; ?>
                                                            value="<?php echo e($code); ?>">
                                                            <?php echo e(ucFirst($language)); ?></option>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </select>
                                            </div>
                                            <?php $__errorArgs = ['default_language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <span class="invalid-default_language" role="alert">
                                                    <strong class="text-danger"><?php echo e($message); ?></strong>
                                                </span>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    <div class="col">
                                        <div class="row">
                                            <div class="col-3 my-auto">
                                                <div class="form-group">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="SITE_RTL"><?php echo e(__('Enable RTL')); ?></label>
                                                    <div class="">
                                                        <input type="checkbox" name="SITE_RTL" id="SITE_RTL"
                                                            data-toggle="switchbutton"
                                                            <?php echo e($settings['SITE_RTL'] == 'on' ? 'checked="checked"' : ''); ?>

                                                            data-onstyle="primary">
                                                        <label class="form-check-labe" for="SITE_RTL"></label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3">
                                                <div class="col switch-width">
                                                    <div class="form-group ml-2 mr-3">
                                                        <?php echo e(Form::label('signup_button', __('Enable Sign-Up Page'), ['class' => 'col-form-label'])); ?>

                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" data-toggle="switchbutton"
                                                                data-onstyle="primary" class=""
                                                                name="signup_button" id="signup_button"
                                                                <?php echo e($settings['signup_button'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                            <label class="custom-control-label mb-1"
                                                                for="signup_button"></label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-3  ">
                                                <div class="form-group">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="email_verification"><?php echo e(__('Email Verification')); ?></label>
                                                    <div class="">
                                                        <input type="checkbox" name="email_verification"
                                                            id="email_verification" data-toggle="switchbutton"
                                                            <?php echo e($settings['email_verification'] == 'on' ? 'checked="checked"' : ''); ?>

                                                            data-onstyle="primary">
                                                        <label class="form-check-label" for="email_verification"></label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-3">
                                                <div class="form-group ">
                                                    <label class="text-dark mb-1 mt-3"
                                                        for="display_landing_page"><?php echo e(__('Enable Landing Page')); ?></label>
                                                    <div class="">
                                                        <input type="checkbox" name="display_landing_page"
                                                            class="form-check-input" id="display_landing_page"
                                                            data-toggle="switchbutton"
                                                            <?php echo e($settings['display_landing_page'] == 'on' ? 'checked="checked"' : ''); ?>

                                                            data-onstyle="primary">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h4 class="small-title"><?php echo e(__('Theme Customizer')); ?></h4>
                                <div class="setting-card setting-logo-box p-3">
                                    <div class="row">
                                        <div class="col-lg-4 col-xl-4 col-md-4">
                                            <h6 class="mt-2">
                                                <i data-feather="credit-card" class="me-2"></i><?php echo e(__('Primary color settings')); ?>

                                            </h6>

                                            <hr class="my-2" />
                                            <div class="color-wrp">
                                                <div class="theme-color themes-color">
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-1' ? 'active_color' : ''); ?>" data-value="theme-1"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-1"<?php echo e($color == 'theme-1' ? 'checked' : ''); ?>>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-2' ? 'active_color' : ''); ?>" data-value="theme-2"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-2"<?php echo e($color == 'theme-2' ? 'checked' : ''); ?>>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-3' ? 'active_color' : ''); ?>" data-value="theme-3"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-3"<?php echo e($color == 'theme-3' ? 'checked' : ''); ?>>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-4' ? 'active_color' : ''); ?>" data-value="theme-4"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-4"<?php echo e($color == 'theme-4' ? 'checked' : ''); ?>>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-5' ? 'active_color' : ''); ?>" data-value="theme-5"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-5"<?php echo e($color == 'theme-5' ? 'checked' : ''); ?>>
                                                    <br>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-6' ? 'active_color' : ''); ?>" data-value="theme-6"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-6"<?php echo e($color == 'theme-6' ? 'checked' : ''); ?>>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-7' ? 'active_color' : ''); ?>" data-value="theme-7"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-7"<?php echo e($color == 'theme-7' ? 'checked' : ''); ?>>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-8' ? 'active_color' : ''); ?>" data-value="theme-8"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-8"<?php echo e($color == 'theme-8' ? 'checked' : ''); ?>>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-9' ? 'active_color' : ''); ?>" data-value="theme-9"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-9"<?php echo e($color == 'theme-9' ? 'checked' : ''); ?>>
                                                    <a href="#!" class="themes-color-change <?php echo e($color == 'theme-10' ? 'active_color' : ''); ?>" data-value="theme-10"></a>
                                                    <input type="radio" class="theme_color d-none" name="color" value="theme-10"<?php echo e($color == 'theme-10' ? 'checked' : ''); ?>>
                                                </div>
                                                <div class="color-picker-wrp ">
                                                        <input type="color" value="<?php echo e($color ? $color : ''); ?>" class="colorPicker <?php echo e(isset($flag) && $flag == 'true' ? 'active_color' : ''); ?>" name="custom_color" id="color-picker">
                                                        <input type='hidden' name="color_flag" value = <?php echo e(isset($flag) && $flag == 'true' ? 'true' : 'false'); ?>>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xl-4 col-md-4">
                                            <h6 class="mt-2">
                                                <i data-feather="layout" class="me-2"></i><?php echo e(__('Sidebar settings')); ?>

                                            </h6>
                                            <hr class="my-2" />
                                            <div class="form-check form-switch">
                                                <input type="checkbox" class="form-check-input" id="cust-theme-bg" name="cust_theme_bg" <?php echo e(!empty($settings['cust_theme_bg']) && $settings['cust_theme_bg'] == 'on' ? 'checked' : ''); ?>/>
                                                <label class="form-check-label f-w-600 pl-1" for="cust-theme-bg"
                                                ><?php echo e(__('Transparent layout')); ?></label>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-xl-4 col-md-4">
                                            <h6 class="mt-2">
                                                <i data-feather="sun" class="me-2"></i><?php echo e(__('Layout settings')); ?>

                                            </h6>
                                            <hr class="my-2" />
                                            <div class="form-check form-switch mt-2">
                                                <input type="checkbox" class="form-check-input" id="cust-darklayout" name="cust_darklayout"<?php echo e(!empty($settings['cust_darklayout']) && $settings['cust_darklayout'] == 'on' ? 'checked' : ''); ?> />
                                                <label class="form-check-label f-w-600 pl-1" for="cust-darklayout"><?php echo e(__('Dark Layout')); ?></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-footer text-end pb-0 pe-0">
                                    <div class="form-group">
                                        <input class="btn btn-print-invoice btn-primary m-r-10" type="submit"
                                            value="<?php echo e(__('Save Changes')); ?>">
                                    </div>
                                </div>
                                <?php echo e(Form::close()); ?>

                            </div>
                        </div>
                    </div>

                    <!--Email Setting-->
                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-2">
                        <div class="card-header">
                            <h5><?php echo e(__('Email Settings')); ?></h5>
                        </div>
                        <?php echo e(Form::open(['route' => 'email.settings', 'method' => 'post'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mail_driver', __('Mail Driver'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('mail_driver', $settings['mail_driver'], ['class' => 'form-control', 'id' => 'mail_driver', 'placeholder' => __('Enter Mail Driver')])); ?>

                                        <?php $__errorArgs = ['mail_driver'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-mail_driver" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mail_host', __('Mail Host'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('mail_host', $settings['mail_host'], [
                                            'class' => 'form-control ',
                                            'id' => 'mail_host',
                                            'placeholder' => __('Enter Mail Host'),
                                        ])); ?>

                                        <?php $__errorArgs = ['mail_host'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-mail_driver" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mail_port', __('Mail Port'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('mail_port', $settings['mail_port'], [
                                            'class' => 'form-control',
                                            'id' => 'mail_port',
                                            'placeholder' => __('Enter Mail Port'),
                                        ])); ?>

                                        <?php $__errorArgs = ['mail_port'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-mail_port" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mail_username', __('Mail Username'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('mail_username', $settings['mail_username'], [
                                            'class' => 'form-control',
                                            'id' => 'mail_username',
                                            'placeholder' => __('Enter Mail Username'),
                                        ])); ?>

                                        <?php $__errorArgs = ['mail_username'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-mail_username" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mail_password', __('Mail Password'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('mail_password', $settings['mail_password'], [
                                            'class' => 'form-control',
                                            'id' => 'mail_password',
                                            'placeholder' => __('Enter Mail Password'),
                                        ])); ?>

                                        <?php $__errorArgs = ['mail_password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-mail_password" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mail_encryption', __('Mail Encryption'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('mail_encryption', $settings['mail_encryption'], [
                                            'class' => 'form-control',
                                            'id' => 'mail_encryption',
                                            'placeholder' => __('Enter Mail Encryption'),
                                        ])); ?>

                                        <?php $__errorArgs = ['mail_encryption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-mail_encryption" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mail_from_address', __('Mail From Address'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('mail_from_address', $settings['mail_from_address'], [
                                            'class' => 'form-control',
                                            'id' => 'mail_from_address',
                                            'placeholder' => __('Enter Mail From Address'),
                                        ])); ?>

                                        <?php $__errorArgs = ['mail_from_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-mail_from_address" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <?php echo e(Form::label('mail_from_name', __('Mail From Name'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('mail_from_name', $settings['mail_from_name'], [
                                            'class' => 'form-control',
                                            'id' => 'mail_from_name',
                                            'placeholder' => __('Enter Mail From Name'),
                                        ])); ?>

                                        <?php $__errorArgs = ['mail_from_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-mail_from_name" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer pb-0">
                            <div class="row">
                                <div class="form-group col-md-6 col-6">
                                    <a href="#" class="btn btn-primary  send_email"
                                        data-title="<?php echo e(__('Send Test Mail')); ?>" data-url="<?php echo e(route('test.mail')); ?>">
                                        <?php echo e(__('Send Test Mail')); ?>

                                    </a>
                                </div>
                                <div class="form-group col-md-6 col-6 text-end">
                                    <input class="btn btn-primary" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                                </div>
                            </div>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>

                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-3">
                        <div class="card-header">
                            <h5><?php echo e(__('Payment Settings')); ?></h5>
                            <small
                                class="text-secondary font-weight-bold"><?php echo e(__('These details will be used to collect subscription plan payments.Each subscription plan will have a payment button based on the below configuration.')); ?></small>
                        </div>
                        <form id="setting-form" method="post" action="<?php echo e(route('admin.payment.settings')); ?>">
                            <?php echo csrf_field(); ?>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="row">
                                            <div class="col-md-6 form-group">
                                                <label class="col-form-label"><?php echo e(__('Currency')); ?> *</label>
                                                <input type="text" name="currency" class="form-control"
                                                    id="currency"
                                                    value="<?php echo e(!isset($payment['currency']) || is_null($payment['currency']) ? '' : $payment['currency']); ?>"
                                                    required>
                                                <small class="text-xs">
                                                    <?php echo e(__('Note: Add currency code as per three-letter ISO code')); ?>.
                                                    <a href="https://stripe.com/docs/currencies"
                                                        target="_blank"><?php echo e(__('You can find out how to do that here.')); ?></a>
                                                </small>
                                            </div>
                                            <div class="col-md-6 form-group">
                                                <label for="currency_symbol"
                                                    class="col-form-label"><?php echo e(__('Currency Symbol')); ?> *</label>
                                                <input type="text" name="currency_symbol" class="form-control"
                                                    id="currency_symbol"
                                                    value="<?php echo e(!isset($payment['currency_symbol']) || is_null($payment['currency_symbol']) ? '' : $payment['currency_symbol']); ?>"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="faq justify-content-center">
                                            <div class="row">
                                                <div class="accordion accordion-flush setting-accordion"
                                                    id="accordionExample">
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading-2-15">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse15"
                                                                aria-expanded="false" aria-controls="collapse15">
                                                                <span class="d-flex align-items-center">

                                                                    <?php echo e(__('Manually')); ?>

                                                                </span>

                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_manually_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_manually_enabled"
                                                                            id="is_manually_enabled"
                                                                            <?php echo e(isset($payment['is_manually_enabled']) && $payment['is_manually_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-1"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse15" class="accordion-collapse collapse"
                                                            aria-labelledby="heading-2-15"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div
                                                                        class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                                        <div class="row pt-2">
                                                                            <label class="pb-2"
                                                                                for="is_manually_enabled"><?php echo e(__('Requesting manual payment for the planned
                                                                                                                                                            amount for the subscriptions paln.')); ?></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item ">
                                                        <h2 class="accordion-header" id="heading-2-16">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse16"
                                                                aria-expanded="false" aria-controls="collapse16">
                                                                <span class="d-flex align-items-center">

                                                                    <?php echo e(__('Bank Transfer')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_bank_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_bank_enabled" id="is_bank_enabled"
                                                                            <?php echo e(isset($payment['is_bank_enabled']) && $payment['is_bank_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-1"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse16" class="accordion-collapse collapse"
                                                            aria-labelledby="heading-2-16"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row gy-4">
                                                                    <div class="col-md-6 mt-3">
                                                                        <div class="form-group">
                                                                            <?php echo Form::label('inputname', 'Bank Details', ['class' => 'col-form-label']); ?>


                                                                            <?php
                                                                                $bank_details = !empty($payment['bank_details']) ? $payment['bank_details'] : '';
                                                                            ?>
                                                                            <?php echo Form::textarea('bank_details', $bank_details, [
                                                                                'class' => 'form-control',
                                                                                'rows' => '6',
                                                                            ]); ?>

                                                                            <small class="text-xs">
                                                                                <?php echo e(__('Example : Bank : Bank Name <br> Account Number : 0000 0000 <br>')); ?>.
                                                                            </small>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingOne">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseOne"
                                                                aria-expanded="false" aria-controls="collapseOne">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Stripe')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_stripe_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_stripe_enabled"
                                                                            id="is_stripe_enabled"
                                                                            <?php echo e(isset($payment['is_stripe_enabled']) && $payment['is_stripe_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-1"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseOne" class="accordion-collapse collapse"
                                                            aria-labelledby="headingOne"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="stripe_key"
                                                                                class="col-form-label"><?php echo e(__('Stripe
                                                                                                                                                            Key')); ?></label>
                                                                            <input class="form-control"
                                                                                placeholder="<?php echo e(__('Stripe Key')); ?>"
                                                                                name="stripe_key" type="text"
                                                                                value="<?php echo e(!isset($payment['stripe_key']) || is_null($payment['stripe_key']) ? '' : $payment['stripe_key']); ?>"
                                                                                id="stripe_key">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="stripe_secret"
                                                                                class="col-form-label"><?php echo e(__('Stripe
                                                                                                                                                            Secret')); ?></label>
                                                                            <input class="form-control "
                                                                                placeholder="<?php echo e(__('Stripe Secret')); ?>"
                                                                                name="stripe_secret" type="text"
                                                                                value="<?php echo e(!isset($payment['stripe_secret']) || is_null($payment['stripe_secret']) ? '' : $payment['stripe_secret']); ?>"
                                                                                id="stripe_secret">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingTwo">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                                aria-expanded="false" aria-controls="collapseTwo">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Paypal')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_paypal_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            id="customswitchv1-2" name="is_paypal_enabled"
                                                                            id="is_paypal_enabled"
                                                                            <?php echo e(isset($payment['is_paypal_enabled']) && $payment['is_paypal_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>

                                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                                            aria-labelledby="headingTwo"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="paypal_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input"
                                                                                            <?php echo e(!isset($payment['paypal_mode']) || $payment['paypal_mode'] == '' || $payment['paypal_mode'] == 'sandbox'
                                                                                                ? 'checked="checked"'
                                                                                                : ''); ?>>
                                                                                        <?php echo e(__('Sandbox')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="paypal_mode"
                                                                                            value="live"
                                                                                            class="form-check-input"
                                                                                            <?php echo e(isset($payment['paypal_mode']) && $payment['paypal_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                        <?php echo e(__('Live')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>


                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="col-form-label"
                                                                                for="paypal_client_id"><?php echo e(__('Client ID')); ?></label>
                                                                            <input type="text" name="paypal_client_id"
                                                                                id="paypal_client_id" class="form-control"
                                                                                value="<?php echo e(!isset($payment['paypal_client_id']) || is_null($payment['paypal_client_id']) ? '' : $payment['paypal_client_id']); ?>"
                                                                                placeholder="<?php echo e(__('Client ID')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label class="col-form-label"
                                                                                for="paypal_secret_key"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text" name="paypal_secret_key"
                                                                                id="paypal_secret_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['paypal_secret_key']) || is_null($payment['paypal_secret_key']) ? '' : $payment['paypal_secret_key']); ?>"
                                                                                placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingThree">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseThree"
                                                                aria-expanded="false" aria-controls="collapseTwo">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i>
                                                                    <?php echo e(__('Paystack')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_paystack_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            id="customswitchv1-2"
                                                                            name="is_paystack_enabled"
                                                                            id="is_paystack_enabled"
                                                                            <?php echo e(isset($payment['is_paystack_enabled']) && $payment['is_paystack_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseThree" class="accordion-collapse collapse"
                                                            aria-labelledby="headingTwo"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paypal_client_id"
                                                                                class="col-form-label"><?php echo e(__('Public
                                                                                                                                                            Key')); ?></label>
                                                                            <input type="text"
                                                                                name="paystack_public_key"
                                                                                id="paystack_public_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['paystack_public_key']) ? $payment['paystack_public_key'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Public Key')); ?>" />
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paystack_secret_key"
                                                                                class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text"
                                                                                name="paystack_secret_key"
                                                                                id="paystack_secret_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['paystack_secret_key']) ? $payment['paystack_secret_key'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Secret Key')); ?>" />
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFour">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseFour"
                                                                aria-expanded="false" aria-controls="collapseTwo">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Flutterwave')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden"
                                                                            name="is_flutterwave_enabled" value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            id="customswitchv1-2"
                                                                            name="is_flutterwave_enabled"
                                                                            id="is_flutterwave_enabled"
                                                                            <?php echo e(isset($payment['is_flutterwave_enabled']) && $payment['is_flutterwave_enabled'] == 'on'
                                                                                ? 'checked="checked"'
                                                                                : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseFour" class="accordion-collapse collapse"
                                                            aria-labelledby="headingFour"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paypal_client_id"
                                                                                class="col-form-label"><?php echo e(__('Public
                                                                                                                                                            Key')); ?></label>
                                                                            <input type="text"
                                                                                name="flutterwave_public_key"
                                                                                id="flutterwave_public_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['flutterwave_public_key']) || is_null($payment['flutterwave_public_key']) ? '' : $payment['flutterwave_public_key']); ?>"
                                                                                placeholder="Public Key">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paystack_secret_key"
                                                                                class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text"
                                                                                name="flutterwave_secret_key"
                                                                                id="flutterwave_secret_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['flutterwave_secret_key']) || is_null($payment['flutterwave_secret_key']) ? '' : $payment['flutterwave_secret_key']); ?>"
                                                                                placeholder="Secret Key">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingFive">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseFive"
                                                                aria-expanded="false" aria-controls="collapseFive">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Razorpay')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"> <?php echo e(__('Enable')); ?> </span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_razorpay_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            id="customswitchv1-2"
                                                                            name="is_razorpay_enabled"
                                                                            id="is_razorpay_enabled"
                                                                            <?php echo e(isset($payment['is_razorpay_enabled']) && $payment['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseFive" class="accordion-collapse collapse"
                                                            aria-labelledby="headingFive"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paypal_client_id"
                                                                                class="col-form-label"><?php echo e(__('Public
                                                                                                                                                            Key')); ?></label>

                                                                            <input type="text"
                                                                                name="razorpay_public_key"
                                                                                id="razorpay_public_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['razorpay_public_key']) || is_null($payment['razorpay_public_key']) ? '' : $payment['razorpay_public_key']); ?>"
                                                                                placeholder="Public Key">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paystack_secret_key"
                                                                                class="col-form-label">
                                                                                <?php echo e(__('Secret
                                                                                                                                                            Key')); ?></label>
                                                                            <input type="text"
                                                                                name="razorpay_secret_key"
                                                                                id="razorpay_secret_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['razorpay_secret_key']) || is_null($payment['razorpay_secret_key']) ? '' : $payment['razorpay_secret_key']); ?>"
                                                                                placeholder="Secret Key">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingSix">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseSix"
                                                                aria-expanded="false" aria-controls="collapseSix">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Paytm')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_paytm_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_paytm_enabled" id="is_paytm_enabled"
                                                                            <?php echo e(isset($payment['is_paytm_enabled']) && $payment['is_paytm_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseSix" class="accordion-collapse collapse"
                                                            aria-labelledby="headingSix"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 pb-4">
                                                                        <label class="paypal-label col-form-label"
                                                                            for="paypal_mode"><?php echo e(__('Paytm
                                                                                                                                                    Environment')); ?></label>
                                                                        <br>
                                                                        <div class="d-flex">
                                                                            <div class="mr-2"
                                                                                style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="paytm_mode"
                                                                                                value="local"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(!isset($payment['paytm_mode']) || $payment['paytm_mode'] == '' || $payment['paytm_mode'] == 'local'
                                                                                                    ? 'checked="checked"'
                                                                                                    : ''); ?>>
                                                                                            <?php echo e(__('Local')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="paytm_mode"
                                                                                                value="production"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(isset($payment['paytm_mode']) && $payment['paytm_mode'] == 'production' ? 'checked="checked"' : ''); ?>>
                                                                                            <?php echo e(__('Production')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="paytm_public_key"
                                                                                class="col-form-label"><?php echo e(__('Merchant
                                                                                                                                                            ID')); ?></label>
                                                                            <input type="text" name="paytm_merchant_id"
                                                                                id="paytm_merchant_id"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['paytm_merchant_id']) ? $payment['paytm_merchant_id'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Merchant ID')); ?>" />
                                                                            <?php if($errors->has('paytm_merchant_id')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('paytm_merchant_id')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="paytm_secret_key"
                                                                                class="col-form-label"><?php echo e(__('Merchant Key')); ?></label>
                                                                            <input type="text"
                                                                                name="paytm_merchant_key"
                                                                                id="paytm_merchant_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['paytm_merchant_key']) ? $payment['paytm_merchant_key'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Merchant Key')); ?>" />
                                                                            <?php if($errors->has('paytm_merchant_key')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('paytm_merchant_key')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-4">
                                                                        <div class="form-group">
                                                                            <label for="paytm_industry_type"
                                                                                class="col-form-label"><?php echo e(__('Industry
                                                                                                                                                            Type')); ?></label>
                                                                            <input type="text"
                                                                                name="paytm_industry_type"
                                                                                id="paytm_industry_type"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['paytm_industry_type']) ? $payment['paytm_industry_type'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Industry Type')); ?>" />
                                                                            <?php if($errors->has('paytm_industry_type')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('paytm_industry_type')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingseven">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseseven"
                                                                aria-expanded="false" aria-controls="collapseseven">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Mercado Pago')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_mercado_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_mercado_enabled"
                                                                            id="is_mercado_enabled"
                                                                            <?php echo e(isset($payment['is_mercado_enabled']) && $payment['is_mercado_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseseven" class="accordion-collapse collapse"
                                                            aria-labelledby="headingseven"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">

                                                                    <div class="col-md-12 pb-4">
                                                                        <label class="coingate-label col-form-label"
                                                                            for="mercado_mode"><?php echo e(__('Mercado
                                                                                                                                                    Mode')); ?></label>
                                                                        <br>
                                                                        <div class="d-flex">
                                                                            <div class="mr-2"
                                                                                style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="mercado_mode"
                                                                                                value="sandbox"
                                                                                                class="form-check-input"
                                                                                                <?php echo e((isset($payment['mercado_mode']) && $payment['mercado_mode'] == '') ||
                                                                                                (isset($payment['mercado_mode']) && $payment['mercado_mode'] == 'sandbox')
                                                                                                    ? 'checked="checked"'
                                                                                                    : ''); ?>>
                                                                                            <?php echo e(__('Sandbox')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="mercado_mode"
                                                                                                value="live"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(isset($payment['mercado_mode']) && $payment['mercado_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                            <?php echo e(__('Live')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="mercado_access_token"
                                                                                class="col-form-label"><?php echo e(__('Access Token')); ?></label>
                                                                            <input type="text"
                                                                                name="mercado_access_token"
                                                                                id="mercado_access_token"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['mercado_access_token']) ? $payment['mercado_access_token'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Access Token')); ?>" />
                                                                            <?php if($errors->has('mercado_secret_key')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('mercado_access_token')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingeight">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseeight"
                                                                aria-expanded="false" aria-controls="collapseeight">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Mollie')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_mollie_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_mollie_enabled"
                                                                            id="is_mollie_enabled"
                                                                            <?php echo e(isset($payment['is_mollie_enabled']) && $payment['is_mollie_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseeight" class="accordion-collapse collapse"
                                                            aria-labelledby="headingeight"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="mollie_api_key"
                                                                                class="col-form-label"><?php echo e(__('Mollie Api
                                                                                                                                                            Key')); ?></label>
                                                                            <input type="text" name="mollie_api_key"
                                                                                id="mollie_api_key" class="form-control"
                                                                                value="<?php echo e(!isset($payment['mollie_api_key']) || is_null($payment['mollie_api_key']) ? '' : $payment['mollie_api_key']); ?>"
                                                                                placeholder="Mollie Api Key">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="mollie_profile_id"
                                                                                class="col-form-label"><?php echo e(__('Mollie Profile
                                                                                                                                                            Id')); ?></label>
                                                                            <input type="text" name="mollie_profile_id"
                                                                                id="mollie_profile_id"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['mollie_profile_id']) || is_null($payment['mollie_profile_id']) ? '' : $payment['mollie_profile_id']); ?>"
                                                                                placeholder="Mollie Profile Id">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="mollie_partner_id"
                                                                                class="col-form-label"><?php echo e(__('Mollie Partner
                                                                                                                                                            Id')); ?></label>
                                                                            <input type="text" name="mollie_partner_id"
                                                                                id="mollie_partner_id"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['mollie_partner_id']) || is_null($payment['mollie_partner_id']) ? '' : $payment['mollie_partner_id']); ?>"
                                                                                placeholder="Mollie Partner Id">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingnine">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapsenine"
                                                                aria-expanded="false" aria-controls="collapsenine">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Skrill')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_skrill_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_skrill_enabled"
                                                                            id="is_skrill_enabled"
                                                                            <?php echo e(isset($payment['is_skrill_enabled']) && $payment['is_skrill_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapsenine" class="accordion-collapse collapse"
                                                            aria-labelledby="headingnine"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="mollie_api_key"
                                                                                class="col-form-label"><?php echo e(__('Skrill
                                                                                                                                                            Email')); ?></label>
                                                                            <input type="email" name="skrill_email"
                                                                                id="skrill_email" class="form-control"
                                                                                value="<?php echo e(isset($payment['skrill_email']) ? $payment['skrill_email'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Mollie Api Key')); ?>" />
                                                                            <?php if($errors->has('skrill_email')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('skrill_email')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="headingten">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapseten"
                                                                aria-expanded="false" aria-controls="collapseten">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('CoinGate')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_coingate_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_coingate_enabled"
                                                                            id="is_coingate_enabled"
                                                                            <?php echo e(isset($payment['is_coingate_enabled']) && $payment['is_coingate_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapseten" class="accordion-collapse collapse"
                                                            aria-labelledby="headingten"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 pb-4">
                                                                        <label class="col-form-label"
                                                                            for="coingate_mode"><?php echo e(__('CoinGate Mode')); ?></label>
                                                                        <br>
                                                                        <div class="d-flex">
                                                                            <div class="mr-2"
                                                                                style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="coingate_mode"
                                                                                                value="sandbox"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(!isset($payment['coingate_mode']) ||
                                                                                                $payment['coingate_mode'] == '' ||
                                                                                                $payment['coingate_mode'] == 'sandbox'
                                                                                                    ? 'checked="checked"'
                                                                                                    : ''); ?>>
                                                                                            <?php echo e(__('Sandbox')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="coingate_mode"
                                                                                                value="live"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(isset($payment['coingate_mode']) && $payment['coingate_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                            <?php echo e(__('Live')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="coingate_auth_token"
                                                                                class="col-form-label"><?php echo e(__('CoinGate Auth
                                                                                                                                                            Token')); ?></label>
                                                                            <input type="text"
                                                                                name="coingate_auth_token"
                                                                                id="coingate_auth_token"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['coingate_auth_token']) || is_null($payment['coingate_auth_token']) ? '' : $payment['coingate_auth_token']); ?>"
                                                                                placeholder="CoinGate Auth Token">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item ">
                                                        <h2 class="accordion-header" id="heading11">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse11"
                                                                aria-expanded="false" aria-controls="collapse11">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('PaymentWall')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden"
                                                                            name="is_paymentwall_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_paymentwall_enabled"
                                                                            id="is_paymentwall_enabled"
                                                                            <?php echo e(isset($payment['is_paymentwall_enabled']) && $payment['is_paymentwall_enabled'] == 'on'
                                                                                ? 'checked="checked"'
                                                                                : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse11" class="accordion-collapse collapse"
                                                            aria-labelledby="heading11"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paymentwall_public_key"
                                                                                class="col-form-label"><?php echo e(__('Public
                                                                                                                                                            Key')); ?></label>
                                                                            <input type="text"
                                                                                name="paymentwall_public_key"
                                                                                id="paymentwall_public_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['paymentwall_public_key']) || is_null($payment['paymentwall_public_key']) ? '' : $payment['paymentwall_public_key']); ?>"
                                                                                placeholder="<?php echo e(__('Public Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paymentwall_private_key"
                                                                                class="col-form-label"><?php echo e(__('Private Key')); ?></label>
                                                                            <input type="text"
                                                                                name="paymentwall_private_key"
                                                                                id="paymentwall_private_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['paymentwall_private_key']) || is_null($payment['paymentwall_private_key']) ? '' : $payment['paymentwall_private_key']); ?>"
                                                                                placeholder="<?php echo e(__('Private Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item ">
                                                        <h2 class="accordion-header" id="heading-2-13">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse12"
                                                                aria-expanded="false" aria-controls="collapse12">
                                                                <span class="d-flex align-items-center">

                                                                    <?php echo e(__('Toyyibpay')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden"
                                                                            name="is_toyyibpay_enabled" value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_toyyibpay_enabled"
                                                                            id="is_toyyibpay_enabled"
                                                                            <?php echo e(isset($payment['is_toyyibpay_enabled']) && $payment['is_toyyibpay_enabled'] == 'on'
                                                                                ? 'checked="checked"'
                                                                                : ''); ?>>
                                                                        <label for="customswitch1-2"
                                                                            class="form-check-label"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse12" class="accordion-collapse collapse"
                                                            aria-labelledby="heading-2-13"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="toyyibpay_secret_key"
                                                                                class="form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text"
                                                                                name="toyyibpay_secret_key"
                                                                                id="toyyibpay_secret_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['toyyibpay_secret_key']) || is_null($payment['toyyibpay_secret_key']) ? '' : $payment['toyyibpay_secret_key']); ?>"
                                                                                placeholder="<?php echo e(__('Secret Key')); ?>">

                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="category_code"
                                                                                class="form-label"><?php echo e(__('Category Code')); ?></label>
                                                                            <input type="text" name="category_code"
                                                                                id="category_code" class="form-control"
                                                                                value="<?php echo e(!isset($payment['category_code']) || is_null($payment['category_code']) ? '' : $payment['category_code']); ?>"
                                                                                placeholder="<?php echo e(__('Category Code')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item ">
                                                        <h2 class="accordion-header" id="heading-2-14">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse14"
                                                                aria-expanded="true" aria-controls="collapse14">
                                                                <span class="d-flex align-items-center">

                                                                    <?php echo e(__('Payfast')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_payfast_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_payfast_enabled"
                                                                            id="is_payfast_enabled"
                                                                            <?php echo e(isset($payment['is_payfast_enabled']) && $payment['is_payfast_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>

                                                        <div id="collapse14" class="accordion-collapse collapse"
                                                            aria-labelledby="heading-2-14"
                                                            data-bs-parent="#accordionExample">

                                                            <div class="accordion-body">
                                                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 pb-4">
                                                                    <label class="col-form-label"
                                                                        for="payfast_mode"><?php echo e(__('Payfast Mode')); ?></label>
                                                                    <br>
                                                                    <div class="d-flex">
                                                                        <div class="mr-2" style="margin-right: 15px;">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="payfast_mode"
                                                                                            value="sandbox"
                                                                                            class="form-check-input"
                                                                                            <?php echo e(!isset($payment['payfast_mode']) || $payment['payfast_mode'] == '' || $payment['payfast_mode'] == 'sandbox'
                                                                                                ? 'checked="checked"'
                                                                                                : ''); ?>>
                                                                                        <?php echo e(__('Sandbox')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="mr-2">
                                                                            <div class="border card p-3">
                                                                                <div class="form-check">
                                                                                    <label
                                                                                        class="form-check-labe text-dark">
                                                                                        <input type="radio"
                                                                                            name="payfast_mode"
                                                                                            value="live"
                                                                                            class="form-check-input"
                                                                                            <?php echo e(isset($payment['payfast_mode']) && $payment['payfast_mode'] == 'live' ? 'checked="checked"' : ''); ?>>
                                                                                        <?php echo e(__('Live')); ?>

                                                                                    </label>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="payfast_merchant_id"
                                                                                class="form-label"><?php echo e(__('Merchant Id')); ?></label>
                                                                            <input type="text"
                                                                                name="payfast_merchant_id"
                                                                                id="payfast_merchant_id"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['payfast_merchant_id']) || is_null($payment['payfast_merchant_id']) ? '' : $payment['payfast_merchant_id']); ?>"
                                                                                placeholder="<?php echo e(__('Merchant Id')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="payfast_merchant_key"
                                                                                class="form-label"><?php echo e(__('Merchant Key')); ?></label>
                                                                            <input type="text"
                                                                                name="payfast_merchant_key"
                                                                                id="payfast_merchant_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['payfast_merchant_key']) || is_null($payment['payfast_merchant_key']) ? '' : $payment['payfast_merchant_key']); ?>"
                                                                                placeholder="<?php echo e(__('Merchant Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="payfast_signature"
                                                                                class="form-label"><?php echo e(__('Salt Passphrase')); ?></label>
                                                                            <input type="text"
                                                                                name="payfast_signature"
                                                                                id="payfast_signature"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['payfast_signature']) || is_null($payment['payfast_signature']) ? '' : $payment['payfast_signature']); ?>"
                                                                                placeholder="<?php echo e(__('Salt Passphrase')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading-2-15">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#headingiyzi"
                                                                aria-expanded="false" aria-controls="headingiyzi">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('Iyzipay')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_iyzipay_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_iyzipay_enabled"
                                                                            id="is_iyzipay_enabled"
                                                                            <?php echo e(isset($payment['is_iyzipay_enabled']) && $payment['is_iyzipay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="headingiyzi" class="accordion-collapse collapse"
                                                            aria-labelledby="heading-2-15"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 pb-4">
                                                                        <label class="paypal-label col-form-label"
                                                                            for="paypal_mode"><?php echo e(__('IyziPay Mode')); ?></label>
                                                                        <br>
                                                                        <div class="d-flex">
                                                                            <div class="mr-2"
                                                                                style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="iyzipay_mode"
                                                                                                value="local"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(!isset($payment['iyzipay_mode']) || $payment['iyzipay_mode'] == '' || $payment['iyzipay_mode'] == 'local'
                                                                                                    ? 'checked="checked"'
                                                                                                    : ''); ?>>
                                                                                            <?php echo e(__('Local')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="iyzipay_mode"
                                                                                                value="production"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(isset($payment['iyzipay_mode']) && $payment['iyzipay_mode'] == 'production' ? 'checked="checked"' : ''); ?>>
                                                                                            <?php echo e(__('Production')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="iyzipay_key"
                                                                                class="col-form-label"><?php echo e(__('IyziPay Key')); ?></label>
                                                                            <input type="text" name="iyzipay_key"
                                                                                id="iyzipay_key" class="form-control"
                                                                                value="<?php echo e(isset($payment['iyzipay_key']) ? $payment['iyzipay_key'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('IyziPay Key')); ?>" />
                                                                            <?php if($errors->has('iyzipay_key')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('iyzipay_key')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="iyzipay_secret"
                                                                                class="col-form-label"><?php echo e(__('IyziPay Secret')); ?></label>
                                                                            <input type="text" name="iyzipay_secret"
                                                                                id="iyzipay_secret" class="form-control"
                                                                                value="<?php echo e(isset($payment['iyzipay_secret']) ? $payment['iyzipay_secret'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('IyziPay Secret')); ?>" />
                                                                            <?php if($errors->has('iyzipay_secret')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('iyzipay_secret')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading-2-16">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#headingssp"
                                                                aria-expanded="false" aria-controls="headingssp">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('SSPay')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_sspay_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_sspay_enabled"
                                                                            id="is_sspay_enabled"
                                                                            <?php echo e(isset($payment['is_sspay_enabled']) && $payment['is_sspay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="headingssp" class="accordion-collapse collapse"
                                                            aria-labelledby="heading-2-16"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="sspay_secret_key"
                                                                                class="col-form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text"
                                                                                name="sspay_secret_key"
                                                                                id="sspay_secret_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['sspay_secret_key']) ? $payment['sspay_secret_key'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Secret Key')); ?>" />
                                                                            <?php if($errors->has('sspay_secret_key')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('sspay_secret_key')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="sspay_category_code"
                                                                                class="col-form-label"><?php echo e(__('Category Code')); ?></label>
                                                                            <input type="text"
                                                                                name="sspay_category_code"
                                                                                id="sspay_category_code"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['sspay_category_code']) ? $payment['sspay_category_code'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Category Code')); ?>" />
                                                                            <?php if($errors->has('sspay_category_code')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('sspay_category_code')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="accordion-item card shadow-none ">
                                                        <h2 class="accordion-header" id="heading-2-17">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse17"
                                                                aria-expanded="true" aria-controls="collapse17">

                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('PayTab')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_paytab_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_paytab_enabled"
                                                                            id="is_paytab_enabled"
                                                                            <?php echo e(isset($payment['is_paytab_enabled']) && $payment['is_paytab_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label for="customswitch1-2"
                                                                            class="form-check-label"></label>
                                                                    </div>
                                                                </div>

                                                            </button>
                                                        </h2>
                                                        <div id="collapse17"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-17"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytab_profile_id"
                                                                                class="form-label"><?php echo e(__('Profile Id')); ?></label>
                                                                            <input type="text"
                                                                                name="paytab_profile_id"
                                                                                id="paytab_profile_id"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['paytab_profile_id']) || is_null($payment['paytab_profile_id']) ? '' : $payment['paytab_profile_id']); ?>"
                                                                                placeholder="<?php echo e(__('Profile Id')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytab_server_key"
                                                                                class="form-label"><?php echo e(__('Server Key')); ?></label>
                                                                            <input type="text"
                                                                                name="paytab_server_key"
                                                                                id="paytab_server_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['paytab_server_key']) || is_null($payment['paytab_server_key']) ? '' : $payment['paytab_server_key']); ?>"
                                                                                placeholder="<?php echo e(__('Server Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytab_region"
                                                                                class="form-label"><?php echo e(__('Paytab Region')); ?></label>
                                                                            <input type="text" name="paytab_region"
                                                                                id="paytab_region" class="form-control"
                                                                                value="<?php echo e(!isset($payment['paytab_region']) || is_null($payment['paytab_region']) ? '' : $payment['paytab_region']); ?>"
                                                                                placeholder="<?php echo e(__('Paytab Region')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="accordion-item card shadow-none">
                                                        <h2 class="accordion-header" id="heading-2-18">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse18"
                                                                aria-expanded="true" aria-controls="collapse18">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Benefit')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_benefit_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_benefit_enabled"
                                                                            id="is_benefit_enabled"
                                                                            <?php echo e(isset($payment['is_benefit_enabled']) && $payment['is_benefit_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label for="customswitch1-2"
                                                                            class="form-check-label"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse18"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-18"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="benefit_api_key"
                                                                                class="form-label"><?php echo e(__('Benefit Key')); ?></label>
                                                                            <input type="text" name="benefit_api_key"
                                                                                id="benefit_api_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['benefit_api_key']) || is_null($payment['benefit_api_key']) ? '' : $payment['benefit_api_key']); ?>"
                                                                                placeholder="<?php echo e(__('Enter Benefit Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="benefit_secret_key"
                                                                                class="form-label"><?php echo e(__('Benefit Secret Key')); ?></label>
                                                                            <input type="text"
                                                                                name="benefit_secret_key"
                                                                                id="benefit_secret_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['benefit_secret_key']) || is_null($payment['benefit_secret_key']) ? '' : $payment['benefit_secret_key']); ?>"
                                                                                placeholder="<?php echo e(__('Enter Benefit Secret key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="accordion-item card shadow-none">
                                                        <h2 class="accordion-header" id="heading-2-19">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse19"
                                                                aria-expanded="true" aria-controls="collapse19">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Cashfree')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_cashfree_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_cashfree_enabled"
                                                                            id="is_cashfree_enabled"
                                                                            <?php echo e(isset($payment['is_cashfree_enabled']) && $payment['is_cashfree_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label for="customswitch1-2"
                                                                            class="form-check-label"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse19"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-19"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="cashfree_api_key"
                                                                                class="form-label"><?php echo e(__(' Cashfree Key')); ?></label>
                                                                            <input type="text"
                                                                                name="cashfree_api_key"
                                                                                id="cashfree_api_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['cashfree_api_key']) || is_null($payment['cashfree_api_key']) ? '' : $payment['cashfree_api_key']); ?>"
                                                                                placeholder="<?php echo e(__('Enter Cashfree Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="cashfree_secret_key"
                                                                                class="form-label"><?php echo e(__('Cashfree Secret Key')); ?></label>
                                                                            <input type="text"
                                                                                name="cashfree_secret_key"
                                                                                id="cashfree_secret_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['cashfree_secret_key']) || is_null($payment['cashfree_secret_key']) ? '' : $payment['cashfree_secret_key']); ?>"
                                                                                placeholder="<?php echo e(__('Enter Cashfree Secret Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="accordion-item card shadow-none">
                                                        <h2 class="accordion-header" id="heading-2-20">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse20"
                                                                aria-expanded="true" aria-controls="collapse20">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Aamarpay')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_aamarpay_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_aamarpay_enabled"
                                                                            id="is_aamarpay_enabled"
                                                                            <?php echo e(isset($payment['is_aamarpay_enabled']) && $payment['is_aamarpay_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label for="customswitch1-2"
                                                                            class="form-check-label"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="collapse20"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-20"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="aamarpay_store_id"
                                                                                class="form-label"><?php echo e(__(' Store Id')); ?></label>
                                                                            <input type="text"
                                                                                name="aamarpay_store_id"
                                                                                id="aamarpay_store_id"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['aamarpay_store_id']) || is_null($payment['aamarpay_store_id']) ? '' : $payment['aamarpay_store_id']); ?>"
                                                                                placeholder="<?php echo e(__('Enter Store Id')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="aamarpay_signature_key"
                                                                                class="form-label"><?php echo e(__('Signature Key')); ?></label>
                                                                            <input type="text"
                                                                                name="aamarpay_signature_key"
                                                                                id="aamarpay_signature_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['aamarpay_signature_key']) || is_null($payment['aamarpay_signature_key']) ? '' : $payment['aamarpay_signature_key']); ?>"
                                                                                placeholder="<?php echo e(__('Enter Signature Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="aamarpay_description"
                                                                                class="form-label"><?php echo e(__('Description')); ?></label>
                                                                            <input type="text"
                                                                                name="aamarpay_description"
                                                                                id="aamarpay_description"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['aamarpay_description']) || is_null($payment['aamarpay_description']) ? '' : $payment['aamarpay_description']); ?>"
                                                                                placeholder="<?php echo e(__('Enter Signature Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="accordion-item card shadow-none">
                                                        <h2 class="accordion-header" id="heading-2-21">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse21"
                                                                aria-expanded="true" aria-controls="collapse21">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Pay TR')); ?>

                                                                </span>


                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_paytr_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_paytr_enabled"
                                                                            id="is_paytr_enabled"
                                                                            <?php echo e(isset($payment['is_paytr_enabled']) && $payment['is_paytr_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>

                                                            </button>
                                                        </h2>

                                                        <div id="collapse21"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-21"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytr_merchant_id"
                                                                                class="form-label"><?php echo e(__('Merchant Id')); ?></label>
                                                                            <input type="text"
                                                                                name="paytr_merchant_id"
                                                                                id="paytr_merchant_id"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['paytr_merchant_id']) || is_null($payment['paytr_merchant_id']) ? '' : $payment['paytr_merchant_id']); ?>"
                                                                                placeholder="<?php echo e(__('Merchant Id')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytr_merchant_key"
                                                                                class="form-label"><?php echo e(__('Merchant Key')); ?></label>
                                                                            <input type="text"
                                                                                name="paytr_merchant_key"
                                                                                id="paytr_merchant_key"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['paytr_merchant_key']) || is_null($payment['paytr_merchant_key']) ? '' : $payment['paytr_merchant_key']); ?>"
                                                                                placeholder="<?php echo e(__('Merchant Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="paytr_merchant_salt"
                                                                                class="form-label"><?php echo e(__('Salt Passphrase')); ?></label>
                                                                            <input type="text"
                                                                                name="paytr_merchant_salt"
                                                                                id="paytr_merchant_salt"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['paytr_merchant_salt']) || is_null($payment['paytr_merchant_salt']) ? '' : $payment['paytr_merchant_salt']); ?>"
                                                                                placeholder="<?php echo e(__('Salt Passphrase')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item card shadow-none">
                                                        <h2 class="accordion-header" id="heading-2-22">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse22"
                                                                aria-expanded="true" aria-controls="collapse22">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Yookassa')); ?>

                                                                </span>

                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_yookassa_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_yookassa_enabled"
                                                                            id="is_yookassa_enabled"
                                                                            <?php echo e(isset($payment['is_yookassa_enabled']) && $payment['is_yookassa_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>

                                                            </button>
                                                        </h2>

                                                        <div id="collapse22"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-22"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="yookassa_shop_id"
                                                                                class="form-label"><?php echo e(__('Shop ID Key')); ?></label>
                                                                            <input type="text"
                                                                                name="yookassa_shop_id"
                                                                                id="yookassa_shop_id"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['yookassa_shop_id']) || is_null($payment['yookassa_shop_id']) ? '' : $payment['yookassa_shop_id']); ?>"
                                                                                placeholder="<?php echo e(__('Shop ID Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="yookassa_secret"
                                                                                class="form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text" name="yookassa_secret"
                                                                                id="yookassa_secret"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['yookassa_secret']) || is_null($payment['yookassa_secret']) ? '' : $payment['yookassa_secret']); ?>"
                                                                                placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    
                                                    <div class="accordion-item card shadow-none">
                                                        <h2 class="accordion-header" id="heading-2-23">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse23"
                                                                aria-expanded="true" aria-controls="collapse23">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Midtrans')); ?>

                                                                </span>

                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_midtrans_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_midtrans_enabled"
                                                                            id="is_midtrans_enabled"
                                                                            <?php echo e(isset($payment['is_midtrans_enabled']) && $payment['is_midtrans_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>

                                                            </button>
                                                        </h2>

                                                        <div id="collapse23"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-23"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">

                                                                    <div class="col-md-12 pb-4">
                                                                        <label class="paypal-label col-form-label"
                                                                            for="paypal_mode"><?php echo e(__('Midtrans Mode')); ?></label>
                                                                        <br>
                                                                        <div class="d-flex">
                                                                            <div class="mr-2"
                                                                                style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="midtrans_mode"
                                                                                                value="local"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(!isset($payment['midtrans_mode']) || $payment['midtrans_mode'] == '' || $payment['midtrans_mode'] == 'local'
                                                                                                    ? 'checked="checked"'
                                                                                                    : ''); ?>>
                                                                                            <?php echo e(__('Local')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="midtrans_mode"
                                                                                                value="production"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(isset($payment['midtrans_mode']) && $payment['midtrans_mode'] == 'production' ? 'checked="checked"' : ''); ?>>
                                                                                            <?php echo e(__('Production')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="midtrans_secret"
                                                                                class="form-label"><?php echo e(__('Secret Key')); ?></label>
                                                                            <input type="text" name="midtrans_secret"
                                                                                id="midtrans_secret"
                                                                                class="form-control"
                                                                                value="<?php echo e(!isset($payment['midtrans_secret']) || is_null($payment['midtrans_secret']) ? '' : $payment['midtrans_secret']); ?>"
                                                                                placeholder="<?php echo e(__('Secret Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="accordion-item card shadow-none">
                                                        <h2 class="accordion-header" id="heading-2-24">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse" data-bs-target="#collapse24"
                                                                aria-expanded="true" aria-controls="collapse24">
                                                                <span class="d-flex align-items-center">
                                                                    <?php echo e(__('Xendit')); ?>

                                                                </span>

                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_xendit_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_xendit_enabled"
                                                                            id="is_xendit_enabled"
                                                                            <?php echo e(isset($payment['is_xendit_enabled']) && $payment['is_xendit_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>

                                                            </button>
                                                        </h2>

                                                        <div id="collapse24"
                                                            class="accordion-collapse collapse"aria-labelledby="heading-2-24"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">

                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="xendit_api"
                                                                                class="form-label"><?php echo e(__('API Key')); ?></label>
                                                                            <input type="text" name="xendit_api"
                                                                                id="xendit_api" class="form-control"
                                                                                value="<?php echo e(!isset($payment['xendit_api']) || is_null($payment['xendit_api']) ? '' : $payment['xendit_api']); ?>"
                                                                                placeholder="<?php echo e(__('API Key')); ?>">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="xendit_token"
                                                                                class="form-label"><?php echo e(__('Token')); ?></label>
                                                                            <input type="text" name="xendit_token"
                                                                                id="xendit_token" class="form-control"
                                                                                value="<?php echo e(!isset($payment['xendit_token']) || is_null($payment['xendit_token']) ? '' : $payment['xendit_token']); ?>"
                                                                                placeholder="<?php echo e(__('Token')); ?>">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    
                                                    <div class="accordion-item">
                                                        <h2 class="accordion-header" id="heading-2-15">
                                                            <button class="accordion-button collapsed" type="button"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#headingPayhere" aria-expanded="false"
                                                                aria-controls="headingPayhere">
                                                                <span class="d-flex align-items-center">
                                                                    <i class=""></i> <?php echo e(__('PayHere')); ?>

                                                                </span>
                                                                <div class="d-flex align-items-center">
                                                                    <span class="me-2"><?php echo e(__('Enable')); ?></span>
                                                                    <div class="form-check form-switch custom-switch-v1">
                                                                        <input type="hidden" name="is_payhere_enabled"
                                                                            value="off">
                                                                        <input type="checkbox"
                                                                            class="form-check-input input-primary"
                                                                            name="is_payhere_enabled"
                                                                            id="is_payhere_enabled"
                                                                            <?php echo e(isset($payment['is_payhere_enabled']) && $payment['is_payhere_enabled'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                                        <label class="form-check-label"
                                                                            for="customswitchv1-2"></label>
                                                                    </div>
                                                                </div>
                                                            </button>
                                                        </h2>
                                                        <div id="headingPayhere" class="accordion-collapse collapse"
                                                            aria-labelledby="heading-2-15"
                                                            data-bs-parent="#accordionExample">
                                                            <div class="accordion-body">
                                                                <div class="row">
                                                                    <div class="col-md-12 pb-4">
                                                                        <label class="paypal-label col-form-label"
                                                                            for="paypal_mode"><?php echo e(__('PayHere Mode')); ?></label>
                                                                        <br>
                                                                        <div class="d-flex">
                                                                            <div class="mr-2"
                                                                                style="margin-right: 15px;">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="payhere_mode"
                                                                                                value="local"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(!isset($payment['payhere_mode']) || $payment['payhere_mode'] == '' || $payment['payhere_mode'] == 'local'
                                                                                                    ? 'checked="checked"'
                                                                                                    : ''); ?>>
                                                                                            <?php echo e(__('Local')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="mr-2">
                                                                                <div class="border card p-3">
                                                                                    <div class="form-check">
                                                                                        <label
                                                                                            class="form-check-labe text-dark">
                                                                                            <input type="radio"
                                                                                                name="payhere_mode"
                                                                                                value="production"
                                                                                                class="form-check-input"
                                                                                                <?php echo e(isset($payment['payhere_mode']) && $payment['payhere_mode'] == 'production' ? 'checked="checked"' : ''); ?>>
                                                                                            <?php echo e(__('Production')); ?>

                                                                                        </label>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="merchant_id"
                                                                                class="col-form-label"><?php echo e(__('Merchant ID')); ?></label>
                                                                            <input type="text" name="merchant_id"
                                                                                id="merchant_id" class="form-control"
                                                                                value="<?php echo e(isset($payment['merchant_id']) ? $payment['merchant_id'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Merchant ID')); ?>" />
                                                                            <?php if($errors->has('merchant_id')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('merchant_id')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="merchant_secret"
                                                                                class="col-form-label"><?php echo e(__('Merchant Secret')); ?></label>
                                                                            <input type="text" name="merchant_secret"
                                                                                id="merchant_secret"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['merchant_secret']) ? $payment['merchant_secret'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('Merchant Secret')); ?>" />
                                                                            <?php if($errors->has('merchant_secret')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('merchant_secret')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="payhere_app_id"
                                                                                class="col-form-label"><?php echo e(__('App ID')); ?></label>
                                                                            <input type="text" name="payhere_app_id"
                                                                                id="payhere_app_id" class="form-control"
                                                                                value="<?php echo e(isset($payment['payhere_app_id']) ? $payment['payhere_app_id'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('App ID')); ?>" />
                                                                            <?php if($errors->has('payhere_app_id')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('payhere_app_id')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="form-group">
                                                                            <label for="payhere_app_secret"
                                                                                class="col-form-label"><?php echo e(__('App Secret')); ?></label>
                                                                            <input type="text"
                                                                                name="payhere_app_secret"
                                                                                id="payhere_app_secret"
                                                                                class="form-control"
                                                                                value="<?php echo e(isset($payment['payhere_app_secret']) ? $payment['payhere_app_secret'] : ''); ?>"
                                                                                placeholder="<?php echo e(__('App Secret')); ?>" />
                                                                            <?php if($errors->has('payhere_app_secret')): ?>
                                                                                <span class="invalid-feedback d-block">
                                                                                    <?php echo e($errors->first('payhere_app_secret')); ?>

                                                                                </span>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end">
                                <button class="btn-submit btn btn-primary" type="submit">
                                    <?php echo e(__('Save Changes')); ?>

                                </button>
                            </div>
                        </form>
                    </div>

                    
                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-4">
                        <?php echo e(Form::open(['url' => route('seo.settings'), 'enctype' => 'multipart/form-data'])); ?>

                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10 col-6">
                                    <h5><?php echo e(__('SEO Settings')); ?></h5>
                                </div>
                                <?php if($chatgpt_enable): ?>
                                    <div class="col-md-2 col-6">
                                        <a href="#" class="btn btn-sm btn-primary" data-size="medium"
                                            data-ajax-popup-over="true" data-url="<?php echo e(route('generate', ['seo'])); ?>"
                                            data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="<?php echo e(__('Generate')); ?>"
                                            data-title="<?php echo e(__('Generate Content With AI')); ?>">
                                            <i class="fas fa-robot"></i><?php echo e(__(' Generate With AI')); ?>

                                        </a>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('meta_keywords', __('Meta Keywords'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('meta_keywords', !empty($settings['meta_keywords']) ? $settings['meta_keywords'] : '', [
                                            'class' => 'form-control ',
                                            'placeholder' => __('Meta Keywords'),
                                        ])); ?>

                                    </div>

                                    <div class="form-group">
                                        <?php echo e(Form::label('meta_description', __('Meta Description'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::textarea(
                                            'meta_description',
                                            !empty($settings['meta_description']) ? $settings['meta_description'] : '',
                                            ['class' => 'form-control ', 'row' => 2, 'placeholder' => __('Enter Meta Description')],
                                        )); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('Meta Image', __('Meta Image'), ['class' => 'col-form-label ms-4'])); ?>

                                        <div class="card-body pt-0">
                                            <div class="setting-card">
                                                <div class="logo-content ">
                                                    <a href="<?php echo e($meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : '/meta_image.png')); ?>"
                                                        target="_blank">
                                                        <img id="meta"
                                                            src="<?php echo e($meta_image . '/' . (isset($settings['meta_image']) && !empty($settings['meta_image']) ? $settings['meta_image'] : '/meta_image.png')); ?>"
                                                            width="250px" class="img_setting seo_image">
                                                    </a>
                                                </div>
                                                <div class="choose-files mt-4">
                                                    <label for="meta_image">
                                                        <div class=" bg-primary logo"> <i
                                                                class="ti ti-upload px-1"></i><?php echo e(__('Choose file here')); ?>

                                                        </div>
                                                        <input style="margin-top: -40px;" type="file"
                                                            class="form-control file" name="meta_image"
                                                            id="meta_image" data-filename="meta_image"
                                                            onchange="document.getElementById('meta').src = window.URL.createObjectURL(this.files[0])">
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card-footer text-end">
                            <button class="btn-submit btn btn-primary" type="submit">
                                <?php echo e(__('Save Changes')); ?>

                            </button>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>

                    
                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-5">
                        <div class="col-md-12">
                            <form method="POST" action="<?php echo e(route('recaptcha.settings.store')); ?>"
                                accept-charset="UTF-8">
                                <div class="card-header">
                                    <div class="row">
                                        <div class="col-lg-8 col-md-8 col-sm-8 col-6">
                                            <h5 class=""><?php echo e(__('ReCaptcha Settings')); ?></h5><small
                                                class="text-secondary font-weight-bold">(<?php echo e(__('How to Get Google reCaptcha Site and Secret key')); ?>)</small>
                                        </div>

                                        <div class="col-lg-4 col-md-4 col-sm-4 text-end col-6">
                                            <div class="col switch-width">
                                                <div class="custom-control custom-switch">
                                                    <input type="checkbox" data-toggle="switchbutton"
                                                        data-onstyle="primary" class="" name="recaptcha_module"
                                                        id="recaptcha_module"
                                                        <?php echo e(!empty($settings['recaptcha_module']) && $settings['recaptcha_module'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                    <label class="custom-control-label form-control-label px-2"
                                                        for="recaptcha_module "></label><br>
                                                    <a href="https://phppot.com/php/how-to-get-google-recaptcha-site-and-secret-key/"
                                                        target="_blank" class="text-blue">

                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <?php echo csrf_field(); ?>
                                    <div class="row">
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            <label for="google_recaptcha_key"
                                                class="form-label"><?php echo e(__('Google Recaptcha
                                                                                            Key')); ?></label>
                                            <input class="form-control"
                                                placeholder="<?php echo e(__('Enter Google Recaptcha Key')); ?>"
                                                name="google_recaptcha_key" type="text"
                                                value="<?php echo e($settings['google_recaptcha_key']); ?>"
                                                id="google_recaptcha_key">
                                        </div>
                                        <div class="col-lg-6 col-md-6 col-sm-6 form-group">
                                            <label for="google_recaptcha_secret"
                                                class="form-label"><?php echo e(__('Google Recaptcha Secret')); ?></label>
                                            <input class="form-control "
                                                placeholder="<?php echo e(__('Enter Google Recaptcha Secret')); ?>"
                                                name="google_recaptcha_secret" type="text"
                                                value="<?php echo e($settings['google_recaptcha_secret']); ?>"
                                                id="google_recaptcha_secret">
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer text-end">

                                    <?php echo e(Form::submit(__('Save Changes'), ['class' => 'btn btn-xs btn-primary'])); ?>


                                </div>
                            </form>
                        </div>
                    </div>

                    
                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-6">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-12">
                                    <h5 class="h6 md-0"><?php echo e(__('Cache Settings')); ?></h5>
                                    <small>
                                        <?php echo e(__('This is a page meant for more advanced users, simply ignore it if you don\'t understand what cache is.')); ?>

                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for=""> <?php echo e(__('Current cache size')); ?> </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="input-group search-form">
                                    <input type="text" value="<?php echo e(Utility::GetCacheSize()); ?>" class="form-control"
                                        readonly>
                                    <span class="input-group-text bg-transparent"> <?php echo e(__('MB')); ?> </span>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <a href="<?php echo e(url('clear-cache')); ?>"
                                class="btn btn-print-invoice btn-primary m-r-10"><?php echo e(__('Clear Cache')); ?></a>
                        </div>
                    </div>

                    
                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-7">
                        <?php echo e(Form::open(['route' => 'storage.setting.store', 'enctype' => 'multipart/form-data'])); ?>

                        <div class="card-header">
                            <div class="row">
                                <div class="col-lg-10 col-md-10 col-sm-10">
                                    <h5 class=""><?php echo e(__('Storage Settings')); ?></h5>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="d-flex">
                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting"
                                        id="local-outlined" autocomplete="off"
                                        <?php echo e($setting['storage_setting'] == 'local' ? 'checked' : ''); ?> value="local"
                                        checked>
                                    <label class="btn btn-outline-primary"
                                        for="local-outlined"><?php echo e(__('Local')); ?></label>
                                </div>
                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting" id="s3-outlined"
                                        autocomplete="off" <?php echo e($setting['storage_setting'] == 's3' ? 'checked' : ''); ?>

                                        value="s3">
                                    <label class="btn btn-outline-primary" for="s3-outlined">
                                        <?php echo e(__('AWS S3')); ?></label>
                                </div>

                                <div class="pe-2">
                                    <input type="radio" class="btn-check" name="storage_setting"
                                        id="wasabi-outlined" autocomplete="off"
                                        <?php echo e($setting['storage_setting'] == 'wasabi' ? 'checked' : ''); ?> value="wasabi">
                                    <label class="btn btn-outline-primary"
                                        for="wasabi-outlined"><?php echo e(__('Wasabi')); ?></label>
                                </div>
                            </div>
                            <div class="mt-2">
                                <div
                                    class="local-setting row <?php echo e($setting['storage_setting'] == 'local' ? ' ' : 'd-none'); ?>">

                                    <div class="form-group col-8 switch-width">
                                        <?php echo e(Form::label('local_storage_validation', __('Only Upload Files'), [
                                            'class' => '
                                                                            form-label',
                                        ])); ?>

                                        <select name="local_storage_validation[]" class="multi-select "
                                            id="choices-multiple" id="local_storage_validation" multiple>
                                            <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option <?php if(in_array($f, $local_storage_validations)): ?> selected <?php endif; ?>>
                                                    <?php echo e($f); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                    <div class="col-lg-4">
                                        <div class="form-group">
                                            <label class="form-label"
                                                for="local_storage_max_upload_size"><?php echo e(__('Max upload size ( In KB)')); ?></label>
                                            <input type="number" name="local_storage_max_upload_size"
                                                class="form-control"
                                                value="<?php echo e(!isset($setting['local_storage_max_upload_size']) || is_null($setting['local_storage_max_upload_size']) ? '' : $setting['local_storage_max_upload_size']); ?>"
                                                placeholder="<?php echo e(__('Max upload size')); ?>">
                                        </div>
                                    </div>
                                </div>

                                <div class="s3-setting row <?php echo e($setting['storage_setting'] == 's3' ? ' ' : 'd-none'); ?>">

                                    <div class=" row ">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_key"><?php echo e(__('S3 Key')); ?></label>
                                                <input type="text" name="s3_key" class="form-control"
                                                    value="<?php echo e(!isset($setting['s3_key']) || is_null($setting['s3_key']) ? '' : $setting['s3_key']); ?>"
                                                    placeholder="<?php echo e(__('S3 Key')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_secret"><?php echo e(__('S3 Secret')); ?></label>
                                                <input type="text" name="s3_secret" class="form-control"
                                                    value="<?php echo e(!isset($setting['s3_secret']) || is_null($setting['s3_secret']) ? '' : $setting['s3_secret']); ?>"
                                                    placeholder="<?php echo e(__('S3 Secret')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_region"><?php echo e(__('S3 Region')); ?></label>
                                                <input type="text" name="s3_region" class="form-control"
                                                    value="<?php echo e(!isset($setting['s3_region']) || is_null($setting['s3_region']) ? '' : $setting['s3_region']); ?>"
                                                    placeholder="<?php echo e(__('S3 Region')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_bucket"><?php echo e(__('S3 Bucket')); ?></label>
                                                <input type="text" name="s3_bucket" class="form-control"
                                                    value="<?php echo e(!isset($setting['s3_bucket']) || is_null($setting['s3_bucket']) ? '' : $setting['s3_bucket']); ?>"
                                                    placeholder="<?php echo e(__('S3 Bucket')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label" for="s3_url"><?php echo e(__('S3 URL')); ?></label>
                                                <input type="text" name="s3_url" class="form-control"
                                                    value="<?php echo e(!isset($setting['s3_url']) || is_null($setting['s3_url']) ? '' : $setting['s3_url']); ?>"
                                                    placeholder="<?php echo e(__('S3 URL')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_endpoint"><?php echo e(__('S3 Endpoint')); ?></label>
                                                <input type="text" name="s3_endpoint" class="form-control"
                                                    value="<?php echo e(!isset($setting['s3_endpoint']) || is_null($setting['s3_endpoint']) ? '' : $setting['s3_endpoint']); ?>"
                                                    placeholder="<?php echo e(__('S3 Bucket')); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-8 switch-width">
                                            <?php echo e(Form::label('s3_storage_validation', __('Only Upload Files'), [
                                                'class' => '
                                                                                    form-label',
                                            ])); ?>

                                            <select name="s3_storage_validation[]" class=" multi-select"
                                                id="choises-multiple1" id="s3_storage_validation" multiple>
                                                <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php if(in_array($f, $s3_storage_validations)): ?> selected <?php endif; ?>>
                                                        <?php echo e($f); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_max_upload_size"><?php echo e(__('Max upload size (
                                                                                                    In KB)')); ?></label>
                                                <input type="number" name="s3_max_upload_size" class="form-control"
                                                    value="<?php echo e(!isset($setting['s3_max_upload_size']) || is_null($setting['s3_max_upload_size']) ? '' : $setting['s3_max_upload_size']); ?>"
                                                    placeholder="<?php echo e(__('Max upload size')); ?>">
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div
                                    class="wasabi-setting row <?php echo e($setting['storage_setting'] == 'wasabi' ? ' ' : 'd-none'); ?>">
                                    <div class=" row ">
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_key"><?php echo e(__('Wasabi Key')); ?></label>
                                                <input type="text" name="wasabi_key" class="form-control"
                                                    value="<?php echo e(!isset($setting['wasabi_key']) || is_null($setting['wasabi_key']) ? '' : $setting['wasabi_key']); ?>"
                                                    placeholder="<?php echo e(__('Wasabi Key')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_secret"><?php echo e(__('Wasabi Secret')); ?></label>
                                                <input type="text" name="wasabi_secret" class="form-control"
                                                    value="<?php echo e(!isset($setting['wasabi_secret']) || is_null($setting['wasabi_secret']) ? '' : $setting['wasabi_secret']); ?>"
                                                    placeholder="<?php echo e(__('Wasabi Secret')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="s3_region"><?php echo e(__('Wasabi Region')); ?></label>
                                                <input type="text" name="wasabi_region" class="form-control"
                                                    value="<?php echo e(!isset($setting['wasabi_region']) || is_null($setting['wasabi_region']) ? '' : $setting['wasabi_region']); ?>"
                                                    placeholder="<?php echo e(__('Wasabi Region')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="wasabi_bucket"><?php echo e(__('Wasabi Bucket')); ?></label>
                                                <input type="text" name="wasabi_bucket" class="form-control"
                                                    value="<?php echo e(!isset($setting['wasabi_bucket']) || is_null($setting['wasabi_bucket']) ? '' : $setting['wasabi_bucket']); ?>"
                                                    placeholder="<?php echo e(__('Wasabi Bucket')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="wasabi_url"><?php echo e(__('Wasabi URL')); ?></label>
                                                <input type="text" name="wasabi_url" class="form-control"
                                                    value="<?php echo e(!isset($setting['wasabi_url']) || is_null($setting['wasabi_url']) ? '' : $setting['wasabi_url']); ?>"
                                                    placeholder="<?php echo e(__('Wasabi URL')); ?>">
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="wasabi_root"><?php echo e(__('Wasabi Root')); ?></label>
                                                <input type="text" name="wasabi_root" class="form-control"
                                                    value="<?php echo e(!isset($setting['wasabi_root']) || is_null($setting['wasabi_root']) ? '' : $setting['wasabi_root']); ?>"
                                                    placeholder="<?php echo e(__('Wasabi Bucket')); ?>">
                                            </div>
                                        </div>
                                        <div class="form-group col-8 switch-width">
                                            <?php echo e(Form::label('wasabi_storage_validation', __('Only Upload Files'), ['class' => 'form-label'])); ?>


                                            <select name="wasabi_storage_validation[]" class=" multi-select"
                                                id="choises-multiple2" id="wasabi_storage_validation" multiple>
                                                <?php $__currentLoopData = $file_type; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $f): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option <?php if(in_array($f, $wasabi_storage_validations)): ?> selected <?php endif; ?>>
                                                        <?php echo e($f); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>
                                        <div class="col-lg-4">
                                            <div class="form-group">
                                                <label class="form-label"
                                                    for="wasabi_root"><?php echo e(__('Max upload size ( In
                                                                                                    KB)')); ?></label>
                                                <input type="number" name="wasabi_max_upload_size"
                                                    class="form-control"
                                                    value="<?php echo e(!isset($setting['wasabi_max_upload_size']) || is_null($setting['wasabi_max_upload_size']) ? '' : $setting['wasabi_max_upload_size']); ?>"
                                                    placeholder="<?php echo e(__('Max upload size')); ?>">
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end pb-0 pe-0">
                                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                    value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                            <?php echo e(Form::close()); ?>

                        </div>
                    </div>

                    
                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-8">
                        <?php echo e(Form::model($settings, ['route' => 'cookie.setting', 'method' => 'post'])); ?>

                        <div
                            class="card-header flex-column flex-lg-row  d-flex align-items-lg-center gap-2 justify-content-between align-items-center flex--column flex-sm-row">

                            <h5><?php echo e(__('Cookie Settings')); ?></h5>
                            <div class="d-flex align-items-center">
                                <?php echo e(Form::label('enable_cookie', __('Enable cookie'), [
                                    'class' => 'col-form-label p-0 fw-bold
                                                            me-3',
                                ])); ?>

                                <div class="custom-control custom-switch" onclick="enablecookie()">
                                    <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary"
                                        name="enable_cookie" class="form-check-input input-primary "
                                        id="enable_cookie" <?php echo e($settings['enable_cookie'] == 'on' ? ' checked ' : ''); ?>>
                                    <label class="custom-control-label mb-1" for="enable_cookie"></label>
                                </div>
                            </div>
                        </div>
                        <div
                            class="card-body cookieDiv <?php echo e($settings['enable_cookie'] == 'off' ? 'disabledCookie ' : ''); ?>">
                            <?php if($chatgpt_enable): ?>
                                    <div class="text-end">
                                        <div class="mt-0">
                                            <a data-size="md" class="btn btn-primary text-white btn-sm"
                                                data-ajax-popup-over="true"
                                                data-url="<?php echo e(route('generate', ['cookie'])); ?>" data-bs-placement="top"
                                                data-title="<?php echo e(__('Generate content with AI')); ?>">
                                                <i class="fas fa-robot"></i> <span><?php echo e(__('Generate with AI')); ?></span>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <div class="row ">
                                <div class="col-md-6">
                                    <div class="form-check form-switch custom-switch-v1" id="cookie_log">
                                        <input type="checkbox" name="cookie_logging"
                                            class="form-check-input input-primary cookie_setting" id="cookie_logging"
                                            onclick="enableButton()"
                                            <?php echo e($settings['cookie_logging'] == 'on' ? ' checked ' : ''); ?>>
                                        <label class="form-check-label"
                                            for="cookie_logging"><?php echo e(__('Enable logging')); ?></label>
                                    </div>
                                    <div class="form-group">
                                        <?php echo e(Form::label('cookie_title', __('Cookie Title'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('cookie_title', null, ['class' => 'form-control cookie_setting'])); ?>

                                    </div>
                                    <div class="form-group ">
                                        <?php echo e(Form::label('cookie_description', __('Cookie Description'), [
                                            'class' => '
                                                                            form-label',
                                        ])); ?>

                                        <?php echo Form::textarea('cookie_description', null, [
                                            'class' => 'form-control
                                                                            cookie_setting',
                                            'rows' => '3',
                                        ]); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-switch custom-switch-v1 ">
                                        <input type="checkbox" name="necessary_cookies"
                                            class="form-check-input input-primary" id="necessary_cookies" checked
                                            onclick="return false">
                                        <label class="form-check-label"
                                            for="necessary_cookies"><?php echo e(__('Strictly necessary cookies')); ?></label>
                                    </div>
                                    <div class="form-group ">
                                        <?php echo e(Form::label('strictly_cookie_title', __(' Strictly Cookie Title'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('strictly_cookie_title', null, [
                                            'class' => 'form-control
                                                                            cookie_setting',
                                        ])); ?>

                                    </div>
                                    <div class="form-group ">
                                        <?php echo e(Form::label('strictly_cookie_description', __('Strictly Cookie Description'), ['class' => ' form-label'])); ?>

                                        <?php echo Form::textarea('strictly_cookie_description', null, [
                                            'class' => 'form-control
                                                                            cookie_setting ',
                                            'rows' => '3',
                                        ]); ?>

                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <h5><?php echo e(__('More Information')); ?></h5>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <?php echo e(Form::label('more_information_description', __('Contact Us Description'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('more_information_description', null, [
                                            'class' => 'form-control
                                                                            cookie_setting',
                                        ])); ?>

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group ">
                                        <?php echo e(Form::label('contactus_url', __('Contact Us URL'), ['class' => 'col-form-label'])); ?>

                                        <?php echo e(Form::text('contactus_url', null, ['class' => 'form-control cookie_setting'])); ?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div
                            class="card-footer d-flex align-items-center gap-2 flex--column flex-sm-row justify-content-between">
                            <div>
                                <?php if(isset($settings['cookie_logging']) && $settings['cookie_logging'] == 'on'): ?>
                                    <label for="file"
                                        class="form-label"><?php echo e(__('Download cookie accepted data')); ?></label>
                                    <a href="<?php echo e(asset('storage/uploads/sample') . '/data.csv'); ?>"
                                        class="btn btn-primary mr-2 ">
                                        <i class="ti ti-download"></i>
                                    </a>
                                <?php endif; ?>
                            </div>
                            <input type="submit" value="<?php echo e(__('Save Changes')); ?>" class="btn btn-primary">
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>

                    <!--Pusher Settings-->
                    <div class="card shadow-none rounded-0 border-bottom" id="useradd-9">
                        <div class="card-header">
                            <h5><?php echo e(__('Pusher Settings')); ?></h5>
                        </div>
                        <?php echo e(Form::model($settings, ['route' => 'pusher.setting', 'method' => 'post'])); ?>

                        <?php echo csrf_field(); ?>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('pusher_app_id', __('Pusher App Id'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('pusher_app_id', $settings['pusher_app_id'], ['class' => 'form-control font-style'])); ?>

                                        <?php $__errorArgs = ['pusher_app_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-pusher_app_id" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('pusher_app_key', __('Pusher App Key'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('pusher_app_key', $settings['pusher_app_key'], ['class' => 'form-control font-style'])); ?>

                                        <?php $__errorArgs = ['pusher_app_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-pusher_app_key" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('pusher_app_secret', __('Pusher App Secret'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('pusher_app_secret', $settings['pusher_app_secret'], [
                                            'class' => 'form-control
                                                                            font-style',
                                        ])); ?>

                                        <?php $__errorArgs = ['pusher_app_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-pusher_app_secret" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <?php echo e(Form::label('pusher_app_cluster', __('Pusher App Cluster'), ['class' => 'form-label'])); ?>

                                        <?php echo e(Form::text('pusher_app_cluster', $settings['pusher_app_cluster'], [
                                            'class' => 'form-control
                                                                            font-style',
                                        ])); ?>

                                        <?php $__errorArgs = ['pusher_app_cluster'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                            <span class="invalid-pusher_app_cluster" role="alert">
                                                <strong class="text-danger"><?php echo e($message); ?></strong>
                                            </span>
                                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end pb-0">
                            <div class="form-group">
                                <input class="btn btn-print-invoice  btn-primary m-r-10" type="submit"
                                    value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>

                    <div id="location-list" class="card shadow-none rounded-0 border-bottom">
                        <div class="col-md-12 border-bottom">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="mb-2"><?php echo e(__('Country Settings')); ?></h5>
                                    </div>

                                    <div class="col-6 text-end">


                                        <a href="#location-list" class="btn btn-sm btn-primary" data-ajax-popup="true"
                                            data-size="md" data-title="<?php echo e(__('Add Country')); ?>"
                                            data-url="<?php echo e(route('country.create')); ?>" data-toggle="tooltip"
                                            title="<?php echo e(__('Create')); ?>"
                                            data-bs-original-title="<?php echo e(__('Create New Counrty')); ?>"
                                            data-bs-placement="top" data-bs-toggle="tooltip">
                                            <i class="ti ti-plus"></i>
                                        </a>

                                    </div>

                                </div>
                            </div>
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table dataTable-5 data-table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('Name')); ?></th>


                                                <th class="text-center"><?php echo e(__('Action')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="font-style">
                                            <?php $__empty_1 = true; $__currentLoopData = $countries; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $country): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e(ucwords($country->country)); ?></td>

                                                    <td class="Action text-center">
                                                        <span>
                                                            <?php if(Auth::user()->type == 'super admin'): ?>
                                                                <div class="action-btn bg-light-secondary ms-2">
                                                                    <a href="#location-list"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                                        data-url="<?php echo e(route('country.edit', $country->id)); ?>"
                                                                        data-size="md" data-ajax-popup="true"
                                                                        data-title="<?php echo e(__('Edit Country')); ?>"
                                                                        title="<?php echo e(__('Edit Country')); ?>"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top"><i
                                                                            class="ti ti-edit "></i>
                                                                    </a>
                                                                </div>

                                                                <div class="action-btn bg-light-secondary ms-2">
                                                                    <a href="#location-list"
                                                                        class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                        data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                        data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                        data-confirm-yes="delete-form-<?php echo e($country->id); ?>"
                                                                        title="<?php echo e(__('Delete')); ?>"
                                                                        data-bs-toggle="tooltip"
                                                                        data-bs-placement="top">
                                                                        <i class="ti ti-trash"></i>
                                                                    </a>
                                                                </div>

                                                                <?php echo Form::open([
                                                                    'method' => 'DELETE',
                                                                    'route' => ['country.destroy', $country->id],
                                                                    'id' => 'delete-form-' . $country->id,
                                                                ]); ?>

                                                                <?php echo Form::close(); ?>

                                                            <?php endif; ?>

                                                        </span>
                                                    </td>
                                                </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr class="text-center">
                                                    <td colspan="4"><?php echo e(__('No Data Found.!')); ?></td>
                                                </tr>
                                            <?php endif; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 border-bottom">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="mt-2"><?php echo e(__('State Settings')); ?></h5>
                                    </div>

                                    <div class="col-6 text-end row">

                                        <form method="GET" action="<?php echo e(route('admin.settings')); ?>"
                                            accept-charset="UTF-8" id="customer_submit">
                                            <?php echo csrf_field(); ?>
                                            <div class=" d-flex align-items-center justify-content-end">

                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                                    <div class="btn-box">

                                                        <?php echo e(Form::label('country', __('Country: '), ['class' => 'col-form-label mr-2'])); ?>

                                                    </div>
                                                </div>
                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                                    <div class="btn-box">
                                                        <select class="form-control" id="country" name="country">
                                                            <option value="" disabled selected>
                                                                <?php echo e(__('Select Country')); ?></option>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">

                                                    <a href="#location-list" class="btn btn-sm btn-primary"
                                                        data-ajax-popup="true" data-size="md"
                                                        data-title="<?php echo e(__('Add State')); ?>"
                                                        data-url="<?php echo e(route('state.create')); ?>" data-toggle="tooltip"
                                                        title="<?php echo e(__('Create')); ?>"
                                                        data-bs-original-title="<?php echo e(__('Create New Counrty')); ?>"
                                                        data-bs-placement="top" data-bs-toggle="tooltip">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </form>


                                    </div>

                                </div>
                            </div>
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table dataTable-5 data-table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('Name')); ?></th>


                                                <th class="text-center"><?php echo e(__('Action')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="font-style">
                                            <?php $__empty_1 = true; $__currentLoopData = array_chunk($states, 50); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $state): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php $__currentLoopData = $state; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e(ucwords($stat['region'])); ?></td>

                                                        <td class="Action text-center">
                                                            <span>
                                                                <?php if(Auth::user()->type == 'super admin'): ?>
                                                                    <div class="action-btn bg-light-secondary ms-2">
                                                                        <a href="#"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                                            data-url="<?php echo e(route('state.edit', $stat['id'])); ?>"
                                                                            data-size="md" data-ajax-popup="true"
                                                                            data-title="<?php echo e(__('Edit State')); ?>"
                                                                            title="<?php echo e(__('Edit State')); ?>"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"><i
                                                                                class="ti ti-edit "></i>
                                                                        </a>
                                                                    </div>

                                                                    <div class="action-btn bg-light-secondary ms-2">
                                                                        <a href="#"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                            data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                            data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                            data-confirm-yes="delete-form-<?php echo e($stat['id']); ?>"
                                                                            title="<?php echo e(__('Delete')); ?>"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top">
                                                                            <i class="ti ti-trash"></i>
                                                                        </a>
                                                                    </div>

                                                                    <?php echo Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['state.destroy', $stat['id']],
                                                                        'id' => 'delete-form-' . $stat['id'],
                                                                    ]); ?>

                                                                    <?php echo Form::close(); ?>

                                                                <?php endif; ?>

                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr class="text-center">
                                                    <td colspan="4"><?php echo e(__('No Data Found.!')); ?></td>
                                                </tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 border-bottom">
                            <div class="card-header">
                                <div class="row">
                                    <div class="col-6">
                                        <h5 class="mt-2"><?php echo e(__('City Settings')); ?></h5>
                                    </div>

                                    <div class="col-6 text-end row">

                                        <form method="GET" action="<?php echo e(route('admin.settings')); ?>"
                                            accept-charset="UTF-8" id="state_filter_submit"> <?php echo csrf_field(); ?>
                                            <div class=" d-flex align-items-center justify-content-end">

                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 me-2">
                                                    <div class="btn-box">

                                                        <?php echo e(Form::label('city', __('State: '), ['class' => 'col-form-label mr-2'])); ?>

                                                    </div>
                                                </div>

                                                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">
                                                    <div class="btn-box">
                                                        <select class="form-control" id="state_filter"
                                                            name="state_id">
                                                            <option value="" disabled selected>
                                                                <?php echo e(__('Select State')); ?></option>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="col-xl-2 col-lg-3 col-md-6 col-sm-12 col-12 mr-2">

                                                    <a href="#location-list" class="btn btn-sm btn-primary"
                                                        data-ajax-popup="true" data-size="md"
                                                        data-title="<?php echo e(__('Add City')); ?>"
                                                        data-url="<?php echo e(route('city.create')); ?>" data-toggle="tooltip"
                                                        title="<?php echo e(__('Create')); ?>"
                                                        data-bs-original-title="<?php echo e(__('Create New City')); ?>"
                                                        data-bs-placement="top" data-bs-toggle="tooltip">
                                                        <i class="ti ti-plus"></i>
                                                    </a>
                                                </div>

                                            </div>
                                        </form>


                                    </div>

                                </div>
                            </div>
                            <div class="card-body table-border-style">
                                <div class="table-responsive">
                                    <table class="table dataTable-5 data-table">
                                        <thead>
                                            <tr>
                                                <th><?php echo e(__('Name')); ?></th>


                                                <th class="text-center"><?php echo e(__('Action')); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody class="font-style">
                                            <?php $__empty_1 = true; $__currentLoopData = array_chunk($cities, 50); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $city): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <?php $__currentLoopData = $city; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <tr>
                                                        <td><?php echo e(ucwords($cit['city'])); ?></td>

                                                        <td class="Action text-center">
                                                            <span>
                                                                <?php if(Auth::user()->type == 'super admin'): ?>
                                                                    <div class="action-btn bg-light-secondary ms-2">
                                                                        <a href="#"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center "
                                                                            data-url="<?php echo e(route('city.edit', $cit['id'])); ?>"
                                                                            data-size="md" data-ajax-popup="true"
                                                                            data-title="<?php echo e(__('Edit City')); ?>"
                                                                            title="<?php echo e(__('Edit City')); ?>"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top"><i
                                                                                class="ti ti-edit "></i>
                                                                        </a>
                                                                    </div>

                                                                    <div class="action-btn bg-light-secondary ms-2">
                                                                        <a href="#"
                                                                            class="mx-3 btn btn-sm d-inline-flex align-items-center bs-pass-para"
                                                                            data-text="<?php echo e(__('This action can not be undone. Do you want to continue?')); ?>"
                                                                            data-confirm="<?php echo e(__('Are You Sure?')); ?>"
                                                                            data-confirm-yes="delete-form-<?php echo e($cit['id']); ?>"
                                                                            title="<?php echo e(__('Delete')); ?>"
                                                                            data-bs-toggle="tooltip"
                                                                            data-bs-placement="top">
                                                                            <i class="ti ti-trash"></i>
                                                                        </a>
                                                                    </div>

                                                                    <?php echo Form::open([
                                                                        'method' => 'DELETE',
                                                                        'route' => ['city.destroy', $cit['id']],
                                                                        'id' => 'delete-form-' . $cit['id'],
                                                                    ]); ?>

                                                                    <?php echo Form::close(); ?>

                                                                <?php endif; ?>

                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr class="text-center">
                                                    <td colspan="4"><?php echo e(__('No Data Found.!')); ?></td>
                                                </tr>
                                            <?php endif; ?>

                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card shadow-none rounded-0 border-bottom" id="chatgpt-settings">
                        <?php echo e(Form::model($settings, ['route' => 'settings.chatgptkey', 'method' => 'post'])); ?>

                        <div class="card-header">
                            <h5><?php echo e(__('Chat GPT Key Settings')); ?></h5>
                            <small><?php echo e(__('Edit your key details')); ?></small>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-6">
                                    <?php echo e(Form::label('Chat GPT Key', __('Chat GPT Key'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('chatgpt_key', isset($settings['chatgpt_key']) ? $settings['chatgpt_key'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Chatgpt Key Here')])); ?>

                                </div>
                                <div class="form-group col-6">
                                    <?php echo e(Form::label('Chat GPT Model', __('Chat GPT Model'), ['class' => 'col-form-label'])); ?>

                                    <?php echo e(Form::text('chatgpt_model', isset($settings['chatgpt_model']) ? $settings['chatgpt_model'] : '', ['class' => 'form-control', 'placeholder' => __('Enter Chatgpt Model')])); ?>

                                </div>
                            </div>
                        </div>
                        <div class="card-footer text-end">
                            <button class="btn btn-primary" type="submit"><?php echo e(__('Save Changes')); ?></button>
                        </div>
                        <?php echo e(Form::close()); ?>

                    </div>
                    <!-- [ Main Content ] end -->
                </div>
            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('custom-script'); ?>
    <script>
        $(document).ready(function() {

            $.ajax({
                url: "<?php echo e(route('get.country')); ?>",
                type: "GET",
                success: function(result) {

                    $.each(result.data, function(key, value) {

                        setTimeout(function() {
                            if (value.id == '<?php echo e($country_id); ?>') {
                                $("#country").append('<option value="' + value.id +
                                    '" selected class="counties_list">' + value
                                    .country + '</option>');
                            } else {
                                $("#country").append('<option value="' + value.id +
                                    '" class="counties_list">' + value.country +
                                    '</option>');
                            }
                        }, 1000);

                    });

                },
            });

            $.ajax({
                url: "<?php echo e(route('get.all.state')); ?>",
                type: "GET",
                success: function(result) {
                    setTimeout(function() {
                        $.each(result, function(key, value) {

                            if (value.id == '<?php echo e($country_id); ?>') {

                                $("#state_filter").append('<option value="' + value.id +
                                    '" selected>' + value.region + "</option>");
                            } else {
                                $("#state_filter").append('<option value="' + value.id +
                                    '">' + value.region + "</option>");
                            }
                        });
                    }, 1000);

                },
            });

        })

        $(document).on("click", 'a[data-ajax-popup="true"], button[data-ajax-popup="true"], div[data-ajax-popup="true"]',
            function() {

                $.ajax({
                    url: "<?php echo e(route('get.country')); ?>",
                    type: "GET",
                    success: function(result) {

                        $.each(result.data, function(key, value) {
                            setTimeout(function() {
                                $("#state_country").append('<option value="' + value.id +
                                    '" >' + value.country + '</option>');
                            }, 1000);

                        });


                    },
                });



            });
        $(document).on("change", '#city_country', function() {

            var country_id = this.value;

            $("#city_state").html("");
            $.ajax({
                url: "<?php echo e(route('get.state')); ?>",
                type: "POST",
                data: {
                    country_id: country_id,
                    _token: "<?php echo e(csrf_token()); ?>",
                },
                dataType: "json",
                success: function(result) {
                    setTimeout(function() {
                        console.log(result);
                        $.each(result.data, function(key, value) {
                            $("#city_state").append('<option value="' + value.id +
                                '">' +
                                value.region + "</option>");
                        });
                        $("#city").html('<option value="">Select State First</option>');
                    }, 1000);
                },
            });
        });
        $('#country').on('change', function() {
            $('#customer_submit').trigger('submit');
            return false;
        })
        $('#state_filter').on('change', function() {
            $('#state_filter_submit').trigger('submit');
            return false;
        })

        <?php if($filter_data == 'filtered'): ?>
            $([document.documentElement, document.body]).animate({
                scrollTop: $("#location-list").offset().top
            }, 2000);
        <?php endif; ?>

        $('.colorPicker').on('click', function(e) {
            $('body').removeClass('custom-color');
            if (/^theme-\d+$/) {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass('custom-color');
            $('.themes-color-change').removeClass('active_color');
            $(this).addClass('active_color');
            const input = document.getElementById("color-picker");
            setColor();
            input.addEventListener("input", setColor);

            function setColor() {
                $(':root').css('--color-customColor', input.value);
            }

            $(`input[name='color_flag`).val('true');
        });

        $('.themes-color-change').on('click', function() {

        $(`input[name='color_flag`).val('false');

            var color_val = $(this).data('value');
            $('body').removeClass('custom-color');
            if(/^theme-\d+$/)
            {
                $('body').removeClassRegex(/^theme-\d+$/);
            }
            $('body').addClass(color_val);
            $('.theme-color').prop('checked', false);
            $('.themes-color-change').removeClass('active_color');
            $('.colorPicker').removeClass('active_color');
            $(this).addClass('active_color');
            $(`input[value=${color_val}]`).prop('checked', true);
        });

        $.fn.removeClassRegex = function(regex) {
            return $(this).removeClass(function(index, classes) {
                return classes.split(/\s+/).filter(function(c) {
                    return regex.test(c);
                }).join(' ');
            });
        };

    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\Downloads\.versity\project\final-project\Juristec-Law-Mangemnet-system\resources\views/settings/admin.blade.php ENDPATH**/ ?>