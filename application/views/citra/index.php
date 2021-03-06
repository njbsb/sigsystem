<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
    <li class="breadcrumb-item active">Citra</li>
</ol>

<h2 class="text-center margin"><?= $title ?></h2>
<div class="row text-center ">
    <?php foreach ($citras as $citra) : ?>
    <div class="col-sm-4">
        <div class="card mb-3">
            <h3 class="card-header">
                <?= $citra['code'] ?>
            </h3>
            <!-- object-fit:cover for square crop or max-width:100%;
                    border-radius:50%; for circle crop -->
            <img style="max-height:200px; display: block; object-fit:cover;  padding:10px;" src="<?= base_url('assets/images/citra/' . $citra['code'] . '.jpg') ?>">

            <div class="card-body">
                <h5 class="card-title">
                    <a href="<?= site_url('/citra/' . $citra['code']); ?>">
                        <?= $citra['name_en'] ?>
                    </a>
                </h5>
                <h6 class="card-subtitle text-white"><?= $citra['name_bm'] ?></h6>
            </div>
            <div class="card-footer text-white">
                <?= $citra['level'] ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
</div>