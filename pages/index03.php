<?php
$json_file_path = '../data/data03.json';
if (!file_exists($json_file_path)) { die("Error: File not found."); }
$vocab_list = json_decode(file_get_contents($json_file_path), true) ?: [];
$per_page = 2; $total_items = count($vocab_list); $total_pages = ceil($total_items / $per_page);
$current_page = max(1, min($total_pages, (int)($_GET['page'] ?? 1)));
$current_items = array_slice($vocab_list, ($current_page - 1) * $per_page, $per_page);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJU Part 3 | 紅葉の秋</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f7f3e8; color: #5d4037; font-family: "Hiragino Mincho ProN", "Yu Mincho", serif; }
        .bg-momiji { background-color: #c62828; }
        .text-momiji { color: #c62828; }
        .navbar { background-color: #8e24aa; background: linear-gradient(to right, #8e24aa, #c62828); }
        .vocab-card { background: #fffbf0; border: 1px solid #d7ccc8; border-radius: 4px; box-shadow: 5px 5px 0px rgba(93, 64, 55, 0.2); }
        .btn-nav-custom { color: white; border: 1px solid rgba(255,255,255,0.3); font-family: sans-serif; }
        .btn-nav-custom.active { background-color: #ffca28; color: #5d4037; border-color: #ffca28; }
        .word-reading { font-family: sans-serif; color: #8d6e63; }
        .btn-speak-classic { background: transparent; border: 2px solid #5d4037; color: #5d4037; border-radius: 50%; width: 45px; height: 45px; transition: 0.3s; }
        .btn-speak-classic:hover { background: #5d4037; color: #fffbf0; }
        .translation-block { border-left: 4px solid #c62828; padding-left: 1rem; margin-top: 1rem; font-style: italic; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark px-4 py-3">
        <span class="navbar-brand fs-4"><i class="fab fa-canadian-maple-leaf me-2"></i>201-300 秋編</span>
        <div class="d-flex gap-1 flex-wrap">
            <?php for($i=1; $i<=8; $i++): ?>
                <a href="index0<?php echo $i; ?>.php" class="btn btn-sm btn-nav-custom <?php echo $i==3?'active':''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <a href="../index.php" class="btn btn-sm btn-outline-light ms-2">TOP</a>
        </div>
    </nav>

    <div class="container mt-5 mb-5 pb-5">
        <div class="row g-5">
            <?php foreach ($current_items as $item): ?>
                <div class="col-md-6">
                    <div class="vocab-card p-4 h-100">
                        <div class="row align-items-center">
                            <div class="col-4">
                                <img src="<?php echo $item['image']; ?>" class="img-fluid rounded border border-dark" alt="img">
                            </div>
                            <div class="col-8">
                                <h2 class="display-6 fw-bold text-momiji mb-0"><?php echo $item['word']; ?></h2>
                                <p class="word-reading mb-2"><?php echo $item['reading']; ?></p>
                                <button class="btn btn-speak-classic" onclick="speak('<?php echo addslashes($item['word']); ?>')"><i class="fas fa-volume-up"></i></button>
                            </div>
                        </div>
                        
                        <div class="translation-block" style="display:none">
                            <span class="text-dark fw-bold">意味：</span> <?php echo $item['meaning_cn']; ?>
                        </div>

                        <hr class="border-dark opacity-25">
                        
                        <?php foreach ($item['examples'] as $ex): ?>
                            <div class="mb-3">
                                <p class="mb-1" style="font-weight: 500;">
                                    <i class="fas fa-circle text-warning me-2" style="font-size: 8px;"></i><?php echo $ex['jp']; ?>
                                    <i class="fas fa-volume-up text-muted ms-2 cursor-pointer" onclick="speak('<?php echo addslashes($ex['jp']); ?>')" style="cursor:pointer"></i>
                                </p>
                                <p class="translation-block text-muted small mt-0 mb-0" style="display:none; border:none; padding:0;"><?php echo $ex['cn']; ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="fixed-bottom bg-dark py-3">
        <div class="container d-flex justify-content-between align-items-center text-white">
            <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" id="transSwitch" onchange="toggleTranslation()">
                <label class="form-check-label" for="transSwitch">翻訳を表示</label>
            </div>
            <div>
                <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="text-white text-decoration-none me-3 <?php echo $current_page == 1 ? 'opacity-50' : ''; ?>">前へ</a>
                <span class="border px-2 py-1 rounded"><?php echo $current_page; ?> / <?php echo $total_pages; ?></span>
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="text-white text-decoration-none ms-3 <?php echo $current_page == $total_pages ? 'opacity-50' : ''; ?>">次へ</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function speak(text) { window.speechSynthesis.cancel(); const u = new SpeechSynthesisUtterance(text); u.lang = 'ja-JP'; window.speechSynthesis.speak(u); }
        function toggleTranslation() { $('.translation-block').toggle(); }
    </script>
</body>
</html>