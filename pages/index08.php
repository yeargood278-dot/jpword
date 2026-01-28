<?php
// 读取数据 - 增加异常处理
$json_file_path = '../data/data08.json';
// 1. 检查文件是否存在
if (!file_exists($json_file_path)) {
    die("错误：找不到数据文件 {$json_file_path}");
}
// 2. 读取文件并处理读取失败情况
$json_data = file_get_contents($json_file_path);
if ($json_data === false) {
    die("错误：无法读取数据文件 {$json_file_path}");
}
// 3. 解析JSON并处理解析失败情况
$vocab_list = json_decode($json_data, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die("错误：JSON解析失败，错误信息：" . json_last_error_msg());
}
// 4. 确保vocab_list是数组（兜底处理）
if (!is_array($vocab_list)) {
    $vocab_list = [];
}

// 分页配置
$per_page = 2; // 每页显示2个单词
$total_items = count($vocab_list);
$total_pages = ceil($total_items / $per_page);

// 获取当前页码
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;

// 计算切片偏移量
$offset = ($current_page - 1) * $per_page;
$current_items = array_slice($vocab_list, $offset, $per_page);
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJU日语词汇学习 PART8 701-800</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* 自定义导航按钮样式，适配小屏 */
        .vocab-nav-btn {
            min-width: 40px;
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        /* 响应式布局，小屏自动换行 */
        .nav-btn-group {
            flex-wrap: wrap;
            gap: 0.25rem;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-light bg-light shadow-sm px-4">
        <span class="navbar-brand mb-0 h1"><i class="fas fa-book-open text-primary"></i> EJU日语词汇学习 (PART8 701-800)</span>
        <!-- 新增导航按钮组 -->
        <div class="d-flex align-items-center nav-btn-group me-2">
            <!-- P1-P8 词汇分册按钮 -->
            <button class="btn btn-outline-secondary btn-sm vocab-nav-btn" onclick="window.location.href='index01.php'">P1</button>
            <button class="btn btn-outline-secondary btn-sm vocab-nav-btn" onclick="window.location.href='index02.php'">P2</button>
            <button class="btn btn-outline-secondary btn-sm vocab-nav-btn" onclick="window.location.href='index03.php'">P3</button>
            <button class="btn btn-outline-secondary btn-sm vocab-nav-btn" onclick="window.location.href='index04.php'">P4</button>
            <button class="btn btn-outline-secondary btn-sm vocab-nav-btn" onclick="window.location.href='index05.php'">P5</button>
            <button class="btn btn-outline-secondary btn-sm vocab-nav-btn" onclick="window.location.href='index06.php'">P6</button>
            <button class="btn btn-outline-secondary btn-sm vocab-nav-btn" onclick="window.location.href='index07.php'">P7</button>
            <button class="btn btn-outline-secondary btn-sm vocab-nav-btn" onclick="window.location.href='index08.php'">P8</button>
            <!-- 练习、测试按钮 -->
            <button class="btn btn-outline-success btn-sm vocab-nav-btn" onclick="window.location.href='vocab_practice.php'">练</button>
            <button class="btn btn-outline-warning btn-sm vocab-nav-btn" onclick="window.location.href='vocab_test.php'">测</button>
        </div>
        <!-- 主页按钮 -->
        <button class="btn btn-outline-primary btn-sm me-2" onclick="window.location.href='../index.php'">
            <i class="fas fa-home"></i> 主页
        </button>
    </nav>

    <div class="container main-stage mt-4">
        <div class="row">
            <?php if (empty($current_items)): ?>
                <div class="col-12 text-center py-5">
                    <h4 class="text-muted">暂无词汇数据</h4>
                </div>
            <?php else: ?>
                <?php foreach ($current_items as $item): ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100 shadow vocab-card">
                            <div class="image-container">
                                <img src="<?php echo $item['image']; ?>" class="card-img-top vocab-img" alt="<?php echo $item['word']; ?>">
                            </div>
                            
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <span class="badge bg-secondary mb-1"><?php echo $item['id']; ?></span>
                                        <h2 class="card-title text-primary mb-0">
                                            <?php echo $item['word']; ?>
                                            <small class="text-muted fs-6">（<?php echo $item['reading']; ?>）</small>
                                        </h2>
                                    </div>
                                    <button class="btn btn-sm btn-primary btn-speak" onclick="speak('<?php echo addslashes($item['word']); ?>')">
                                        <i class="fas fa-volume-up"></i> 读
                                    </button>
                                </div>

                                <div class="translation-block mb-3" style="display: none;">
                                    <p class="card-text text-success fw-bold"><i class="fas fa-language"></i> <?php echo $item['meaning_cn']; ?></p>
                                </div>

                                <hr>

                                <div class="details-section">
                                    <?php if (!empty($item['synonyms'])): ?>
                                        <p><strong><span class="badge bg-info text-dark">类</span></strong> <?php echo implode(' / ', $item['synonyms']); ?></p>
                                    <?php endif; ?>

                                    <?php if (!empty($item['antonyms'])): ?>
                                        <p><strong><span class="badge bg-warning text-dark">对</span></strong> <?php echo implode(' / ', $item['antonyms']); ?></p>
                                    <?php endif; ?>

                                    <?php if (!empty($item['collocations'])): ?>
                                        <p><strong><span class="badge bg-success">连</span></strong> <?php echo implode(' • ', $item['collocations']); ?></p>
                                    <?php endif; ?>
                                </div>

                                <div class="examples-section mt-3 bg-light p-2 rounded">
                                    <h6 class="text-muted border-bottom pb-1">例句 (Example)</h6>
                                    <?php foreach ($item['examples'] as $ex): ?>
                                        <div class="example-item mb-2">
                                            <div class="d-flex justify-content-between">
                                                <span><i class="fas fa-quote-left text-muted me-1"></i> <?php echo $ex['jp']; ?></span>
                                                <button class="btn btn-xs text-primary" onclick="speak('<?php echo addslashes($ex['jp']); ?>')"><i class="fas fa-volume-up"></i></button>
                                            </div>
                                            <div class="translation-block text-secondary small mt-1 ps-3" style="display: none;">
                                                <?php echo $ex['cn']; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <div class="fixed-bottom bg-white border-top py-3">
        <div class="container d-flex justify-content-between align-items-center">
            
            <button class="btn btn-warning" onclick="toggleTranslation()">
                <i class="fas fa-eye"></i> <span id="trans-btn-text">显示译文</span>
            </button>

            <div class="btn-group" role="group">
                <a href="?page=1" class="btn btn-outline-primary <?php echo $current_page == 1 ? 'disabled' : ''; ?>">首页</a>
                <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-outline-primary <?php echo $current_page == 1 ? 'disabled' : ''; ?>">上一页</a>
                
                <button class="btn btn-primary disabled">
                    <?php echo $current_page; ?> / <?php echo $total_pages; ?>
                </button>
                
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-outline-primary <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">下一页</a>
                <a href="?page=<?php echo $total_pages; ?>" class="btn btn-outline-primary <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">尾页</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // 修复：实现日语语音合成函数
        function speak(text) {
            // 停止当前正在播放的语音
            window.speechSynthesis.cancel();
            
            // 创建语音实例
            const utterance = new SpeechSynthesisUtterance(text);
            
            // 关键配置：设置日语语音（兼容不同浏览器）
            utterance.lang = 'ja-JP'; 
            
            // 可选：调整语速和音量
            utterance.rate = 0.9; // 语速（0.1-10）
            utterance.volume = 1; // 音量（0-1）
            utterance.pitch = 1;  // 音调（0-2）
            
            // 播放语音
            window.speechSynthesis.speak(utterance);
        }

        // 修复：实现译文显示/隐藏切换函数
        function toggleTranslation() {
            const $transBlocks = $('.translation-block');
            const $btnText = $('#trans-btn-text');
            
            if ($transBlocks.is(':visible')) {
                $transBlocks.hide();
                $btnText.text('显示译文');
            } else {
                $transBlocks.show();
                $btnText.text('隐藏译文');
            }
        }
    </script>
</body>
</html>
