<?php $__env->startSection('page-title'); ?>
    <?php echo e(__('Landing Page')); ?>

<?php $__env->stopSection(); ?>
<?php $__env->startSection('breadcrumb'); ?>
    <li class="breadcrumb-item"><?php echo e(__('Landing Page')); ?></li>
<?php $__env->stopSection(); ?>

<?php
    $logo=\App\Models\Utility::get_file('uploads/logo');
    $settings = \Modules\LandingPage\Entities\LandingPageSetting::settings();
?>





<?php $__env->startSection('content'); ?>
        <div class="col-sm-12">
            <div class="row g-0">
                <div class="col-xl-3 border-end border-bottom">
                    <div class="card shadow-none bg-transparent sticky-top" style="top:30px">
                        <div class="list-group list-group-flush rounded-0" id="useradd-sidenav">

                            <?php echo $__env->make('landingpage::layouts.tab', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                        </div>
                    </div>
                </div>

                <div class="col-xl-9 border-end ">
                
                    <?php echo e(Form::model(null, array('route' => array('landingpage.store'), 'method' => 'POST'))); ?>

                    <?php echo csrf_field(); ?>
                        <div class="card rounded-0 shadow-none bg-transparent">
                            <div class="card-header">
                                <div class="row align-items-center">
                                    <div class="col-6">
                                        <h5 class="mb-2"><?php echo e(__('Top Bar')); ?></h5>
                                    </div>
                                    <div class="col switch-width text-end">
                                        <div class="form-group mb-0">
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" data-toggle="switchbutton" data-onstyle="primary" class="" name="topbar_status"
                                                    id="topbar_status" <?php echo e($settings['topbar_status'] == 'on' ? 'checked="checked"' : ''); ?>>
                                                <label class="custom-control-label" for="topbar_status"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-12">
                                        <?php echo e(Form::label('content', __('Message'), ['class' => 'col-form-label text-dark'])); ?>

                                        <?php echo e(Form::textarea('topbar_notification_msg',$settings['topbar_notification_msg'], ['class' => 'summernote form-control', 'required' => 'required'])); ?>

                                    </div>

                                </div>
                            </div>
                            <div class="card-footer text-end border-bottom rounded-0">
                                <input class="btn btn-print-invoice btn-primary m-r-10" type="submit" value="<?php echo e(__('Save Changes')); ?>">
                            </div>
                        </div>
                    <?php echo e(Form::close()); ?>


                
                </div>
            </div>
        </div>
<?php $__env->stopSection(); ?>



<?php $__env->startPush('custom-script'); ?>
<script src="<?php echo e(asset('css/summernote/summernote-bs4.js')); ?>"></script>

<script>
    $('.summernote').summernote({
        dialogsInBody: !0,
        minHeight: 250,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'strikethrough']],
            ['list', ['ul', 'ol', 'paragraph']],
            ['insert', ['link', 'unlink']],
        ]
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\Downloads\.versity\project\final-project\Juristec-Law-Mangemnet-system\Modules/LandingPage\Resources/views/landingpage/topbar.blade.php ENDPATH**/ ?>