<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\LinkPager;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $sort string */

$this->title = '评论广场';
$isLoggedIn = !Yii::$app->user->isGuest;
$currentUserId = $isLoggedIn ? Yii::$app->user->id : null;
?>

<div class="comment-index">

    <!-- 标题区域 -->
    <div class="text-center" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); padding: 50px 20px; margin: -20px -15px 40px; border-radius: 0 0 20px 20px;">
        <h1 style="color: #d4af37; font-weight: 900; letter-spacing: 3px; font-size: 36px;">
            <i class="fa fa-comments-o"></i> 评论广场
        </h1>
        <p style="color: #888; margin-top: 15px;">分享你的观点，与其他麻将爱好者交流</p>
    </div>

    <!-- 发表评论区域 -->
    <?php if ($isLoggedIn): ?>
    <div class="post-comment mb-4" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 20px;">
        <div style="display: flex; gap: 15px;">
            <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; background: #333; flex-shrink: 0;">
                <?php $user = Yii::$app->user->identity; ?>
                <?php if ($user->avatar): ?>
                    <img src="/uploads/avatars/<?= $user->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php else: ?>
                    <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                        <i class="fa fa-user" style="color: #666;"></i>
                    </div>
                <?php endif; ?>
            </div>
            <div style="flex: 1;">
                <textarea id="comment-content" class="form-control" rows="3" 
                          style="background: #222; border: 1px solid #333; color: #fff; resize: none;"
                          placeholder="说点什么吧..."></textarea>
                <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 10px;">
                    <span id="char-count" style="color: #666; font-size: 12px;">0/500</span>
                    <button id="submit-comment" class="btn btn-warning" style="font-weight: bold;">
                        <i class="fa fa-paper-plane"></i> 发表评论
                    </button>
                </div>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="login-prompt mb-4 text-center" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 30px;">
        <p style="color: #888; margin-bottom: 15px;">登录后即可发表评论</p>
        <a href="<?= Url::to(['site/login']) ?>" class="btn btn-warning">
            <i class="fa fa-sign-in"></i> 立即登录
        </a>
        <a href="<?= Url::to(['site/signup']) ?>" class="btn btn-outline-warning ml-2">
            <i class="fa fa-user-plus"></i> 注册账号
        </a>
    </div>
    <?php endif; ?>

    <!-- 排序选择 -->
    <div class="sort-tabs mb-4" style="border-bottom: 1px solid #333; padding-bottom: 15px;">
        <a href="<?= Url::to(['comment/index', 'sort' => 'latest']) ?>" 
           class="sort-tab <?= $sort === 'latest' ? 'active' : '' ?>"
           style="color: <?= $sort === 'latest' ? '#d4af37' : '#888' ?>; text-decoration: none; margin-right: 30px; font-weight: bold; padding-bottom: 15px; border-bottom: 2px solid <?= $sort === 'latest' ? '#d4af37' : 'transparent' ?>;">
            <i class="fa fa-clock-o"></i> 最新
        </a>
        <a href="<?= Url::to(['comment/index', 'sort' => 'hot']) ?>" 
           class="sort-tab <?= $sort === 'hot' ? 'active' : '' ?>"
           style="color: <?= $sort === 'hot' ? '#d4af37' : '#888' ?>; text-decoration: none; font-weight: bold; padding-bottom: 15px; border-bottom: 2px solid <?= $sort === 'hot' ? '#d4af37' : 'transparent' ?>;">
            <i class="fa fa-fire"></i> 热门
        </a>
    </div>

    <!-- 评论列表 -->
    <div id="comment-list">
        <?php foreach ($dataProvider->getModels() as $comment): ?>
            <?php 
            $commentUser = $comment->user;
            $isLiked = false;
            if ($isLoggedIn) {
                $isLiked = \common\models\CommentLike::find()
                    ->where(['comment_id' => $comment->id, 'user_id' => $currentUserId])
                    ->exists();
            }
            ?>
        <div class="comment-item" id="comment-<?= $comment->id ?>" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 20px; margin-bottom: 15px;">
            <div style="display: flex; gap: 15px;">
                <!-- 用户头像 -->
                <div style="width: 50px; height: 50px; border-radius: 50%; overflow: hidden; background: #333; flex-shrink: 0;">
                    <?php if ($commentUser && $commentUser->avatar): ?>
                        <img src="/uploads/avatars/<?= $commentUser->avatar ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center;">
                            <i class="fa fa-user" style="color: #666;"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- 评论内容 -->
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <div>
                            <span style="color: #fff; font-weight: bold;">
                                <?= Html::encode($commentUser ? ($commentUser->nickname ?: $commentUser->username) : '未知用户') ?>
                            </span>
                            <span style="color: #666; font-size: 12px; margin-left: 10px;">
                                <?= date('Y-m-d H:i', $comment->created_at) ?>
                            </span>
                        </div>
                        
                        <!-- 删除按钮（仅自己可见） -->
                        <?php if ($isLoggedIn && $comment->user_id == $currentUserId): ?>
                        <button class="btn btn-sm btn-link delete-comment" data-id="<?= $comment->id ?>" style="color: #666;">
                            <i class="fa fa-trash"></i>
                        </button>
                        <?php endif; ?>
                    </div>
                    
                    <p style="color: #ddd; line-height: 1.8; margin-bottom: 15px;">
                        <?= nl2br(Html::encode($comment->content)) ?>
                    </p>
                    
                    <!-- 点赞按钮 -->
                    <div class="comment-actions">
                        <button class="btn btn-sm like-btn <?= $isLiked ? 'liked' : '' ?>" 
                                data-id="<?= $comment->id ?>"
                                style="color: <?= $isLiked ? '#e74c3c' : '#666' ?>; background: transparent; border: none;">
                            <i class="fa <?= $isLiked ? 'fa-heart' : 'fa-heart-o' ?>"></i>
                            <span class="like-count"><?= $comment->like_count ?></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
        
        <?php if (empty($dataProvider->getModels())): ?>
        <div class="text-center" style="padding: 80px 20px; color: #666;">
            <i class="fa fa-comments-o" style="font-size: 60px; margin-bottom: 20px; display: block;"></i>
            <p>还没有评论，来发表第一条吧！</p>
        </div>
        <?php endif; ?>
    </div>

    <!-- 分页 -->
    <div class="pagination-wrapper" style="margin-top: 30px;">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'pagination justify-content-center'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link', 'style' => 'background: #222; border-color: #333; color: #fff;'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link', 'style' => 'background: #333; border-color: #333; color: #666;'],
        ]) ?>
    </div>

</div>

<script>
$(document).ready(function() {
    // 字数统计
    $('#comment-content').on('input', function() {
        var len = $(this).val().length;
        $('#char-count').text(len + '/500');
        if (len > 500) {
            $('#char-count').css('color', '#e74c3c');
        } else {
            $('#char-count').css('color', '#666');
        }
    });
    
    // 发表评论
    $('#submit-comment').click(function() {
        var content = $('#comment-content').val().trim();
        if (!content) {
            alert('请输入评论内容');
            return;
        }
        if (content.length > 500) {
            alert('评论内容不能超过500字');
            return;
        }
        
        var btn = $(this);
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> 发送中...');
        
        $.post('<?= Url::to(['comment/create']) ?>', {
            content: content,
            '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
        }, function(data) {
            btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> 发表评论');
            
            if (data.success) {
                $('#comment-content').val('');
                $('#char-count').text('0/500');
                // 刷新页面显示新评论
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });
    
    // 点赞
    $(document).on('click', '.like-btn', function() {
        <?php if (!$isLoggedIn): ?>
        alert('请先登录');
        return;
        <?php endif; ?>
        
        var btn = $(this);
        var commentId = btn.data('id');
        
        $.post('<?= Url::to(['comment/like']) ?>', {
            comment_id: commentId,
            '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
        }, function(data) {
            if (data.success) {
                btn.find('.like-count').text(data.like_count);
                if (data.action === 'liked') {
                    btn.addClass('liked').css('color', '#e74c3c');
                    btn.find('i').removeClass('fa-heart-o').addClass('fa-heart');
                } else {
                    btn.removeClass('liked').css('color', '#666');
                    btn.find('i').removeClass('fa-heart').addClass('fa-heart-o');
                }
            } else {
                alert(data.message);
            }
        });
    });
    
    // 删除评论
    $(document).on('click', '.delete-comment', function() {
        if (!confirm('确定要删除这条评论吗？')) return;
        
        var commentId = $(this).data('id');
        
        $.post('<?= Url::to(['comment/delete']) ?>', {
            comment_id: commentId,
            '<?= Yii::$app->request->csrfParam ?>': '<?= Yii::$app->request->csrfToken ?>'
        }, function(data) {
            if (data.success) {
                $('#comment-' + commentId).fadeOut(function() {
                    $(this).remove();
                });
            } else {
                alert(data.message);
            }
        });
    });
});
</script>

<style>
.like-btn:hover {
    color: #e74c3c !important;
}
.like-btn.liked:hover {
    color: #666 !important;
}
.comment-item:hover {
    border-color: #444;
}
</style>
