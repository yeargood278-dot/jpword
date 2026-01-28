<?php
$json_file_path = '../data/data05.json';
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
    <title>EJU Part 5 | 抹茶の和</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #e8f5e9; border-left: 20px solid #2e7d32; min-height: 100vh; }
        .text-matcha { color: #2e7d32; }
        .bg-matcha { background-color: #2e7d32; color: white; }
        .card { border: 2px solid #a5d6a7; border-radius: 0; background-color: #f1f8e9; }
        .nav-link-custom { color: #1b5e20; font-weight: bold; margin-right: 5px; }
        .nav-link-custom.active { border-bottom: 2px solid #1b5e20; }
        .jp-font { font-family: 'Times New Roman', serif; }
        .btn-zen { border: 1px solid #2e7d32; color: #2e7d32; border-radius: 0; }
        .btn-zen:hover { background: #2e7d32; color: white; }
    </style>
</head>
<body>
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center border-bottom border-success pb-3 mb-4">
            <h1 class="h3 text-matcha jp-font"><i class="fas fa-leaf me-2"></i>401-500 和風編</h1>
            <div>
                <?php for($i=1; $i<=8; $i++): ?>
                    <a href="index0<?php echo $i; ?>.php" class="text-decoration-none nav-link-custom <?php echo $i==5?'active':''; ?>"><?php echo $i; ?></a>
                <?php endfor; ?>
                <a href="../index.php" class="text-decoration-none nav-link-custom ms-3"><i class="fas fa-home"></i></a>
            </div>
        </div>

        <div class="row">
            <?php foreach ($current_items as $item): ?>
                <div class="col-12 mb-4">
                    <div class="card p-0 shadow-sm">
                        <div class="row g-0">
                            <div class="col-md-3">
                                <img src="<?php echo $item['image']; ?>" class="img-fluid h-100" style="object-fit:cover; min-height: 150px;" alt="img">
                            </div>
                            <div class="col-md-9">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <h2 class="card-title text-matcha jp-font fw-bold"><?php echo $item['word']; ?> <span class="fs-6 text-muted ms-2 fw-normal"><?php echo $item['reading']; ?></span></h2>
                                        <button class="btn btn-sm btn-outline-success" onclick="speak('<?php echo addslashes($item['word']); ?>')">音声 <i class="fas fa-volume-up"></i></button>
                                    </div>
                                    
                                    <div class="translation-block mt-2 p-2 bg-white border-start border-4 border-success" style="display:none">
                                        <?php echo $item['meaning_cn']; ?>
                                    </div>

                                    <ul class="list-group list-group-flush mt-3 bg-transparent">
                                        <?php foreach ($item['examples'] as $ex): ?>
                                            <li class="list-group-item bg-transparent px-0 border-bottom-0 pb-1">
                                                <i class="fas fa-caret-right text-success me-2"></i><?php echo $ex['jp']; ?>
                                                <i class="fas fa-volume-down text-secondary ms-2 cursor-pointer" onclick="speak('<?php echo addslashes($ex['jp']); ?>')"></i>
                                                <div class="translation-block text-secondary small ms-4" style="display:none"><?php echo $ex['cn']; ?></div>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="d-flex justify-content-center align-items-center mt-4 gap-3">
             <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-zen"><i class="fas fa-chevron-left"></i> 前頁</a>
             <button class="btn btn-success" onclick="$('.translation-block').slideToggle()">訳文</button>
             <span class="text-matcha fw-bold">Page <?php echo $current_page; ?></span>
             <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-zen">次頁 <i class="fas fa-chevron-right"></i></a>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script> function speak(t) { window.speechSynthesis.cancel(); const u = new SpeechSynthesisUtterance(t); u.lang='ja-JP'; window.speechSynthesis.speak(u); } </script>
</body>
</html>