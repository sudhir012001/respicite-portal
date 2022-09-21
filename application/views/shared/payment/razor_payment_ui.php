<body class="hold-transition login-page">
    <div class="content-wrapper bg-white">
        <!-- Content Header (Page header) -->
        <section class="content-header mb-3 bg-white" style="padding: 6px 0.5rem;border-bottom: 1px solid #dee2e6;">
            <div class="container-fluid">
                <div class="row mb-2">

                    <div class="col-sm-6">
                        <h1 class="m-0 pt-2" style="font-size: 1.2em;">Pay online</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right pt-1">
                            <li class="breadcrumb-item"><a
                                    href="<?php echo base_url().'BaseController/dashboard'; ?>">Dashboard</a></li>
                            <li class="breadcrumb-item">Pay online</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <br> <br>
                <div class="row">
                    <div class="col-3"></div>
                    <div class="col-6">
                        <div class="card card-widget widget-user shadow">
                            <div class="widget-user-header bg-success" style="height: 80px;">
                                <h3 class="widget-user-username">
                                    <?php echo $fullname;?>
                                </h3>
                                <h5 class="widget-user-desc">
                                    <?php echo $type;?>
                                </h5>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-sm-6 border-right">
                                        <div class="description-block">
                                            <span class="description-text">Purchase Couseling</span>
                                            <h5 class="description-header">
                                                <?php echo $fullname;?>
                                            </h5>
                                        </div>

                                    </div>

                                    <div class="col-sm-6">
                                        <div class="description-block">
                                            <span class="description-text">Price</span>
                                            <h5 class="description-header">
                                                <?php echo $mrp;?>
                                            </h5>
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <button class="btn btn-success w-100 mt-5" id="rzp-button1">PAY</button>

                                        <p class="mt-3">Note : <strong>Please do not refresh this page.</strong></p>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>

    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
    <script>
        let options_json = `<?php echo $api_pay; ?>`;
        let options = JSON.parse(options_json);
        let rzp1 = new Razorpay(options);
        document.getElementById('rzp-button1').onclick = function (e) {
            rzp1.open();
            e.preventDefault();
        }

        document.getElementById('rzp-button1').click();
    </script>