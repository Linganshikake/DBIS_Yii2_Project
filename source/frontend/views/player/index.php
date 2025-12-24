<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 邓晓川  2313547, 202512
 * This is the frontend player index view (前端球员列表视图)
 */
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'M-LEAGUERS';
$players = $dataProvider->getModels();
?>

<div class="player-index">

    <div class="row" style="margin-bottom: 30px; margin-top: 20px;">
        <div class="col-md-12">
            <h1 style="color: #d4af37; font-weight: 900; letter-spacing: 3px; border-bottom: 2px solid #333; padding-bottom: 15px;">
                M.LEAGUERS
            </h1>
        </div>
    </div>

    <div class="row">
        <?php foreach ($players as $player): ?>
            <div class="col-lg-3 col-md-4 col-sm-6" style="margin-bottom: 30px;">
                <a href="<?= Url::to(['player/view', 'id' => $player->id]) ?>" style="text-decoration: none;">
                    <div class="player-card" style="background: #1a1a1a; border: 1px solid #333; overflow: hidden; position: relative; transition: 0.3s;">
                        
                        <div style="height: 220px; background: #000; position: relative; overflow: hidden;">
                            
                            <div style="position: absolute; top: 0; right: 0; background: #d4af37; color: #000; font-size: 10px; padding: 2px 8px; font-weight: bold; z-index: 10;">
                                <?= Html::encode($player->team->name) ?>
                            </div>

                            <?php if ($player->avatar): ?>
                                <img src="/uploads/players/<?= $player->avatar ?>" 
                                     alt="<?= Html::encode($player->name) ?>" 
                                     style="width: 100%; height: 100%; object-fit: cover; object-position: top;">
                            <?php else: ?>
                                <div style="width: 100%; height: 100%; display: flex; align-items: flex-end; justify-content: center;">
                                    <div style="color: #333; font-size: 80px; opacity: 0.3; padding-bottom: 20px;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div style="padding: 15px; text-align: center;">
                            <h4 style="color: #fff; font-weight: bold; margin: 0;">
                                <?= Html::encode($player->name) ?>
                            </h4>
                            <div style="color: #888; font-size: 12px; margin-top: 5px;">
                                <?= Html::encode($player->register_name) ?>
                            </div>
                        </div>
                        
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="row">
        <div class="col-12 text-center">
            <?= LinkPager::widget([
                'pagination' => $dataProvider->pagination,
                'options' => ['class' => 'pagination justify-content-center'],
                'linkOptions' => ['class' => 'page-link', 'style' => 'background: #000; border-color: #333; color: #d4af37;'],
                'disabledPageCssClass' => 'disabled',
                'activePageCssClass' => 'active',
            ]) ?>
        </div>
    </div>

</div>