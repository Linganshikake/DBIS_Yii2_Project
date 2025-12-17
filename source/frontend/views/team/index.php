<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'TEAMS';
$teams = $dataProvider->getModels();
?>

<div class="team-index">

    <div class="row" style="margin-bottom: 40px; margin-top: 20px;">
        <div class="col-md-12">
            <h1 style="color: #d4af37; font-weight: 900; letter-spacing: 3px; border-bottom: 2px solid #333; padding-bottom: 15px;">
                M.LEAGUE TEAMS
            </h1>
            <p style="color: #888;">M联赛参赛队伍一览</p>
        </div>
    </div>

    <div class="row">
        <?php foreach ($teams as $team): ?>
            <div class="col-lg-4 col-md-6 col-sm-12" style="margin-bottom: 30px;">
                <a href="<?= Url::to(['team/view', 'id' => $team->id]) ?>" class="team-card-link" style="display: block; text-decoration: none;">
                    <div class="team-card" style="height: 100%; padding: 0; overflow: hidden; position: relative;">
                        <div style="height: 150px; background: #000; display: flex; align-items: center; justify-content: center; border-bottom: 1px solid #333;">
                            <div style="text-align: center;">
                                <span style="font-size: 40px; font-weight: 900; color: #333; letter-spacing: -2px;">M.L</span>
                                <br>
                                <span style="font-size: 12px; color: #555;">OFFICIAL TEAM</span>
                            </div>
                        </div>

                        <div style="padding: 20px;">
                            <h3 style="color: #fff; font-weight: bold; margin-top: 0; font-size: 22px; text-transform: uppercase;">
                                <?= Html::encode($team->name) ?>
                            </h3>
                            <p style="color: #d4af37; font-size: 13px; font-weight: bold; letter-spacing: 1px; margin-bottom: 15px;">
                                <i class="fas fa-building"></i> <?= Html::encode($team->company) ?>
                            </p>
                            <p style="color: #aaa; font-size: 14px; line-height: 1.6; height: 45px; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                                <?= Html::encode($team->description ?: '暂无队伍简介...') ?>
                            </p>
                            <div style="margin-top: 20px; text-align: right;">
                                <span class="btn btn-outline-warning btn-sm" style="border-radius: 20px; padding: 5px 20px; font-size: 12px;">
                                    TEAM INFO <i class="fa fa-arrow-right" style="margin-left: 5px;"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

</div>