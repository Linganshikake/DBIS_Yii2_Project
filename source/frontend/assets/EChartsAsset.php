<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * ECharts asset bundle for data visualization
 * 
 * ECharts 百度开源数据可视化库
 * 用于展示：队伍积分柱状图、选手顺位饼图、得分走势折线图、能力雷达图
 */
class EChartsAsset extends AssetBundle
{
    public $sourcePath = null;
    
    public $js = [
        'https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js',
    ];
    
    public $css = [];
    
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
