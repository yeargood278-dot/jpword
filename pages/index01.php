<?php
// 读取数据 (标准健壮版)
$json_file_path = '../data/data01.json';
if (!file_exists($json_file_path)) { die("错误：找不到数据文件 {$json_file_path}"); }
$json_data = file_get_contents($json_file_path);
if ($json_data === false) { die("错误：无法读取数据文件 {$json_file_path}"); }
$vocab_list = json_decode($json_data, true);
if (!is_array($vocab_list)) { $vocab_list = []; }

// 分页配置
$per_page = 2;
$total_items = count($vocab_list);
$total_pages = ceil($total_items / $per_page);
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
if ($current_page > $total_pages) $current_page = $total_pages;
$offset = ($current_page - 1) * $per_page;
$current_items = array_slice($vocab_list, $offset, $per_page);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJU Part 1 | 桜の季節</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-color: #ff9a9e; --secondary-color: #fecfef; --accent-color: #ff6b6b; }
        body { background: linear-gradient(120deg, #fdfbfb 0%, #ebedee 100%); background-image: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCIgdmlld0JveD0iMCAwIDIwIDIwIiBmaWxsPSIjZmY5YTllIiBmaWxsLW9wYWNpdHk9IjAuMSI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiLz48L3N2Zz4'); font-family: 'Helvetica Neue', Arial, sans-serif; }
        .navbar { background: rgba(255, 255, 255, 0.9); border-bottom: 3px solid var(--primary-color); }
        .vocab-card { border: none; border-radius: 20px; transition: transform 0.3s; overflow: hidden; border-top: 5px solid var(--primary-color); }
        .vocab-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(255, 154, 158, 0.2) !important; }
        .vocab-img { height: 200px; object-fit: cover; border-bottom: 1px solid #eee; }
        .badge-id { background-color: var(--secondary-color); color: #d63384; }
        .btn-speak { background-color: var(--primary-color); border: none; border-radius: 50px; }
        .btn-speak:hover { background-color: var(--accent-color); }
        .vocab-nav-btn { border-radius: 20px; font-weight: bold; }
        .pagination .page-item.disabled .page-link { background-color: #f8f9fa; }
        .badge-custom { padding: 5px 10px; border-radius: 10px; }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg sticky-top shadow-sm px-4">
        <div class="d-flex align-items-center w-100 justify-content-between flex-wrap">
            <span class="navbar-brand mb-0 h1 text-danger"><i class="fas fa-cherry-blossom fa-spin text-danger"></i> 001-100 桜編</span>
            <div class="d-flex align-items-center gap-1 flex-wrap">
                <?php for($i=1; $i<=8; $i++): ?>
                    <a href="index0<?php echo $i; ?>.php" class="btn btn-sm <?php echo $i==1?'btn-danger':'btn-outline-danger'; ?> vocab-nav-btn">P<?php echo $i; ?></a>
                <?php endfor; ?>
                <a href="vocab_practice.php" class="btn btn-sm btn-outline-success vocab-nav-btn ms-2">練</a>
                <a href="vocab_test.php" class="btn btn-sm btn-outline-warning vocab-nav-btn">測</a>
                <a href="../index.php" class="btn btn-sm btn-secondary ms-2 rounded-pill"><i class="fas fa-home"></i></a>
            </div>
        </div>
    </nav>

    <div class="container mt-4 mb-5 pb-5">
        <div class="row g-4">
            <?php foreach ($current_items as $item): ?>
                <div class="col-md-6">
                    <div class="card h-100 shadow-sm vocab-card">
                        <img src="<?php echo $item['image']; ?>" class="vocab-img" alt="<?php echo $item['word']; ?>">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <span class="badge badge-id mb-2">No.<?php echo $item['id']; ?></span>
                                    <h2 class="card-title fw-bold text-dark mb-0"><?php echo $item['word']; ?></h2>
                                    <span class="text-secondary small"><?php echo $item['reading']; ?></span>
                                </div>
                                <button class="btn btn-sm btn-speak text-white shadow-sm" onclick="speak('<?php echo addslashes($item['word']); ?>')"><i class="fas fa-volume-up"></i></button>
                            </div>
                            
                            <div class="translation-block bg-danger bg-opacity-10 p-2 rounded mb-3" style="display: none;">
                                <p class="mb-0 text-danger fw-bold"><i class="fas fa-language me-2"></i><?php echo $item['meaning_cn']; ?></p>
                            </div>

                            <div class="details-section small text-muted">
                                <?php if (!empty($item['synonyms'])): ?><div class="mb-1"><span class="badge bg-info bg-opacity-25 text-info me-2">類</span><?php echo implode(', ', $item['synonyms']); ?></div><?php endif; ?>
                                <?php if (!empty($item['antonyms'])): ?><div class="mb-1"><span class="badge bg-warning bg-opacity-25 text-warning me-2">対</span><?php echo implode(', ', $item['antonyms']); ?></div><?php endif; ?>
                            </div>

                            <div class="mt-3 border-top pt-2">
                                <h6 class="text-secondary small fw-bold">例文</h6>
                                <?php foreach ($item['examples'] as $ex): ?>
                                    <div class="bg-light p-2 rounded mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span class="text-dark small"><i class="fas fa-caret-right text-danger me-1"></i><?php echo $ex['jp']; ?></span>
                                            <i class="fas fa-volume-up text-secondary cursor-pointer" onclick="speak('<?php echo addslashes($ex['jp']); ?>')" style="cursor:pointer; font-size:0.8rem;"></i>
                                        </div>
                                        <div class="translation-block text-secondary small mt-1 ps-3 fst-italic" style="display: none;"><?php echo $ex['cn']; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="fixed-bottom bg-white bg-opacity-90 border-top py-3 backdrop-blur shadow-lg">
        <div class="container d-flex justify-content-between align-items-center">
            <button class="btn btn-danger rounded-pill shadow-sm" onclick="toggleTranslation()"><i class="fas fa-eye me-1"></i> <span id="trans-btn-text">訳文表示</span></button>
            <div class="btn-group">
                <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-outline-danger <?php echo $current_page == 1 ? 'disabled' : ''; ?>"><i class="fas fa-chevron-left"></i></a>
                <button class="btn btn-danger disabled"><?php echo $current_page; ?> / <?php echo $total_pages; ?></button>
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-outline-danger <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>"><i class="fas fa-chevron-right"></i></a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function speak(text) { window.speechSynthesis.cancel(); const u = new SpeechSynthesisUtterance(text); u.lang = 'ja-JP'; u.rate = 0.9; window.speechSynthesis.speak(u); }
        function toggleTranslation() { const t = $('.translation-block'); const b = $('#trans-btn-text'); if(t.is(':visible')){ t.hide(); b.text('訳文表示'); } else { t.show(); b.text('訳文非表示'); } }
    </script>
</body>
</html>