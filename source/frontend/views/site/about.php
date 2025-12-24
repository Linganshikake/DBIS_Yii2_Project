<?php
/**
 * Team: DBIS_Yii2_Project
 * Coding by: 尹浩燃  2313547, 202512
 * This is the frontend about view (前端关于页面视图)
 */
/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'M League是什么？';
// 不显示面包屑导航
?>

<style>
.about-page { background-color: #111; min-height: 100vh; margin: -20px -15px; padding: 20px 15px; }
.about-hero { background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); border: 1px solid #333; border-radius: 10px; padding: 60px 40px; margin-bottom: 40px; position: relative; overflow: hidden; }
.about-hero::before { content: ''; position: absolute; top: 0; left: 0; width: 5px; height: 100%; background: linear-gradient(to bottom, #d4af37, #ffd700); }
.about-hero .logo-section { display: flex; align-items: center; justify-content: center; gap: 50px; max-width: 1000px; margin: 0 auto; flex-wrap: wrap; }
.about-hero .mleague-logo { width: 180px; height: 180px; background: #fff; border-radius: 50%; padding: 0; box-shadow: 0 4px 20px rgba(212,175,55,0.4); object-fit: contain; }
.about-hero .hero-text { text-align: left; max-width: 600px; }
.about-hero .hero-title { font-size: 32px; font-weight: 900; color: #d4af37; margin-bottom: 25px; line-height: 1.4; letter-spacing: 2px; }
.about-hero .hero-desc { font-size: 17px; color: #ddd; line-height: 2; margin-bottom: 25px; text-align: justify; }
.about-hero .detail-link { color: #d4af37; text-decoration: none; font-size: 16px; border-bottom: 1px solid #d4af37; padding-bottom: 2px; }
.about-hero .detail-link:hover { color: #ffd700; border-color: #ffd700; }
.club-owners { background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); border: 1px solid #333; border-radius: 10px; padding: 50px 40px; text-align: center; margin-bottom: 40px; }
.section-title { font-size: 32px; font-weight: 900; color: #d4af37; margin-bottom: 12px; letter-spacing: 3px; }
.section-subtitle { font-size: 16px; color: #888; margin-bottom: 40px; }
.owners-logos { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 25px; max-width: 900px; margin: 0 auto; }
.owners-logos .logo-item { background: #fff; border-radius: 8px; padding: 15px 20px; height: 70px; display: flex; align-items: center; justify-content: center; transition: transform 0.3s, box-shadow 0.3s; }
.owners-logos .logo-item:hover { transform: translateY(-3px); box-shadow: 0 5px 20px rgba(212,175,55,0.3); }
.owners-logos .logo-item img { height: 35px; width: auto; max-width: 110px; object-fit: contain; }
.mission-section { background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); border: 1px solid #333; border-radius: 10px; padding: 60px 40px; margin-bottom: 40px; }
.mission-container { max-width: 900px; margin: 0 auto; }
.mission-header { text-align: center; margin-bottom: 40px; }
.mission-header .logo { width: 140px; height: 140px; margin-bottom: 20px; background: #fff; border-radius: 50%; padding: 0; box-shadow: 0 4px 20px rgba(212,175,55,0.4); object-fit: contain; }
.mission-header h2 { font-size: 36px; font-weight: 900; color: #d4af37; margin-bottom: 12px; letter-spacing: 5px; }
.mission-header .subtitle { font-size: 16px; color: #888; }
.mission-desc { font-size: 18px; color: #ddd; line-height: 2; margin-bottom: 50px; text-align: justify; }
.philosophy-section { border-top: 2px solid #d4af37; padding-top: 30px; margin-bottom: 50px; }
.philosophy-title { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
.philosophy-title h3 { font-size: 22px; font-weight: bold; color: #fff; margin: 0; }
.philosophy-title .dot { width: 12px; height: 12px; background: #e74c3c; border-radius: 50%; }
.philosophy-list { list-style: none; padding: 0; margin: 0; }
.philosophy-list li { position: relative; padding-left: 20px; margin-bottom: 18px; font-size: 16px; color: #ccc; line-height: 1.9; }
.philosophy-list li:before { content: ""; position: absolute; left: 0; color: #d4af37; }
.about-footer { background: linear-gradient(135deg, #1a1a1a 0%, #2a2a2a 100%); border: 1px solid #333; border-radius: 10px; padding: 40px; }
.footer-content { max-width: 800px; margin: 0 auto; }
.footer-org { font-size: 18px; color: #d4af37; margin-bottom: 25px; font-weight: bold; }
.footer-social { display: flex; gap: 25px; align-items: center; }
.footer-social a { display: inline-flex; align-items: center; justify-content: center; text-decoration: none; transition: transform 0.3s; }
.footer-social a:hover { transform: scale(1.1); }
.footer-social .social-x { width: 45px; height: 45px; }
.footer-social .social-youtube { width: 55px; height: 45px; }
.footer-social .social-line { width: 45px; height: 45px; }
@media (max-width: 768px) { .about-hero .logo-section { flex-direction: column; gap: 30px; } .about-hero .hero-text { text-align: center; } .about-hero .hero-title { font-size: 26px; } .about-hero, .club-owners, .mission-section, .about-footer { padding: 30px 20px; } }
</style>

<div class="about-page">
    <div class="about-hero">
        <div class="logo-section">
            <img src="/uploads/mleague_logo_circle.png" alt="M.LEAGUE" class="mleague-logo">
            <div class="hero-text">
                <h1 class="hero-title">如今，最好的个人运动<br>变成了最好的团队运动。</h1>
                <p class="hero-desc">职业麻将联赛M联赛正式开赛。这项全国性联赛的诞生，只允许少数顶尖职业选手从众多麻将选手中脱颖而出，参与其中。技术与智慧的碰撞，打造了高度专业的联赛环境。M联赛的选手们与各大公司签订了职业合同，身着统一队服，在智慧的较量中为各自队伍的荣誉而战。让我们共同开启麻将的新时代。</p>
                <a href="#mission" class="detail-link">M联赛理念的细节 </a>
            </div>
        </div>
    </div>

    <div class="club-owners">
        <h2 class="section-title">Club Owner</h2>
        <p class="section-subtitle">M联赛俱乐部所有者公司</p>
        <div class="owners-logos">
            <div class="logo-item"><img src="/uploads/company/JETS.png" alt="EARTH JETS"></div>
            <div class="logo-item"><img src="/uploads/company/SAKURA.png" alt="KADOKAWA"></div>
            <div class="logo-item"><img src="/uploads/company/KONAMI.png" alt="KONAMI"></div>
            <div class="logo-item"><img src="/uploads/company/ABEMAS.png" alt="CyberAgent"></div>
            <div class="logo-item"><img src="/uploads/company/PHOENIX.png" alt="SEGA SAMMY"></div>
            <div class="logo-item"><img src="/uploads/company/EX.png" alt="tv asahi"></div>
            <div class="logo-item"><img src="/uploads/company/DRIVENS.png" alt="dentsu"></div>
            <div class="logo-item"><img src="/uploads/company/RAIDEN.png" alt="HAKUHODO"></div>
            <div class="logo-item"><img src="/uploads/company/BEAST.png" alt="BS10"></div>
            <div class="logo-item"><img src="/uploads/company/U-NEXT.png" alt="U-NEXT"></div>
        </div>
    </div>

    <div class="mission-section" id="mission">
        <div class="mission-container">
            <div class="mission-header">
                <img src="/uploads/mleague_logo_circle.png" alt="M.LEAGUE" class="logo">
                <h2>使命</h2>
                <p class="subtitle">M联赛的使命</p>
            </div>
            <p class="mission-desc">麻将是一项深受喜爱的消遣活动，深深植根于日常生活当中。同时，它也是一项精彩的智力运动，需要极高的专注力、判断力、逻辑思维能力，甚至还需要强烈的胜利感。M League 将通过首个全国性职业队联赛，把麻将的乐趣和魅力传播到世界各地，让顶尖职业选手们在此展开激烈的冠军争夺。</p>
            <div class="philosophy-section">
                <div class="philosophy-title"><h3>M联赛理念</h3><span class="dot"></span></div>
                <ul class="philosophy-list">
                    <li>确立麻将作为一项高智力运动的认可度</li>
                    <li>消除麻将的负面形象</li>
                    <li>通过麻将促进代际交流，并为社会发展做出贡献</li>
                    <li>通过麻将促进国际友谊</li>
                </ul>
            </div>
            <div class="philosophy-section">
                <div class="philosophy-title"><h3>M联赛的目的</h3><span class="dot"></span></div>
                <ul class="philosophy-list">
                    <li>M 联赛通过由职业组织经过严格选拔程序选出的顶尖职业选手之间的严肃比赛，旨在确立麻将作为一项高智力运动的认可度，并为提高麻将的竞技水平做出贡献。</li>
                    <li>M 联赛要求参赛选手断绝与非法赌博的联系，并与麻将界（包括专业组织和相关公司）密切合作，以消除麻将的负面形象，提高其作为娱乐和竞技形式的地位。</li>
                    <li>通过与粉丝和支持者的互动，M League 将把麻将这项脑力运动的乐趣传播到世界各地。</li>
                    <li>M League 致力于创造一个更健康、更安全的麻将游戏环境，让从儿童到老年人各个年龄段的人都能以更健康、更安全的方式享受麻将的乐趣，促进代际互动，为社会发展做出贡献。</li>
                    <li>M联赛将在国内做出必要的努力，以期使麻将成为奥运会正式比赛项目，同时也将为国际交流与友谊做出贡献。</li>
                    <li>M 联赛非常重视队伍和选手团结一致，在比赛中全力以赴，以实现队伍赢得赛季冠军并在排名中提升一位的最终目标。</li>
                    <li>M League 认为，一支球队的赛季排名以及他们多年来的表现和积分（累计积分）都将永远具有价值。</li>
                    <li>作为一项脑力运动，M League 尊重在比赛中全力以赴以增加团队积分的态度，即使提高团队排名变得很困难。</li>
                </ul>
            </div>
        </div>
    </div>
</div>
