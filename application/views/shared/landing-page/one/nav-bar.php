


<nav class="navbar navbar-expand-lg bg-white navbar-light shadow border-top border-5 border-primary sticky-top p-0">
<?php foreach($template as $row){
    if($row['parameter'] =='logo_text'){
?>
        
        <a href="index.html" class="navbar-brand bg-primary d-flex align-items-center px-4 px-lg-5">
            <h2 class="mb-2 text-white"><?php echo $row['value'];?></h2>
        </a>
        <button type="button" class="navbar-toggler me-4" data-bs-toggle="collapse" data-bs-target="#navbarCollapse">
            <span class="navbar-toggler-icon"></span>
        </button>
        <?php } ?>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <div class="navbar-nav ms-auto p-4 p-lg-0">
                <?php  if($row['parameter'] =='nav-item1'){ ?>
                <a href="index.html" class="nav-item nav-link active"><?php echo $row['value'];?></a>
                <?php } if($row['parameter'] =='nav-item2'){?>
                <a href="about.html" class="nav-item nav-link"><?php echo $row['value'];?></a>
                <?php } if($row['parameter'] =='nav-item3'){ ?>
                <a href="service.html" class="nav-item nav-link"><?php echo $row['value'];?></a>
                <?php } ?>
                <div class="nav-item dropdown">
                    <?php if($row['parameter'] =='nav-link dropdown-toggle'){ ?>
                    <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown"><?php echo $row['value'];?></a>
                    <?php } ?>
                    <div class="dropdown-menu fade-up m-0">
                        <?php if($row['parameter'] =='dropdown-item1'){ ?>
                        <a href="price.html" class="dropdown-item"><?php echo $row['value'];?></a>
                        <?php } if($row['parameter'] =='dropdown-item2'){ ?>
                        <a href="feature.html" class="dropdown-item"><?php echo $row['value'];?></a>
                        <?php } if($row['parameter'] =='dropdown-item3'){?>
                        <a href="quote.html" class="dropdown-item"><?php echo $row['value'];?></a>
                        <?php } if($row['parameter'] =='dropdown-item4'){ ?>
                        <a href="team.html" class="dropdown-item"><?php echo $row['value'];?></a>
                        <?php } if($row['parameter'] =='dropdown-item5'){?>
                        <a href="testimonial.html" class="dropdown-item"><?php echo $row['value'];?></a>
                        <?php } if($row['parameter'] =='dropdown-item6'){ ?>
                        <a href="404.html" class="dropdown-item"><?php echo $row['value'];?></a>
                        <?php }?>
                    </div>
                </div>
                <?php if($row['parameter'] =='nav-item4'){ ?>
                <a href="contact.html" class="nav-item nav-link"><?php echo $row['value'];?></a>
                <?php }?>
            </div>
            <?php if($row['parameter'] =='tel_contact'){?>
            <h4 class="m-0 pe-lg-5 d-none d-lg-block"><i class="fa fa-headphones text-primary me-3"></i><?php echo $row['value'];?></h4>
            <?php }?>
        </div>
         <?php } ?>
    </nav>
   