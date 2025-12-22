<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */

$this->title = 'M.LEAGUE 后台管理系统';
?>
<div class="site-index">

    <div class="jumbotron" style="background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); color: #fff; border: 2px solid #d4af37; border-radius: 10px;">
        <h1 style="color: #d4af37;">M.LEAGUE 数据管理后台</h1>
        <p class="lead">欢迎使用 M.LEAGUE 数据管理系统</p>
    </div>

    <div class="body-content">
        <h3 style="border-left: 4px solid #d4af37; padding-left: 10px; margin-bottom: 30px;">快捷导航</h3>
        
        <div class="row">
            <!-- 队伍管理 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-primary" style="border-color: #337ab7;">
                    <div class="panel-heading" style="background: #337ab7; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-flag"></i> 队伍管理</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理所有参赛队伍信息</p>
                        <a href="<?= Url::to(['team/index']) ?>" class="btn btn-primary btn-block">进入管理</a>
                    </div>
                </div>
            </div>
            
            <!-- 选手管理 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-success" style="border-color: #5cb85c;">
                    <div class="panel-heading" style="background: #5cb85c; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-user"></i> 选手管理</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理选手信息、头像、视频</p>
                        <a href="<?= Url::to(['player/index']) ?>" class="btn btn-success btn-block">进入管理</a>
                    </div>
                </div>
            </div>
            
            <!-- 赛季管理 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-info" style="border-color: #5bc0de;">
                    <div class="panel-heading" style="background: #5bc0de; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-calendar"></i> 赛季管理</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理赛季信息及当前赛季</p>
                        <a href="<?= Url::to(['season/index']) ?>" class="btn btn-info btn-block">进入管理</a>
                    </div>
                </div>
            </div>
            
            <!-- 选手成绩 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-warning" style="border-color: #f0ad4e;">
                    <div class="panel-heading" style="background: #f0ad4e; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-stats"></i> 选手成绩</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理选手赛季统计数据</p>
                        <a href="<?= Url::to(['player-season-stat/index']) ?>" class="btn btn-warning btn-block">进入管理</a>
                    </div>
                </div>
            </div>
            
            <!-- 团体管理 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-default" style="border-color: #777;">
                    <div class="panel-heading" style="background: #777; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-tower"></i> 团体管理</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理职业麻将团体信息</p>
                        <a href="<?= Url::to(['organization/index']) ?>" class="btn btn-default btn-block">进入管理</a>
                    </div>
                </div>
            </div>
            
            <!-- 新闻管理 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-danger" style="border-color: #d9534f;">
                    <div class="panel-heading" style="background: #d9534f; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-list-alt"></i> 新闻管理</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理联赛新闻资讯</p>
                        <a href="<?= Url::to(['news/index']) ?>" class="btn btn-danger btn-block">进入管理</a>
                    </div>
                </div>
            </div>
            
            <!-- 赛程管理 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-primary" style="border-color: #337ab7;">
                    <div class="panel-heading" style="background: #337ab7; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-time"></i> 赛程管理</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理比赛日程安排</p>
                        <a href="<?= Url::to(['schedule/index']) ?>" class="btn btn-primary btn-block">进入管理</a>
                    </div>
                </div>
            </div>
            
            <!-- 赛程成绩 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-success" style="border-color: #5cb85c;">
                    <div class="panel-heading" style="background: #5cb85c; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-list"></i> 赛程成绩</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理比赛成绩记录</p>
                        <a href="<?= Url::to(['schedule-score/index']) ?>" class="btn btn-success btn-block">进入管理</a>
                    </div>
                </div>
            </div>
            
            <!-- 企业信息 -->
            <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                <div class="panel panel-info" style="border-color: #5bc0de;">
                    <div class="panel-heading" style="background: #5bc0de; color: #fff; padding: 15px;">
                        <h4 style="margin: 0;"><i class="glyphicon glyphicon-briefcase"></i> 企业信息</h4>
                    </div>
                    <div class="panel-body">
                        <p>管理赞助企业信息</p>
                        <a href="<?= Url::to(['company/index']) ?>" class="btn btn-info btn-block">进入管理</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
