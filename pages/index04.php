<?php
$json_file_path = '../data/data04.json';
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
    <title>EJU Part 4 | 雪の季節</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: linear-gradient(to top, #cfd9df 0%, #e2ebf0 100%); min-height: 100vh; }
        .glass-nav { background: rgba(255, 255, 255, 0.25); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.05); backdrop-filter: blur(4px); border-bottom: 1px solid rgba(255, 255, 255, 0.18); }
        .glass-card { background: rgba(255, 255, 255, 0.6); box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.1); backdrop-filter: blur(8px); border-radius: 20px; border: 1px solid rgba(255, 255, 255, 0.5); transition: 0.3s; }
        .glass-card:hover { background: rgba(255, 255, 255, 0.8); transform: scale(1.02); }
        .text-ice { color: #446688; }
        .btn-ice { background: white; color: #446688; border: 1px solid #aaccff; border-radius: 30px; }
        .btn-ice.active { background: #446688; color: white; }
    </style>
</head>
<body>
    <nav class="navbar glass-nav sticky-top px-4">
        <span class="navbar-brand text-ice fw-bold"><i class="far fa-snowflake me-2"></i>301-400 冬編</span>
        <div>
            <?php for($i=1; $i<=8; $i++): ?>
                <a href="index0<?php echo $i; ?>.php" class="btn btn-sm btn-ice <?php echo $i==4?'active':''; ?> mb-1"><?php echo $i; ?></a>
            <?php endfor; ?>
            <a href="../index.php" class="btn btn-sm btn-secondary rounded-circle ms-2"><i class="fas fa-home"></i></a>
        </div>
    </nav>

    <div class="container mt-5 mb-5 pb-5">
        <div class="row g-4">
            <?php foreach ($current_items as $item): ?>
                <div class="col-md-6">
                    <div class="glass-card p-4 h-100">
                        <div class="d-flex align-items-center mb-3">
                            <img src="<?php echo $item['image']; ?>" class="rounded-circle shadow-sm me-3" width="80" height="80" style="object-fit:cover;">
                            <div>
                                <h3 class="text-ice fw-bold mb-0"><?php echo $item['word']; ?></h3>
                                <span class="badge bg-secondary opacity-50"><?php echo $item['reading']; ?></span>
                            </div>
                            <button class="btn btn-light rounded-circle shadow-sm ms-auto text-ice" onclick="speak('<?php echo addslashes($item['word']); ?>')"><i class="fas fa-volume-up"></i></button>
                        </div>

                        <div class="translation-block alert alert-light border-0 shadow-sm text-secondary" style="display:none">
                            <i class="fas fa-language me-2"></i><?php echo $item['meaning_cn']; ?>
                        </div>

                        <div class="mt-3">
                            <?php foreach ($item['examples'] as $ex): ?>
                                <div class="mb-2 pb-2 border-bottom border-light">
                                    <div class="d-flex justify-content-between">
                                        <span class="text-dark small"><?php echo $ex['jp']; ?></span>
                                        <i class="fas fa-play-circle text-ice opacity-50" onclick="speak('<?php echo addslashes($ex['jp']); ?>')" style="cursor:pointer"></i>
                                    </div>
                                    <div class="translation-block text-muted small mt-1" style="display:none"><?php echo $ex['cn']; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="fixed-bottom glass-nav py-3">
        <div class="container text-center position-relative">
            <button class="btn btn-ice btn-sm position-absolute start-0" onclick="$('.translation-block').fadeToggle()">翻訳切替</button>
            <div class="btn-group shadow-sm rounded-pill overflow-hidden">
                <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-light text-ice px-4">←</a>
                <button class="btn btn-light text-ice fw-bold px-4" disabled><?php echo $current_page; ?></button>
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-light text-ice px-4">→</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script> function speak(t) { window.speechSynthesis.cancel(); const u = new SpeechSynthesisUtterance(t); u.lang='ja-JP'; window.speechSynthesis.speak(u); } </script>
</body>
</html>