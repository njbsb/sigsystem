<h2 class="text-center"><?php echo $title; ?></h2>

<div class="container-fluid text-center">
    <div class="row">
        <div class="col-lg-4">
            <div class="card border-dark mb-3" style="max-width: 20rem;">
                <?php if ($mentor['profile_image']) : ?>
                    <img style="max-height:300px; display: block; object-fit:cover; padding:10px;" src="<?= base_url('assets/images/profile/' . $mentor['profile_image']) ?>">
                <?php else : ?>
                    <img style="max-height:300px; display: block; object-fit:cover; padding:10px;" src="<?= base_url('assets/images/profile/default.jpg') ?>">
                <?php endif ?>
                <div class="card-footer text-muted">
                    <?= $mentor['id'] ?>
                </div>
            </div>
        </div>
        <div class="col-lg-8 text-left">
            <h3><b><?= $mentor['name'] ?></b></h3>
            <h6><b>Position:</b> <?= $mentor['position'] ?></h6>
            <h6><b>Email:</b> <a href="mailto:<?= $mentor['email'] ?>"><?= $mentor['email'] ?></a></h6>
            <h6><b>SIG:</b> <?= $mentor['signame'] ?></h6>
            <h6><b>SIG Role:</b> <?= $mentor['rolename'] ?></h6>
            <h6><b>Room Num:</b> <?= $mentor['roomnum'] ?></h6>

        </div>
    </div>
    <hr>
    <h2>Previous Activity and Roles</h2> <br>
    <?php if ($activity_roles) : ?>
        <h4>Activities</h4>
        <div class="row justify-content-center">
            <?php foreach ($activity_roles as $actrole) : ?>
                <div class="col-md-4">
                    <div class="card text-white bg-dark mb-3">
                        <div class="card-header"><a class="text-white" href="<?= site_url('activity/' . $actrole['slug']) ?>"><?= $actrole['activity_name'] ?></a></div>
                        <div class="card-body">
                            <h4 class="card-title">Advisor</h4>
                            <p class="card-text"><?= $actrole['academicsession'] ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    <?php else : ?>
        <p>No data of activity roles found</p>
    <?php endif ?>
</div>