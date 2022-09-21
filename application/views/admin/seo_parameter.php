<div class="wrapper">
    <div class="content-wrapper">
        <section class="content-header mb-3"
            style="padding: 6px 0.5rem;background-color: #ffffff;border-bottom: 1px solid #dee2e6;">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 pt-2" style="font-size: 1.2em;color: #7f7f7f;">Manage SEO Parameter</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right pt-1">
                            <li class="breadcrumb-item"><a href="<?php echo base_url("
                                    AdminController/dashboard");?>">Dashboard</a></li>
                            <li class="breadcrumb-item active"><a href="<?php echo base_url()."AdminController/seo_parameter";?>">Manage SEO Parameter</a></li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="container-fluid">
                    <!-- <div class="alert alert-success">
                    </div> -->
                <section class="content">
                    <div class="container-fluid">
                        <div class="card card-default collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">Channels</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 d-flex align-items-stretch flex-column">
                                        <div class="card-body">
                                            <div class="text-right"><a href="<?php echo base_url('AdminController/seo_channel');?>" class="btn btn-primary m-1"><i class="fas fa-plus"></i> Add</a></div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td>Description</td>
                                                        <td>Action</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($seo_parameters["channels"] as $v){ ?>
                                                        <tr>
                                                            <td><?php echo $v->name;?></td>
                                                            <td><?php echo $v->description;?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                <a href="<?php echo base_url('AdminController/seo_channel/'.$v->id);?>" class="btn btn-primary">Edit</a>
                                                                <a href="<?php echo base_url('AdminController/seo_remove/channel/'.$v->id);?>" class="btn btn-danger">Delete</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-default collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">Location</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 d-flex align-items-stretch flex-column">
                                        <div class="card-body">
                                            <div class="text-right"><a href="<?php echo base_url('AdminController/seo_location');?>" class="btn btn-primary m-1"><i class="fas fa-plus"></i> Add</a></div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td>Name</td>
                                                        <td>Action</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($seo_parameters["local"] as $v){ ?>
                                                        <tr>
                                                            <td><?php echo $v->name;?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                <a href="<?php echo base_url('AdminController/seo_location/'.$v->id);?>" class="btn btn-primary">Edit</a>
                                                                <a href="<?php echo base_url('AdminController/seo_remove/loaction/'.$v->id);?>" class="btn btn-danger">Delete</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card card-default collapsed-card">
                            <div class="card-header">
                                <h3 class="card-title">Top Skills</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12 d-flex align-items-stretch flex-column">
                                        <div class="card-body">
                                            <div class="text-right"><a href="<?php echo base_url('AdminController/seo_top_skill');?>" class="btn btn-primary m-1"><i class="fas fa-plus"></i> Add</a></div>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <td>Skills</td>
                                                        <td>Description</td>
                                                        <td>Action</td>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($seo_parameters["top_skills"] as $v){ ?>
                                                        <tr>
                                                            <td><?php echo $v->skill;?></td>
                                                            <td><?php echo $v->description;?></td>
                                                            <td>
                                                                <div class="btn-group">
                                                                <a href="<?php echo base_url('AdminController/seo_top_skill/'.$v->id);?>" class="btn btn-primary">Edit</a>
                                                                <a href="<?php echo base_url('AdminController/seo_remove/top_skill/'.$v->id);?>" class="btn btn-danger">Delete</a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </section>
            </div>
        </section>
    </div>
</div>