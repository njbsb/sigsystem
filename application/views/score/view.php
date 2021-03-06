<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="<?= site_url() ?>">Home</a></li>
    <li class="breadcrumb-item"><a href="<?= site_url('score') ?>">Score</a></li>
    <li class="breadcrumb-item"><a href="<?= site_url('score/' . $academicsession['slug']) ?>"><?= $academicsession['academicsession'] ?></a></li>
    <li class="breadcrumb-item active"><?= $student['id'] ?></li>
</ol>

<h2 class="margin">Student: <?= $student['name'] ?></h2>
<hr>
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table text-center table-hover">
                <thead class="table-dark">
                    <tr>
                        <td>Academic Session</td>
                        <?php foreach ($scoreplans as $scoreplan) : ?>
                        <td class="text-warning" data-toggle="tooltip" data-placement="top" title="<?= $scoreplan['percentweightage'] ?>%"><?= ucfirst($scoreplan['label']) ?></td>
                        <?php endforeach ?>
                        <td class="text-info" data-toggle="tooltip" data-placement="top" title="15%">Components</td>
                        <td data-toggle="tooltip" data-placement="top" title="55%">Total</td>
                    </tr>
                </thead>
                <tbody class="table-active">
                    <tr>
                        <td><?= $academicsession['academicsession'] ?></td>
                        <?php foreach ($scoreplans as $scoreplan) : ?>
                        <?php $badge = (array_sum($scoreplan['scores']) > 18 and $scoreplan['activitycategory_id'] == 'A') ? '<i class="fas fa-award"></i>' : '' ?>
                        <td class=""><?= $scoreplan['totalpercent'] ?> % <?= $badge ?></td>
                        <?php endforeach ?>
                        <td><?= $scorecomps['totalpercent'] ?> % </td>
                        <td><?= $totalwhole ?> %</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
<hr>

<div class="row">
    <?php foreach ($scoreplans as $scoreplan) : ?>
    <div class="col-lg-6">
        <div class="card" style="margin-bottom:20px;">
            <div class="card-body">
                <h5 class="text-primary text-center"><b>Level <?= $scoreplan['label'] ?></b></h5>
                <hr>
                <div class="form-group">
                    <!-- <h6>Total:</h6> -->
                    <?php $textclass = (array_sum($scoreplan['scores']) > 18) ? 'text-primary' : '' ?>
                    <?php $scpbadge = (array_sum($scoreplan['scores']) > 18 and $scoreplan['activitycategory_id'] == 'A') ? '<i class="fas fa-award" style="color: #464F64;"></i>' : '' ?>
                    <h3 class="<?= $textclass ?> text-center"><?= array_sum($scoreplan['scores']) ?>/<?= $scoreleveltotal ?> <?= $scpbadge ?></h3>
                </div>
                <div class="form-group">
                    <h6 for="activity"><?= $scoreplan['category'] . ': ' . $scoreplan['title'] ?></h6>
                </div>
                <div class="row">
                    <?php if ($scoreplan['scores']) : ?>
                    <?php foreach ($scoreplan['scores'] as $key => $score) :  ?>
                    <div class="col-sm-3 text-center">
                        <div class="form-group card" style="padding: 4px; border-radius: 10px">
                            <p class="text-info"><?= ucfirst($key) ?></p>
                            <h3><?= $score ?></h3>
                            <?php foreach ($guide[$key] as $scoreguide) : ?>
                            <?php if ($scoreguide['score'] == $score) : ?>
                            <small><?= $scoreguide['description']  ?></small>
                            <?php endif ?>
                            <?php endforeach ?>
                        </div>
                    </div>
                    <?php endforeach ?>
                    <div class="col">
                        <button data-toggle="modal" data-target="#edit<?= $scoreplan['label'] ?>" class="btn btn-outline-dark">Edit Score</button>
                    </div>
                    <?php else : ?>
                    <div class="col">

                        <button data-toggle="modal" data-target="#add<?= $scoreplan['label'] ?>" class="btn btn-dark">Add Score</button>
                        <br><small>You have not added score for <?= $student['name'] ?> on this level</small>
                    </div>
                    <?php endif ?>

                </div>
            </div>
        </div>
    </div>
    <!-- ADD -->
    <div id="add<?= $scoreplan['label'] ?>" class="modal fade card">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Score: Level <?= $scoreplan['label'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $hidden = array(
                        'acslug' => $academicsession['slug'],
                        'scoreplan_id' => $scoreplan['id'],
                        'student_id' => $student_id
                    );
                    ?>
                <?= form_open('score/add_scorelevel', '', $hidden) ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="activity">Activity</label>
                        <input name='activity' type="text" class="form-control" value="<?= $scoreplan['title'] ?>" readonly>
                    </div>

                    <?php foreach ($levelrubrics as $key => $value) : ?>
                    <div class="form-group">
                        <label><?= ucfirst($key) ?></label>
                        <select name="keys[<?= $key ?>]" id="<?= $key ?>" class="custom-select" required>
                            <option value="" disabled selected>Select <?= $key ?> score</option>
                            <?php foreach ($guide[$key] as $scoreguide) : ?>
                            <option value="<?= $scoreguide['id'] ?>"><?= $scoreguide['concat'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php endforeach ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Submit</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Dismiss</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <!-- EDIT -->
    <div id="edit<?= $scoreplan['label'] ?>" class="modal fade card">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Score: Level <?= $scoreplan['label'] ?></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $hidden = array(
                        'acslug' => $academicsession['slug'],
                        'scoreplan_id' => $scoreplan['id'],
                        'student_id' => $student_id
                    );
                    ?>
                <?= form_open('score/edit_scorelevel', '', $hidden) ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="activity">Activity</label>
                        <input type="text" class="form-control" value="<?= $scoreplan['title'] ?>" readonly>
                    </div>
                    <?php foreach ($scoreplan['scores'] as $key => $value) : ?>
                    <div class="form-group">
                        <label><?= ucfirst($key) ?></label>
                        <select name="keys[<?= $key ?>]" id="<?= $key ?>" class="custom-select" required>
                            <option value="" disabled selected>Select <?= $key ?> score</option>
                            <?php foreach ($guide[$key] as $scoreguide) : ?>
                            <?php $selected = ($scoreguide['score'] == $value) ? 'selected' : '' ?>
                            <option value="<?= $scoreguide['id'] ?>" <?php echo $selected ?>><?= $scoreguide['concat'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <?php endforeach ?>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Update</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Dismiss</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
    <?php endforeach ?>
    <div class="col-md 6">
        <div class="card" style="border-radius: 12px; padding-top: 20px; padding-bottom: 20px;">
            <div class="container">
                <h5 class="text-primary text-center"><b>Component</b></h5>
                <hr>
                <div class="form-group">
                    <!-- <h6>Total:</h6> -->
                    <?php $sum = $scorecomps['scores'] ? array_sum($scorecomps['scores']) : 0 ?>
                    <h3 class="text-center"><?= $sum ?>/15</h3>
                    <?php if ($sum == 15) : ?>
                    <small>Full score!</small>
                    <?php endif ?>

                </div>
                <?php $status = ($scorecomps['scores']) ? 'Complete &#9989;' : 'Incomplete &#10060;' ?>
                <label for="">Component: <?= $status ?></label>

                <div class="row justify-content-center">
                    <?php if ($scorecomps['scores']) : ?>
                    <?php foreach ($scorecomps['scores'] as $key => $value) : ?>
                    <div class="col-sm-4 text-center">
                        <div class="form-group card" style="padding: 4px; border-radius: 10px">
                            <p class="text-info"><?= ucfirst($key) ?></p>
                            <h3><?= $value ?></h3>
                        </div>
                    </div>
                    <?php endforeach ?>
                    <div class="col">
                        <button data-toggle="modal" data-target="#editcomp" class="btn btn-outline-dark">Edit Score</button>
                    </div>
                    <?php else : ?>
                    <div class="col">
                        <button data-toggle="modal" data-target="#addcomp" class="btn btn-dark">Add Score</button>
                        <br><small>You have not added components score for <?= $student['name'] ?></small>
                    </div>
                    <?php endif ?>

                </div>
            </div>
        </div>

    </div>
    <div id="addcomp" class="modal fade card">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add Component Score</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $hidden = array(
                    'student_id' => $student_id,
                    'acadsession_id' => $academicsession['id'],
                    'acslug' => $academicsession['slug']
                ); ?>
                <?= form_open('score/add_scorecomp', '', $hidden) ?>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="">Digital CV</label>
                        <select name="digitalcv" class="custom-select" required>
                            <option value="" selected disabled>Select digital CV score</option>
                            <?php foreach ($guide['digitalcv'] as $scoreguide) : ?>
                            <option value="<?= $scoreguide['id'] ?>"><?= $scoreguide['concat'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Leadership</label>
                        <select name="leadership" class="custom-select" required>
                            <option value="" selected disabled>Select digital CV score</option>
                            <?php foreach ($guide['leadership'] as $scoreguide) : ?>
                            <option value="<?= $scoreguide['id'] ?>"><?= $scoreguide['concat'] ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Volunteer</label>
                        <input name="volunteer" type="number" min="0" max="5" class="form-control" required>
                    </div>
                    <small>Each component should have maximum 5 marks</small>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Submit</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Dismiss</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>

    <div id="editcomp" class="modal fade card">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Component Score</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <?php $hidden = array(
                    'student_id' => $student_id,
                    'acadsession_id' => $academicsession['id'],
                    'acslug' => $academicsession['slug']
                ); ?>
                <?= form_open('score/edit_scorecomp', '', $hidden) ?>
                <div class="modal-body">
                    <?php foreach ($scorecomps['scores'] as $key => $component) : ?>
                    <div class="form-group">
                        <label><?= ucfirst($key) ?></label>
                        <?php if ($key == 'volunteer') : ?>
                        <input name="keys[<?= $key ?>]" value="<?= $component ?>" type="number" min="0" max="5" class="form-control" required>
                        <?php else : ?>
                        <select name="keys[<?= $key ?>]" class="custom-select" required>
                            <option value="" selected disabled>Select <?= $key ?> score</option>
                            <?php foreach ($guide[$key] as $scoreguide) : ?>
                            <?php $selected = ($scoreguide['score'] == $component) ? 'selected' : ''; ?>
                            <option value="<?= $scoreguide['id'] ?>" <?= $selected ?>><?= $scoreguide['concat'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <?php endif ?>
                    </div>
                    <?php endforeach ?>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-dark">Update</button>
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Dismiss</button>
                </div>
                <?= form_close() ?>
            </div>
        </div>
    </div>
</div>