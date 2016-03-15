<?php

namespace k1app;

use \k1lib\templates\temply as temply;
use k1lib\session\session_db as session;
?>
<div class="row">
    <?php if (session::check_user_level(['god', 'admin'])) : ?>
        <div class="k1-data-group">
            <h5 class="k1-data-group-title">Access control</h5>
            <div class="row">
                <div class="large-4 medium-6 small-12 end column k1-data-item">
                    <div class="k1-data-item-label"><?php temply::set_template_place("user_level-label") ?></div>
                    <div class="k1-data-item-value"><?php temply::set_template_place("user_level") ?></div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    <div class="k1-data-group">
        <h5 class="k1-data-group-title">Personal data</h5>
        <div class="row">
            <div class="large-4 medium-6 small-12 column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_names-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_names") ?></div>
            </div>
            <div class="large-4 medium-6 small-12 column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_last_names-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_last_names") ?></div>
            </div>
            <div class="large-4 medium-6 small-12 end column k1-data-item">
                <div class="k1-data-item-label"><?php temply::set_template_place("user_avatar-label") ?></div>
                <div class="k1-data-item-value"><?php temply::set_template_place("user_avatar") ?></div>
            </div>
        </div>
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
