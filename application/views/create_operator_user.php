
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="RIUNG APP X-COMET CREATE USER">
    <meta name="author" content="DANANG ARIF RAHMANDA">
    <meta name="generator" content="LENOVO THINKPAD LL390">
    <title><?=$title?></title>

    <!-- <link rel="canonical" href="https://getbootstrap.com/docs/4.5/examples/sign-in/"> -->

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

        <!-- Favicons -->
    <!-- <link rel="apple-touch-icon" href="/docs/4.5/assets/img/favicons/apple-touch-icon.png" sizes="180x180">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-32x32.png" sizes="32x32" type="image/png">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon-16x16.png" sizes="16x16" type="image/png">
    <link rel="manifest" href="/docs/4.5/assets/img/favicons/manifest.json">
    <link rel="mask-icon" href="/docs/4.5/assets/img/favicons/safari-pinned-tab.svg" color="#563d7c">
    <link rel="icon" href="/docs/4.5/assets/img/favicons/favicon.ico">
    <meta name="msapplication-config" content="/docs/4.5/assets/img/favicons/browserconfig.xml">
    <meta name="theme-color" content="#563d7c"> -->


        <style>
          .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
          }

          @media (min-width: 768px) {
            .bd-placeholder-img-lg {
              font-size: 3.5rem;
            }
          }
        </style>
        <!-- Custom styles for this template -->
        <!-- <link href="https://getbootstrap.com/docs/4.5/examples/sign-in/signin.css" rel="stylesheet"> -->
      </head>
      <body >
        <div class="container">
          <div class="row">
            <div class="col-sm-12 mt-5">
              <h5 class="head">Create Operator User</h5>
              <?php
              if($this->session->flashdata('error')){
              ?>
              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>Failed!</strong> <?=$this->session->flashdata('error')?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              }
              if($this->session->flashdata('success')){
              ?>
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success!</strong> <?=$this->session->flashdata('success')?>
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              <?php
              }
              ?>
              <form class="create_user" action="<?=base_url('Operator/create_user')?>" method="POST">
                <div class="form-group">
                  <label class="label-control">Nama: </label>
                  <input class="form-control" name="nama" id="nama" placeholder="Nama" autocomplete="off" autofocus="on">
                </div>
                <div class="form-group">
                  <label class="label-control">NRP:</label>
                  <input class="form-control" name="nrp" id="nro" placeholder="NRP" autocomplete="off">
                </div>
                <div class="form-group">
                  <label class="label-control">AS:</label>
                  <select class="form-control" name="sebagai" id="sebagai">
                    <?php
                    foreach($data_operator->result() as $key){
                    ?>
                    <option value="<?=$key->id?>" label="<?=$key->nama_divisi?>"><?=$key->nama_divisi?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
                <div class="form-group">
                  <label class="label-control">Level:</label>
                  <select class="form-control" name="level">
                    <?php
                    foreach($data_level->result() as $v){
                    ?>
                    <option value="<?=$v->ULEVEL?>" label="<?=$v->type;?>"><?=$v->type;?></option>
                    <?php
                    }
                    ?>
                  </select>
                </div>
              <button class="btn btn-lg btn-primary btn-block" type="submit">Create</button>
              <p class="mt-5 mb-3 text-muted">&copy; <?=date('Y')?></p>
            </form>
            </div>
          </div>
        </div>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
        <script type="text/javascript">
          $('.alert').alert();
        </script>
    </body>
    </html>
