<?php

namespace k1app;

use \k1lib\templates\temply as temply;
?>
<div class="row">
    <?php temply::set_template_place("user_avatar") ?>
    <h4><?php temply::set_template_place("user_names") ?> <?php temply::set_template_place("user_last_names") ?> <small>(<?php temply::set_template_place("user_login") ?> has level <?php temply::set_template_place("user_level") ?>)</small></h4>
    <div class="k1-data-group">
        <h5 class="k1-data-group-title">Personal data</h5>
        <div class="row">
            <div class="large-4 medium-6 small-12 column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_legal_id-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_legal_id_type") ?> <?php temply::set_template_place("user_legal_id") ?></div>
            </div>
            <div class="large-4 medium-6 small-12 column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_birthday-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_birthday") ?></div>
            </div>
            <div class="large-4 medium-6 small-12 column end k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_sex-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_sex") ?></div>
            </div>
        </div>
    </div>
    <div class="k1-data-group">
        <h5 class="k1-data-group-title">Agency data</h5>
        <div class="row">
            <div class="large-4 medium-6 small-12 column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("location_id-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("location_id") ?></div>
            </div>
            <div class="large-4 medium-6 small-12 column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("dep_id-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("dep_id") ?></div>
            </div>
            <div class="large-4 medium-6 small-12 column end k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("job_title_id-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("job_title_id") ?></div>
            </div>
        </div>
    </div>
    <div class="k1-data-group">
        <h5 class="k1-data-group-title">Contact info</h5>
        <div class="row">
            <div class="large-4 medium-6 small-12 column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_email-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_email") ?></div>
            </div>
            <div class="large-4 medium-6 small-12 column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_phone_work-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_phone_work") ?></div>
            </div>
            <div class="large-4 medium-6 small-12 column end k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_phone_personal-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_phone_personal") ?></div>
            </div>
        </div>
        <div class="row">
            <div class="large-4 medium-6 small-12 column end k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_address-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_address") ?></div>
            </div>
        </div>
    </div>
</div>
