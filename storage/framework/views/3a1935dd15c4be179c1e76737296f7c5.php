<?php $__env->startSection('page-title', __('Dashboard')); ?>


<?php $__env->startSection('content'); ?>
<div class="col-sm-12">
    <div class="row g-0">
        <div class="col-12">
            <div
                class="row overflow-hidden g-0 pt-0 row-cols-1  row-cols-md-2 row-cols-xxl-3 row-cols-lg-3 row-cols-sm-2">
                <div class="col-xxl-4 border-end border-bottom">
                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="theme-avtar bg-primary">
                                <i class="ti ti-home"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-muted"><?php echo e(__('Paid Users')); ?> :
                                    <?php echo e(number_format($user['total_paid_user'])); ?>

                                </h5>
                            </div>
                        </div>
                        <h5 class="mb-0"><?php echo e(__('Total Users')); ?> : <?php echo e(number_format($user['total_user'])); ?>

                        </h5>
                    </div>
                </div>

                <div class="col-xxl-4 border-end border-bottom">
                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="theme-avtar bg-info">
                                <i class="ti ti-shopping-cart-plus"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-muted"><?php echo e(__('Total Order Amount')); ?> :
                                    <?php echo e(number_format($user['total_orders_price'])); ?>

                                </h5>
                            </div>
                        </div>
                        <h5 class="mb-0"><?php echo e(__('Total Orders')); ?> : <?php echo e(number_format($user['total_orders'])); ?>

                        </h5>
                    </div>
                </div>

                <div class="col-xxl-4 border-end border-bottom">
                    <div class="p-3">
                        <div class="d-flex justify-content-between mb-3">
                            <div class="theme-avtar bg-danger">
                                <i class="ti ti-trophy"></i>
                            </div>
                            <div>
                                <h5 class="mb-0 text-muted"><?php echo e(__('Most Purchase Plan')); ?> :
                                    <?php echo e(number_format($user['most_purchese_plan'])); ?>

                                </h5>
                            </div>
                        </div>
                        <h5 class="mb-0"><?php echo e(__('Total Plans')); ?> : <?php echo e(number_format($user['total_plan'])); ?>

                        </h5>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 border-bottom">
            <div class="card shadow-none bg-transparent border-bottom rounded-0 mb-0">
                <div class="card-header">
                    <h5><?php echo e(__('Recent Order')); ?></h5>
                </div>
                <div class="card-body">
                    <div id="chart-sales" style="min-height: 165px;">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('custom-script'); ?>


<script>
    (function () {
            var chartBarOptions = {
                series: [
                    {
                        name: '<?php echo e(__("Income")); ?>',
                        data:  <?php echo json_encode ($chartData['data']); ?>,


                    },
                ],

                chart: {
                    height: 300,
                    type: 'area',

                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    width: 2,
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                xaxis: {
                    categories: <?php echo json_encode($chartData['label']); ?>,
                    title: {
                        text: '<?php echo e(__("Months")); ?>'
                    }
                },
                colors: ['#6fd944', '#6fd944'],

                grid: {
                    strokeDashArray: 4,
                },
                legend: {
                    show: false,
                },

                yaxis: {
                    title: {
                        text: '<?php echo e(__("Income")); ?>'
                    },

                }

            };
            var arChart = new ApexCharts(document.querySelector("#chart-sales"), chartBarOptions);
            arChart.render();
        })();
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\Users\Asus\Downloads\.versity\project\final-project\Juristec-Law-Mangemnet-system\resources\views/admin_dash.blade.php ENDPATH**/ ?>