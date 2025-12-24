<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend comment index view (前端评论列表视图)
 */

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
    <div class="post-comment" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 20px; margin-bottom: 30px;">
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
    <div class="login-prompt text-center" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 30px; margin-bottom: 30px;">
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
    <div class="sort-tabs" style="border-bottom: 1px solid #333; padding-bottom: 15px; margin-bottom: 25px;">
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
        <?php if ($sort === 'hot'): ?>
        <span style="color: #666; font-size: 12px; margin-left: 20px;">
            <i class="fa fa-info-circle"></i> 仅展示点赞数最多的前3条评论
        </span>
        <?php endif; ?>
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
        <div class="comment-item" id="comment-<?= $comment->id ?>" style="background: #1a1a1a; border: 1px solid #333; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
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
                                <?= date('Y-m-d H:i', strtotime($comment->created_at)) ?>
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
                    
                    <!-- 操作按钮：点赞 + 回复 -->
                    <div class="comment-actions" style="display: flex; gap: 25px; align-items: center;">
                        <button class="like-btn <?= $isLiked ? 'liked' : '' ?>" 
                                data-id="<?= $comment->id ?>"
                                style="background: transparent; border: none; padding: 0; display: flex; align-items: center; gap: 6px; font-size: 14px; cursor: pointer; outline: none; box-shadow: none;">
                            <svg class="like-icon" width="20" height="20" viewBox="0 0 768 768" fill="<?= $isLiked ? '#f8615a' : '#888' ?>">
                                <path d="M96 480h96V224H96v256zm672-224c0-26.4-21.6-48-48-48H496l36-129.6c2.4-9.6 0-19.2-4.8-28.8l-24-24L307.2 224c-9.6 9.6-19.2 28.8-19.2 48v256c0 52.8 43.2 96 96 96h192c38.4 0 67.2-19.2 81.6-48l76.8-177.6c2.4-9.6 4.8-19.2 4.8-28.8V256h-4.8l4.8-4.8z"/>
                            </svg>
                            <span class="like-count" style="color: <?= $isLiked ? '#f8615a' : '#888' ?>;"><?= $comment->like_count ?></span>
                        </button>
                        <button class="btn btn-sm reply-btn" 
                                data-id="<?= $comment->id ?>"
                                data-username="<?= Html::encode($commentUser ? ($commentUser->nickname ?: $commentUser->username) : '未知用户') ?>"
                                style="color: #888; background: transparent; border: none; padding: 0; display: flex; align-items: center; gap: 5px; font-size: 14px;">
                            <i class="fa fa-comment-o"></i>
                            <span>回复</span>
                        </button>
                    </div>
                    
                    <!-- 回复输入框（默认隐藏） -->
                    <div class="reply-box" id="reply-box-<?= $comment->id ?>" style="display: none; margin-top: 15px; padding: 15px; background: #222; border-radius: 8px;">
                        <textarea class="form-control reply-content" rows="2" 
                                  style="background: #333; border: 1px solid #444; color: #fff; resize: none; font-size: 14px;"
                                  placeholder="回复 <?= Html::encode($commentUser ? ($commentUser->nickname ?: $commentUser->username) : '未知用户') ?>..."></textarea>
                        <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 10px;">
                            <button class="btn btn-sm cancel-reply" style="background: #444; color: #aaa; border: none;">取消</button>
                            <button class="btn btn-sm submit-reply" data-id="<?= $comment->id ?>" style="background: #d4af37; color: #000; border: none; font-weight: bold;">发送</button>
                        </div>
                    </div>
                    
                    <!-- 回复列表 -->
                    <?php 
                    $replies = \common\models\CommentReply::find()
                        ->where(['comment_id' => $comment->id, 'display_status' => 1])
                        ->orderBy(['created_at' => SORT_ASC])
                        ->all();
                    ?>
                    <?php if (!empty($replies)): ?>
                    <div class="reply-list" style="margin-top: 15px; padding-left: 10px; border-left: 2px solid #333;">
                        <?php foreach ($replies as $reply): ?>
                        <?php $replyUser = $reply->user; ?>
                        <div class="reply-item" id="reply-<?= $reply->id ?>" style="padding: 10px 0; border-bottom: 1px solid #2a2a2a;">
                            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                                <div style="flex: 1;">
                                    <span style="color: #d4af37; font-weight: bold; font-size: 13px;">
                                        <?= Html::encode($replyUser ? ($replyUser->nickname ?: $replyUser->username) : '未知用户') ?>
                                    </span>
                                    <span style="color: #666; font-size: 11px; margin-left: 8px;">
                                        <?= date('Y-m-d H:i', strtotime($reply->created_at)) ?>
                                    </span>
                                    <p style="color: #ccc; font-size: 13px; margin: 5px 0 0 0; line-height: 1.6;">
                                        <?= nl2br(Html::encode($reply->content)) ?>
                                    </p>
                                </div>
                                <?php if ($isLoggedIn && $reply->user_id == $currentUserId): ?>
                                <button class="btn btn-sm btn-link delete-reply" data-id="<?= $reply->id ?>" style="color: #666; padding: 0;">
                                    <i class="fa fa-times" style="font-size: 12px;"></i>
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
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

    <!-- 分页（热门模式不显示分页） -->
    <?php if ($dataProvider->pagination !== false): ?>
    <div class="pagination-wrapper" style="margin-top: 30px;">
        <?= LinkPager::widget([
            'pagination' => $dataProvider->pagination,
            'options' => ['class' => 'pagination justify-content-center'],
            'linkContainerOptions' => ['class' => 'page-item'],
            'linkOptions' => ['class' => 'page-link', 'style' => 'background: #222; border-color: #333; color: #fff;'],
            'disabledListItemSubTagOptions' => ['class' => 'page-link', 'style' => 'background: #333; border-color: #333; color: #666;'],
        ]) ?>
    </div>
    <?php endif; ?>

</div>

<?php
$js = <<<JS
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
        
        $.ajax({
            url: '/index.php?r=comment/create',
            type: 'POST',
            data: {
                content: content,
                _csrf: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> 发表评论');
                
                if (data.success) {
                    $('#comment-content').val('');
                    $('#char-count').text('0/500');
                    location.reload();
                } else {
                    alert(data.message);
                }
            },
            error: function(xhr, status, error) {
                btn.prop('disabled', false).html('<i class="fa fa-paper-plane"></i> 发表评论');
                alert('请求失败，请检查是否已登录');
            }
        });
    });
    
    // 点赞
    $(document).on('click', '.like-btn', function() {
        var btn = $(this);
        var commentId = btn.data('id');
        
        $.ajax({
            url: '/index.php?r=comment/like',
            type: 'POST',
            data: {
                comment_id: commentId,
                _csrf: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    btn.find('.like-count').text(data.like_count);
                    if (data.action === 'liked') {
                        btn.addClass('liked');
                        btn.find('.like-icon').attr('fill', '#f8615a');
                        btn.find('.like-count').css('color', '#f8615a');
                    } else {
                        btn.removeClass('liked');
                        btn.find('.like-icon').attr('fill', '#888');
                        btn.find('.like-count').css('color', '#888');
                    }
                } else {
                    alert(data.message);
                }
            }
        });
    });
    
    // 删除评论
    $(document).on('click', '.delete-comment', function() {
        if (!confirm('确定要删除这条评论吗？')) return;
        
        var commentId = $(this).data('id');
        
        $.ajax({
            url: '/index.php?r=comment/delete',
            type: 'POST',
            data: {
                comment_id: commentId,
                _csrf: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $('#comment-' + commentId).fadeOut(function() {
                        $(this).remove();
                    });
                } else {
                    alert(data.message);
                }
            }
        });
    });
    
    // 点击回复按钮显示回复框
    $(document).on('click', '.reply-btn', function() {
        var commentId = $(this).data('id');
        var replyBox = $('#reply-box-' + commentId);
        
        // 隐藏其他回复框
        $('.reply-box').not(replyBox).slideUp();
        
        // 切换当前回复框
        replyBox.slideToggle();
        replyBox.find('textarea').focus();
    });
    
    // 取消回复
    $(document).on('click', '.cancel-reply', function() {
        $(this).closest('.reply-box').slideUp();
    });
    
    // 提交回复
    $(document).on('click', '.submit-reply', function() {
        var btn = $(this);
        var commentId = btn.data('id');
        var replyBox = $('#reply-box-' + commentId);
        var content = replyBox.find('.reply-content').val().trim();
        
        if (!content) {
            alert('请输入回复内容');
            return;
        }
        
        btn.prop('disabled', true).text('发送中...');
        
        $.ajax({
            url: '/index.php?r=comment/reply',
            type: 'POST',
            data: {
                comment_id: commentId,
                content: content,
                _csrf: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                btn.prop('disabled', false).text('发送');
                
                if (data.success) {
                    replyBox.find('.reply-content').val('');
                    replyBox.slideUp();
                    location.reload();
                } else {
                    alert(data.message);
                }
            },
            error: function() {
                btn.prop('disabled', false).text('发送');
                alert('请求失败，请检查是否已登录');
            }
        });
    });
    
    // 删除回复
    $(document).on('click', '.delete-reply', function() {
        if (!confirm('确定要删除这条回复吗？')) return;
        
        var replyId = $(this).data('id');
        
        $.ajax({
            url: '/index.php?r=comment/delete-reply',
            type: 'POST',
            data: {
                reply_id: replyId,
                _csrf: $('meta[name="csrf-token"]').attr('content')
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    $('#reply-' + replyId).fadeOut(function() {
                        $(this).remove();
                    });
                } else {
                    alert(data.message);
                }
            }
        });
    });
JS;

$this->registerJs($js);
?>

<style>
.like-btn:hover .like-icon {
    fill: #f8615a !important;
}
.like-btn:hover .like-count {
    color: #f8615a !important;
}
.reply-btn:hover {
    color: #d4af37 !important;
}
.comment-item:hover {
    border-color: #444;
}
.reply-item:last-child {
    border-bottom: none;
}
.like-btn {
    transition: all 0.2s ease;
}
.like-icon {
    transition: fill 0.2s ease;
}
</style>
