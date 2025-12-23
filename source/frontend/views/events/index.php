<?php
/* @var $this yii\web\View */
/* @var $events array */

$this->title = '抗战80周年纪念';
?>

<style>
body {
    margin: 0;
    font-family: "Microsoft YaHei", Arial, sans-serif;
    background: #f4f4f4;
}

/* 顶部 Banner */
.banner {
    background: url("/uploads/banner_war.jpg") center center / cover no-repeat;
    height: 320px;
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
}
.banner h1 {
    font-size: 48px;
    letter-spacing: 4px;
    background: rgba(0,0,0,0.5);
    padding: 20px 40px;
}

/* 主体 */
.container {
    width: 1100px;
    margin: 40px auto;
}

/* 标题 */
.section-title {
    font-size: 28px;
    border-left: 6px solid #c00;
    padding-left: 15px;
    margin-bottom: 30px;
}

/* 时间轴 */
.timeline {
    position: relative;
    padding-left: 40px;
}
.timeline::before {
    content: "";
    position: absolute;
    left: 10px;
    top: 0;
    bottom: 0;
    width: 4px;
    background: #ccc;
}

/* 单个事件 */
.event {
    position: relative;
    background: #fff;
    padding: 20px 25px;
    margin-bottom: 30px;
    border-radius: 4px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
}
.event::before {
    content: "";
    position: absolute;
    left: -34px;
    top: 25px;
    width: 14px;
    height: 14px;
    background: #c00;
    border-radius: 50%;
}

.event-date {
    color: #999;
    font-size: 14px;
}

.event-title {
    font-size: 22px;
    color: #333;
    margin: 8px 0;
}

.event-desc {
    color: #555;
    line-height: 1.8;
}

/* 底部 */
.footer {
    background: #222;
    color: #aaa;
    text-align: center;
    padding: 20px;
    margin-top: 60px;
}
</style>

<!-- Banner -->
<div class="banner">
    <h1>纪念中国人民抗日战争胜利 80 周年</h1>
</div>

<!-- 内容 -->
<div class="container">
    <div class="section-title">重大历史事件回顾</div>

    <div class="timeline">

        <div class="event">
            <div class="event-date">1931-09-18</div>
            <div class="event-title">九一八事变</div>
            <div class="event-desc">
                日本关东军制造九一八事变，悍然侵占中国东北，中华民族的局部抗战由此开始。
            </div>
        </div>

        <div class="event">
            <div class="event-date">1937-07-07</div>
            <div class="event-title">卢沟桥事变</div>
            <div class="event-desc">
                卢沟桥事变爆发，标志着中国人民抗日战争全面爆发，全国性抗战正式开始。
            </div>
        </div>

        <div class="event">
            <div class="event-date">1937-12</div>
            <div class="event-title">南京大屠杀</div>
            <div class="event-desc">
                日军攻占南京后制造惨绝人寰的大屠杀，30万同胞惨遭杀害，成为中华民族永远的痛。
            </div>
        </div>

        <div class="event">
            <div class="event-date">1940</div>
            <div class="event-title">百团大战</div>
            <div class="event-desc">
                八路军发动百团大战，沉重打击了日军的嚣张气焰，振奋了全国抗战信心。
            </div>
        </div>

        <div class="event">
            <div class="event-date">1945-09-03</div>
            <div class="event-title">抗日战争胜利</div>
            <div class="event-desc">
                中国人民抗日战争取得伟大胜利，为世界反法西斯战争作出了重大贡献。
            </div>
        </div>

    </div>
</div>

<div class="footer">
    © 抗战胜利80周年主题网站 ｜ 历史铭记 · 民族复兴
</div>