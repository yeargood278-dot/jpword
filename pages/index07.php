<?php
$json_file_path = '../data/data07.json';
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
    <title>EJU Part 7 | レトロ</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4e1d2; color: #5e4b35; font-family: 'Courier New', Courier, monospace; }
        .navbar { background-color: #6f4e37; }
        .card { background-color: #fff8f0; border: 2px dashed #6f4e37; border-radius: 15px; }
        .btn-coffee { background-color: #8d6e63; color: white; border: none; }
        .btn-coffee:hover { background-color: #6d4c41; color: white; }
        .nav-pill-custom { background: #8d6e63; color: #f4e1d2; margin: 2px; border-radius: 5px; text-decoration: none; padding: 5px 10px; font-size: 0.8rem; }
        .nav-pill-custom.active { background: #f4e1d2; color: #6f4e37; border: 1px solid #6f4e37; }
        .badge-coffee { background: #d7ccc8; color: #3e2723; }
        .highlight-text { background-color: #ffccbc; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    <nav class="navbar px-4 shadow">
        <span class="navbar-brand text-white fw-bold"><i class="fas fa-coffee me-2"></i>601-700 レトロ</span>
        <div class="d-flex flex-wrap">
            <?php for($i=1; $i<=8; $i++): ?>
                <a href="index0<?php echo $i; ?>.php" class="nav-pill-custom <?php echo $i==7?'active':''; ?>"><?php echo $i; ?></a>
            <?php endfor; ?>
            <a href="../index.php" class="btn btn-sm btn-outline-light ms-2 rounded-circle"><i class="fas fa-home"></i></a>
        </div>
    </nav>

    <div class="container mt-4 mb-5 pb-4">
        <div class="row g-4">
            <?php foreach ($current_items as $item): ?>
                <div class="col-md-6">
                    <div class="card p-3 h-100 shadow-sm">
                        <div class="text-center mb-3">
                            <img src="<?php echo $item['image']; ?>" class="rounded-circle border border-3 border-white shadow" width="120" height="120" style="object-fit: cover;">
                        </div>
                        <div class="text-center">
                            <h2 class="fw-bold"><?php echo $item['word']; ?></h2>
                            <span class="badge badge-coffee"><?php echo $item['reading']; ?></span>
                            <button class="btn btn-sm btn-outline-dark rounded-pill ms-2" onclick="speak('<?php echo addslashes($item['word']); ?>')"><i class="fas fa-music"></i></button>
                        </div>
                        <div class="translation-block mt-3 text-center" style="display:none">
                            <span class="highlight-text"><?php echo $item['meaning_cn']; ?></span>
                        </div>
                        <div class="mt-4 border-top border-secondary pt-3">
                            <?php foreach ($item['examples'] as $ex): ?>
                                <div class="mb-2">
                                    <p class="mb-0 fw-bold small"><i class="fas fa-comment-dots me-1"></i> <?php echo $ex['jp']; ?> 
                                    <i class="fas fa-volume-up ms-1 text-muted" onclick="speak('<?php echo addslashes($ex['jp']); ?>')" style="cursor:pointer"></i></p>
                                    <p class="translation-block mb-0 small text-muted fst-italic" style="display:none"><?php echo $ex['cn']; ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="fixed-bottom py-3" style="background: #6f4e37;">
        <div class="container d-flex justify-content-between">
            <button class="btn btn-light btn-sm text-brown fw-bold" onclick="$('.translation-block').slideToggle()">答え合わせ</button>
            <div>
                <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-sm btn-outline-light">Prev</a>
                <span class="text-white mx-2 fw-bold"><?php echo $current_page; ?></span>
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-sm btn-outline-light">Next</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script> function speak(t) { window.speechSynthesis.cancel(); const u = new SpeechSynthesisUtterance(t); u.lang='ja-JP'; window.speechSynthesis.speak(u); } </script>
</body>
</html>