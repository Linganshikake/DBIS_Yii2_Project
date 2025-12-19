<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model common\models\Team */

$this->title = $model->name;
?>

<div class="team-view">

    <div class="team-header" style="background: #1a1a1a; padding: 40px; border-bottom: 4px solid #d4af37; margin-bottom: 40px; position: relative;">
        <div class="row align-items-center">
            
            <div class="col-md-3 text-center">
                <div style="width: 150px; height: 150px; background: #fff; border: 4px solid #d4af37; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; overflow: hidden; box-shadow: 0 0 15px rgba(212, 175, 55, 0.3);">
                    <?php if ($model->logo): ?>
                        <img src="/uploads/teams/<?= $model->logo ?>" alt="Logo" style="width: 100%; height: 100%; object-fit: contain; padding: 15px;">
                    <?php else: ?>
                        <span style="font-size: 30px; font-weight: 900; color: #333;">LOGO</span>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="col-md-9">
                <h4 style="color: #d4af37; letter-spacing: 2px; text-transform: uppercase; margin-bottom: 5px;">
                    OFFICIAL TEAM
                </h4>
                <h1 style="color: #fff; font-size: 48px; font-weight: 900; margin-top: 0; line-height: 1;">
                    <?= Html::encode($model->name) ?>
                </h1>
                <p style="color: #888; font-size: 18px; margin-top: 10px;">
                    <i class="fas fa-building"></i> 所属企业：<?= Html::encode($model->company) ?>
                </p>
                <div style="margin-top: 20px; color: #ccc; font-size: 14px; max-width: 800px;">
                    <?= nl2br(Html::encode($model->description)) ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2 style="border-left: 5px solid #d4af37; padding-left: 15px; color: #fff;">
                    TEAM MEMBERS
                </h2>
            </div>
        </div>

        <div class="row">
            <?php foreach ($model->players as $player): ?>
                <?php if ($player->display_status == 1): // 只显示有效的选手 ?>
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="player-card" style="background: #111; border: 1px solid #333; text-align: center; transition: 0.3s;">
                        
                        <div style="height: 200px; background: #222; position: relative; overflow: hidden;">
                            <?php if ($player->avatar): ?>
                                <img src="/uploads/players/<?= $player->avatar ?>" style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                            <?php else: ?>
                                <div style="height: 100%; display: flex; align-items: center; justify-content: center; color: #333;">
                                    <span style="font-size: 50px;">NO IMG</span>
                                </div>
                            <?php endif; ?>

                            <div style="position: absolute; bottom: 0; width: 100%; padding: 10px; background: linear-gradient(to top, #000, transparent);">
                                <h3 style="margin: 0; color: #fff; font-size: 20px; font-weight: bold;">
                                    <?= Html::encode($player->name) ?>
                                </h3>
                                <div style="color: #d4af37; font-size: 12px; text-transform: uppercase;">
                                    <?= Html::encode($player->register_name) ?>
                                </div>
                            </div>
                        </div>

                        <div style="padding: 15px;">
                            <div class="row text-center">
                                <div class="col-6" style="border-right: 1px solid #333;">
                                    <div style="color: #888; font-size: 10px;">GENDER</div>
                                    <div style="color: #fff;"><?= $player->gender ?></div>
                                </div>
                                <div class="col-6">
                                    <div style="color: #888; font-size: 10px;">JOINED</div>
                                    <div style="color: #fff;"><?= date('Y', strtotime($player->join_date)) ?></div>
                                </div>
                            </div>
                            
                            <div style="margin-top: 15px;">
                                <a href="<?= Url::to(['player/view', 'id' => $player->id]) ?>" class="btn btn-sm btn-block" style="background: #333; color: #fff; width: 100%;">
                                    VIEW PROFILE
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <?php if (empty($model->players)): ?>
                <div class="col-12 text-center" style="padding: 50px; color: #666;">
                    该队伍暂无选手数据
                </div>
            <?php endif; ?>
        </div>

        <div class="row mt-5">
            <div class="col-12 text-center">
                <?= Html::a('← BACK TO TEAM LIST', ['index'], ['class' => 'btn btn-outline-secondary', 'style' => 'color: #888; border-color: #444;']) ?>
            </div>
        </div>
    </div>

</div>