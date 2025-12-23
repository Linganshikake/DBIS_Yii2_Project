<?php
/**
 * 新闻正文内容渲染
 * 
 * 支持的语法：
 * 1. 换行符 /n 会被转换为 <br> 标签
 * 2. 图片引用 [img:文件名] 会被转换为图片标签，图片存放在 /uploads/news/images/ 目录
 * 3. 封面图片引用 [img:cover] 或 [img:cover|说明] 会引用新闻的封面图片
 * 4. 段落标记 ■ 会加粗显示
 * 
 * 使用示例（在数据库content字段中）：
 * - 换行：这是第一行/n这是第二行
 * - 插入图片：[img:example.jpg]
 * - 带说明的图片：[img:example.jpg|这是图片说明]
 * - 引用封面图片：[img:cover] 或 [img:cover|图片说明]
 * - 段落标题：■关于比赛信息
 * 
 * @var string $content 新闻正文内容
 * @var string|null $cover 封面图片文件名（可选）
 */

use yii\helpers\Html;

// 获取封面图片（如果传入）
$coverImage = isset($cover) ? $cover : null;

// 处理内容
$processedContent = $content;

// 0. 预处理：将全角字符转换为半角（兼容性处理）
$fullToHalf = [
    '［' => '[',
    '］' => ']',
    '：' => ':',
    '｜' => '|',
];
$processedContent = str_replace(array_keys($fullToHalf), array_values($fullToHalf), $processedContent);

// 1. 先提取并保存图片标签（在HTML转义之前）
// 使用更简单的正则：匹配 [img:xxx] 或 [img:xxx|说明]
$imageReplacements = [];
$imageIndex = 0;
$processedContent = preg_replace_callback(
    '/\[img:([^\]]+)\]/',
    function ($matches) use (&$imageReplacements, &$imageIndex, $coverImage) {
        $content = trim($matches[1]);
        // 检查是否有说明文字（用|分隔）
        if (strpos($content, '|') !== false) {
            list($filename, $caption) = explode('|', $content, 2);
            $filename = trim($filename);
            $caption = trim($caption);
        } else {
            $filename = $content;
            $caption = '';
        }
        
        // 检查是否引用封面图片
        if (strtolower($filename) === 'cover' && $coverImage) {
            $imgSrc = '/uploads/news/cover/' . htmlspecialchars($coverImage);
        } else {
            $imgSrc = '/uploads/news/images/' . htmlspecialchars($filename);
        }
        
        $imgHtml = '<div class="news-inline-image" style="text-align: center; margin: 20px 0;">';
        $imgHtml .= '<img src="' . $imgSrc . '" style="width: 100%; border-radius: 10px;">';
        if ($caption) {
            $imgHtml .= '<div style="color: #888; font-size: 13px; margin-top: 8px; font-style: italic;">' . htmlspecialchars($caption) . '</div>';
        }
        $imgHtml .= '</div>';
        
        $placeholder = "___IMAGE_PLACEHOLDER_{$imageIndex}___";
        $imageReplacements[$placeholder] = $imgHtml;
        $imageIndex++;
        
        return $placeholder;
    },
    $processedContent
);

// 2. 先提取并保存链接标签（在HTML转义之前）
$linkReplacements = [];
$linkIndex = 0;
$processedContent = preg_replace_callback(
    '/\[link:([^\]|]+)(?:\|([^\]]*))?\]/',
    function ($matches) use (&$linkReplacements, &$linkIndex) {
        $url = trim($matches[1]);
        $text = isset($matches[2]) ? trim($matches[2]) : $url;
        
        $linkHtml = '<a href="' . htmlspecialchars($url) . '" target="_blank" style="color: #d4af37; text-decoration: underline;">' . htmlspecialchars($text) . '</a>';
        
        $placeholder = "___LINK_PLACEHOLDER_{$linkIndex}___";
        $linkReplacements[$placeholder] = $linkHtml;
        $linkIndex++;
        
        return $placeholder;
    },
    $processedContent
);

// 3. 处理换行符 /n（图片已被占位符替换，不会影响图片路径）
$processedContent = str_replace('/n', '___NEWLINE___', $processedContent);

// 4. 对剩余内容进行HTML转义（防止XSS攻击）
$processedContent = Html::encode($processedContent);

// 5. 将换行占位符替换为 <br>
$processedContent = str_replace('___NEWLINE___', '<br>', $processedContent);

// 6. 还原图片和链接
foreach ($imageReplacements as $placeholder => $html) {
    $processedContent = str_replace($placeholder, $html, $processedContent);
}
foreach ($linkReplacements as $placeholder => $html) {
    $processedContent = str_replace($placeholder, $html, $processedContent);
}

// 6. 同时处理实际的换行符 \n
$processedContent = str_replace(["\r\n", "\r", "\n"], '<br>', $processedContent);

// 7. 处理段落标记 ■（加粗显示）
$processedContent = preg_replace(
    '/■([^<\n]+)/',
    '<strong style="color: #d4af37; font-size: 18px; display: block; margin-top: 20px; margin-bottom: 10px;">■$1</strong>',
    $processedContent
);

// 8. 自动识别括号内的URL（如 https://... ）
$processedContent = preg_replace_callback(
    '/\((https?:\/\/[^\s\)]+)\)/',
    function ($matches) {
        $url = $matches[1];
        return '(<a href="' . $url . '" target="_blank" style="color: #d4af37; text-decoration: underline;">' . $url . '</a>)';
    },
    $processedContent
);

echo $processedContent;
?>
