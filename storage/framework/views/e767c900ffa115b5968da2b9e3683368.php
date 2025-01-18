<?php
    use App\Models\Utility;
    $settings = Utility::settings();

    $logo = Utility::get_file('uploads/logo');


    $company_favicon = $settings['company_favicon'] ?? '';

    $SITE_RTL = $settings['SITE_RTL'];

    $color = !empty($settings['color']) ? $settings['color'] : 'theme-1';
    if(isset($settings['color_flag']) && $settings['color_flag'] == 'true')
    {
        $themeColor = 'custom-color';
    }
    else {
        $themeColor = $color;
    }

    $SITE_RTL = 'off';
    if (!empty($settings['SITE_RTL'])) {
        $SITE_RTL = $settings['SITE_RTL'];
    }

    $logo_light = $settings['company_logo_light'] ?? '';
    $logo_dark = $settings['company_logo_dark'] ?? '';
    $company_logo = Utility::get_company_logo();
    $company_logos = $settings['company_logo_light'] ?? '';

    $lang = \App::getLocale('lang');
    if($lang == 'ar' || $lang == 'he'){
        $SITE_RTL = 'on';
    }
?>

<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" dir="<?php echo e($SITE_RTL == 'on' ? 'rtl' : ''); ?>">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="keywords" content="Dashboard Template" />
    <meta name="author" content="Rajodiya Infotech" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <title>
        <?php echo e(Utility::getValByName('title_text') ? Utility::getValByName('title_text') : config('app.name', 'AdvocateGo-SaaS')); ?>

        - <?php echo $__env->yieldContent('page-title'); ?> </title>

    <!-- Primary Meta Tags -->
    <meta name="title" content=<?php echo e($settings['meta_keywords'] ?? ''); ?>>
    <meta name="description" content=<?php echo e($settings['meta_description'] ?? ''); ?>>

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content=<?php echo e(env('APP_URL')); ?>>
    <meta property="og:title" content=<?php echo e($settings['meta_keywords'] ?? ''); ?>>
    <meta property="og:description" content=<?php echo e($settings['meta_description'] ?? ''); ?>>
    <meta property="og:image" content=<?php echo e(asset(Storage::url('uploads/metaevent/' . $settings['meta_image'] ?? ''))); ?>>

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content=<?php echo e(env('APP_URL')); ?>>
    <meta property="twitter:title" content=<?php echo e($settings['meta_keywords'] ?? ''); ?>>
    <meta property="twitter:description" content=<?php echo e($settings['meta_description'] ?? ''); ?>>
    <meta property="twitter:image"
        content=<?php echo e(asset(Storage::url('uploads/metaevent/' . $settings['meta_image'] ?? ''))); ?>>


    <!-- Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

    <!-- Favicon icon -->

    <link rel="icon"
        href="<?php echo e($logo . '/' . (isset($company_favicon) && !empty($company_favicon) ? $company_favicon : 'favicon.png') . '?timestamp=' . time()); ?>"
        type="image" sizes="800x800">

    <!-- font css -->
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/tabler-icons.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/feather.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/fontawesome.css')); ?>">
    <link rel="stylesheet" href="<?php echo e(asset('assets/fonts/material.css')); ?>">

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/plugins/notifier.css')); ?>">

    <?php if($settings['cust_darklayout'] == 'on'): ?>
        <?php if(isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on'): ?>
            <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>" id="main-style-link">
        <?php endif; ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-dark.css')); ?>">
    <?php else: ?>
        <?php if(isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on'): ?>
            <link rel="stylesheet" href="<?php echo e(asset('assets/css/style-rtl.css')); ?>" id="main-style-link">
        <?php else: ?>
            <link rel="stylesheet" href="<?php echo e(asset('assets/css/style.css')); ?>" id="main-style-link">
        <?php endif; ?>
    <?php endif; ?>
    <?php if(isset($settings['SITE_RTL']) && $settings['SITE_RTL'] == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom-auth-rtl.css')); ?>" id="main-style-link">
    <?php else: ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom-auth.css')); ?>" id="main-style-link">
    <?php endif; ?>
    <?php if($settings['cust_darklayout'] == 'on'): ?>
        <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom-dark.css')); ?>" id="main-style-link">
    <?php endif; ?>

    <link rel="stylesheet" href="<?php echo e(asset('assets/css/custom-color.css')); ?>">

    <style>
        :root {
            --color-customColor: <?= $color ?>;
        }
    </style>
</head>

<body class="<?php echo e($themeColor); ?>">

    <div class="custom-login">
        <div class="login-bg-img">
            <img src="<?php echo e(isset($setting['color_flag']) && $setting['color_flag'] == 'false' ? asset('assets/images/auth/'.$color.'.svg') : asset('assets/images/auth/theme-1.svg')); ?>" class="login-bg-1">
            <img src="<?php echo e(asset('assets/images/auth/common.svg')); ?>" class="login-bg-2">
        </div>
        <div class="bg-login bg-primary"></div>

        <div class="custom-login-inner">
            <header class="dash-login-header">
                <nav class="navbar navbar-expand-md default">
                    <div class="container">
                        <div class="navbar-brand">
                            <a href="#">
                                <?php if($settings['cust_darklayout'] && $settings['cust_darklayout'] == 'on'): ?>
                                    <img src="<?php echo e($logo . '/' . (isset($company_logos) && !empty($company_logos) ? $company_logos : 'logo-dark.png') . '?' . time()); ?>"
                                        alt="<?php echo e(config('app.name', 'AdvocateGo-SaaS')); ?>" class="logo "
                                        style="height: 30px; width: 180px;" loading="lazy">
                                <?php else: ?>
                                    <img src="<?php echo e($logo . '/' . (isset($company_logo) && !empty($company_logo) ? $company_logo : 'logo-dark.png') . '?' . time()); ?>"
                                        alt="<?php echo e(config('app.name', 'AdvocateGo-SaaS')); ?>" class="logo "
                                        style="height: 30px; width: 180px;" loading="lazy">
                                <?php endif; ?>
                            </a>
                        </div>
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                            data-bs-target="#navbarlogin">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarlogin">
                            <ul class="navbar-nav align-items-center ms-auto mb-2 mb-lg-0">
                                <li class="nav-item">
                                    <?php echo $__env->make('landingpage::layouts.buttons', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                </li>
                                <?php echo $__env->yieldContent('language-bar'); ?>
                            </ul>
                        </div>
                    </div>
                </nav>
            </header>
            <main class="custom-wrapper">
                <div class="custom-row">
                    <div class="card">
                        <?php echo $__env->yieldContent('content'); ?>
                    </div>
                </div>
            </main>
            <footer>
                <div class="auth-footer">
                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <span>&copy;
                                    <?php echo e($settings['footer_text'] ? $settings['footer_text'] : config('app.name', 'AdvocateGo SaaS')); ?>

                                    <?php echo e(date('Y')); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- [ auth-signup ] end -->
    <?php echo $__env->make('layouts.cookie_consent', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <script src="<?php echo e(asset('assets/js/vendor-all.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/bootstrap.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/feather.js')); ?>"></script>
    <script src="<?php echo e(asset('js/jquery.js')); ?>"></script>
    <script src="<?php echo e(asset('assets/js/plugins/notifier.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('custom-scripts'); ?>

    <?php if($message = Session::get('success')): ?>
        <script>
            show_toastr('<?php echo e(__('Success')); ?>', '<?php echo $message; ?>', 'success')
        </script>
    <?php endif; ?>

    <?php if($message = Session::get('error')): ?>
        <script>
            show_toastr('<?php echo e(__('Error')); ?>', '<?php echo $message; ?>', 'error')
        </script>
    <?php endif; ?>
</body>

</html>
<?php /**PATH C:\Users\Asus\Downloads\.versity\project\final-project\Juristec-Law-Mangemnet-system\resources\views/layouts/guest.blade.php ENDPATH**/ ?>