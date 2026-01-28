<?php
$json_file_path = '../data/data02.json';
if (!file_exists($json_file_path)) { die("Error: File not found."); }
$vocab_list = json_decode(file_get_contents($json_file_path), true) ?: [];
// Pagination Logic
$per_page = 2; $total_items = count($vocab_list); $total_pages = ceil($total_items / $per_page);
$current_page = max(1, min($total_pages, (int)($_GET['page'] ?? 1)));
$current_items = array_slice($vocab_list, ($current_page - 1) * $per_page, $per_page);
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJU Part 2 | 青い夏</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --ocean-blue: #0077be; --sky-blue: #a0e9ff; --sand: #fdfcf0; }
        body { background-color: var(--sky-blue); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .navbar { background: var(--ocean-blue); }
        .nav-btn-custom { color: white; border: 1px solid rgba(255,255,255,0.5); }
        .nav-btn-custom.active { background: white; color: var(--ocean-blue); }
        .card { border: none; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .card-header-img { height: 180px; background-color: #eee; position: relative; overflow: hidden; border-radius: 15px 15px 0 0; }
        .card-header-img img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .card:hover .card-header-img img { transform: scale(1.1); }
        .word-title { color: var(--ocean-blue); font-weight: 900; }
        .btn-audio { background: var(--ocean-blue); color: white; border-radius: 50%; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <nav class="navbar navbar-dark shadow px-3">
        <span class="navbar-brand fw-bold"><i class="fas fa-swimmer me-2"></i>101-200 夏編</span>
        <div class="d-flex gap-1 flex-wrap">
            <?php for($i=1; $i<=8; $i++): ?>
                <a href="index0<?php echo $i; ?>.php" class="btn btn-sm nav-btn-custom <?php echo $i==2?'active':''; ?>">P<?php echo $i; ?></a>
            <?php endfor; ?>
            <a href="../index.php" class="btn btn-sm btn-light ms-2"><i class="fas fa-home"></i></a>
        </div>
    </nav>

    <div class="container mt-4 mb-5 pb-5">
        <div class="row g-4">
            <?php foreach ($current_items as $item): ?>
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header-img">
                            <img src="<?php echo $item['image']; ?>" alt="img">
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h2 class="word-title display-6 mb-0"><?php echo $item['word']; ?></h2>
                                    <span class="badge bg-info text-dark"><?php echo $item['reading']; ?></span>
                                </div>
                                <button class="btn btn-audio shadow" onclick="speak('<?php echo addslashes($item['word']); ?>')"><i class="fas fa-play"></i></button>
                            </div>
                            
                            <div class="translation-block alert alert-primary py-2" style="display:none">
                                <strong>意味：</strong><?php echo $item['meaning_cn']; ?>
                            </div>

                            <div class="examples mt-3">
                                <?php foreach ($item['examples'] as $ex): ?>
                                    <div class="border-start border-4 border-info ps-2 mb-2 bg-light py-1">
                                        <div onclick="speak('<?php echo addslashes($ex['jp']); ?>')" style="cursor:pointer">
                                            <?php echo $ex['jp']; ?> <i class="fas fa-volume-up text-muted ms-1 small"></i>
                                        </div>
                                        <div class="translation-block small text-muted" style="display:none"><?php echo $ex['cn']; ?></div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="fixed-bottom bg-white border-top py-3">
        <div class="container d-flex justify-content-between">
            <button class="btn btn-outline-primary" onclick="toggleTranslation()"><i class="fas fa-glasses me-1"></i> <span id="trans-btn-text">Anwsers</span></button>
            <div class="btn-group">
                <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="btn btn-primary <?php echo $current_page == 1 ? 'disabled' : ''; ?>">Prev</a>
                <span class="btn btn-light border"><?php echo $current_page; ?></span>
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="btn btn-primary <?php echo $current_page == $total_pages ? 'disabled' : ''; ?>">Next</a>
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