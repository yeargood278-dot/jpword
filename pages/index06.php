<?php
$json_file_path = '../data/data06.json';
if (!file_exists($json_file_path)) { die("Error."); }
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
    <title>EJU Part 6 | 真夜中</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #1a1a2e; color: #e0e0e0; }
        .navbar { background-color: #16213e; border-bottom: 1px solid #0f3460; }
        .card { background-color: #16213e; border: 1px solid #0f3460; color: #e94560; }
        .text-neon { color: #e94560; }
        .text-light-blue { color: #4db5ff; }
        .btn-outline-neon { border-color: #e94560; color: #e94560; }
        .btn-outline-neon:hover, .btn-outline-neon.active { background-color: #e94560; color: white; }
        .btn-circle { width: 35px; height: 35px; padding: 0; line-height: 35px; border-radius: 50%; }
        .badge-dark { background-color: #0f3460; color: #4db5ff; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark px-4 py-3">
        <span class="navbar-brand fw-bold text-light-blue"><i class="fas fa-moon me-2"></i>501-600 夜編</span>
        <div>
            <?php for($i=1; $i<=8; $i++): ?>
                <a href="index0<?php echo $i; ?>.php" class="btn btn-sm btn-outline-neon <?php echo $i==6?'active':''; ?> me-1"><?php echo $i; ?></a>
            <?php endfor; ?>
            <a href="../index.php" class="btn btn-sm btn-secondary ms-2"><i class="fas fa-home"></i></a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row g-4">
            <?php foreach ($current_items as $item): ?>
                <div class="col-md-6">
                    <div class="card h-100 shadow-lg">
                        <div class="row g-0 h-100">
                            <div class="col-4">
                                <img src="<?php echo $item['image']; ?>" class="img-fluid rounded-start h-100" style="object-fit: cover; opacity: 0.8;" alt="...">
                            </div>
                            <div class="col-8">
                                <div class="card-body">
                                    <h3 class="card-title text-light-blue fw-bold"><?php echo $item['word']; ?></h3>
                                    <span class="badge badge-dark mb-2"><?php echo $item['reading']; ?></span>
                                    <button class="btn btn-sm btn-outline-light float-end" onclick="speak('<?php echo addslashes($item['word']); ?>')"><i class="fas fa-volume-up"></i></button>
                                    
                                    <p class="card-text text-white small mt-2 translation-block" style="display:none; border-left: 2px solid #e94560; padding-left: 10px;">
                                        <?php echo $item['meaning_cn']; ?>
                                    </p>
                                    
                                    <div class="mt-3 small text-secondary">
                                        <?php foreach ($item['examples'] as $ex): ?>
                                            <div class="mb-1 text-light border-bottom border-secondary pb-1">
                                                <i class="fas fa-chevron-right text-neon me-1"></i> <?php echo $ex['jp']; ?>
                                                <i class="fas fa-play text-secondary ms-1" style="font-size: 10px; cursor: pointer;" onclick="speak('<?php echo addslashes($ex['jp']); ?>')"></i>
                                                <div class="translation-block text-secondary fst-italic" style="display:none"><?php echo $ex['cn']; ?></div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="fixed-bottom bg-dark py-3 border-top border-secondary">
        <div class="container d-flex justify-content-between">
            <button class="btn btn-outline-info btn-sm" onclick="$('.translation-block').fadeToggle()">Translation / 翻訳</button>
            <div class="btn-group btn-group-sm">
                <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-secondary"><i class="fas fa-arrow-left"></i></a>
                <button class="btn btn-dark" disabled><?php echo $current_page; ?> / <?php echo $total_pages; ?></button>
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-secondary"><i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script> function speak(t) { window.speechSynthesis.cancel(); const u = new SpeechSynthesisUtterance(t); u.lang='ja-JP'; window.speechSynthesis.speak(u); } </script>
</body>
</html>