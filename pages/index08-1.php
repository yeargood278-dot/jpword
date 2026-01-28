<?php
$json_file_path = '../data/data08.json';
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
    <title>EJU Part 8 | Cyber</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #0b0c15; color: #fff; font-family: 'Consolas', monospace; background-image: linear-gradient(rgba(0, 255, 255, 0.05) 1px, transparent 1px), linear-gradient(90deg, rgba(0, 255, 255, 0.05) 1px, transparent 1px); background-size: 20px 20px; }
        .navbar { background: black; border-bottom: 2px solid #00f3ff; }
        .text-neon-blue { color: #00f3ff; text-shadow: 0 0 5px #00f3ff; }
        .text-neon-pink { color: #ff00ff; text-shadow: 0 0 5px #ff00ff; }
        .card-cyber { background: rgba(0,0,0,0.8); border: 1px solid #00f3ff; box-shadow: 0 0 10px rgba(0, 243, 255, 0.2); }
        .btn-glitch { background: transparent; border: 1px solid #ff00ff; color: #ff00ff; border-radius: 0; text-transform: uppercase; font-size: 0.8rem; }
        .btn-glitch:hover, .btn-glitch.active { background: #ff00ff; color: black; box-shadow: 0 0 10px #ff00ff; }
        .img-cyber { filter: contrast(120%) grayscale(50%); border: 2px solid #ff00ff; }
        .progress-bar-custom { height: 2px; background: #00f3ff; width: 0%; transition: width 0.5s; }
    </style>
</head>
<body>
    <nav class="navbar px-4 sticky-top">
        <span class="navbar-brand text-neon-blue fw-bold">EJU_SYS::PART_08</span>
        <div>
            <?php for($i=1; $i<=8; $i++): ?>
                <a href="index0<?php echo $i; ?>.php" class="btn btn-sm btn-glitch <?php echo $i==8?'active':''; ?> mb-1">P<?php echo $i; ?></a>
            <?php endfor; ?>
            <a href="../index.php" class="btn btn-sm btn-outline-light ms-2"><i class="fas fa-power-off"></i></a>
        </div>
    </nav>

    <div class="container mt-5 mb-5">
        <div class="row g-5">
            <?php foreach ($current_items as $item): ?>
                <div class="col-md-6">
                    <div class="card card-cyber p-3 h-100 position-relative">
                        <span class="position-absolute top-0 end-0 p-2 text-neon-pink small">ID: <?php echo str_pad($item['id'], 4, '0', STR_PAD_LEFT); ?></span>
                        <div class="d-flex align-items-center">
                            <img src="<?php echo $item['image']; ?>" class="img-cyber me-3" width="100" height="100" style="object-fit:cover;">
                            <div>
                                <h2 class="text-neon-blue fw-bold mb-0"><?php echo $item['word']; ?></h2>
                                <div class="text-muted">/ <?php echo $item['reading']; ?> /</div>
                                <button class="btn btn-sm border-0 text-white" onclick="speak('<?php echo addslashes($item['word']); ?>')">[ <i class="fas fa-play"></i> PLAY AUDIO ]</button>
                            </div>
                        </div>

                        <div class="translation-block mt-3 border border-secondary p-2 small text-success" style="display:none">
                            > MEANING: <?php echo $item['meaning_cn']; ?>
                        </div>

                        <div class="mt-3 small">
                            <?php foreach ($item['examples'] as $ex): ?>
                                <div class="mb-2">
                                    <span class="text-neon-pink">>></span> <?php echo $ex['jp']; ?>
                                    <i class="fas fa-volume-up text-secondary ms-1 cursor-pointer" onclick="speak('<?php echo addslashes($ex['jp']); ?>')" style="cursor:pointer"></i>
                                    <div class="translation-block text-secondary" style="display:none"><?php echo $ex['cn']; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="fixed-bottom bg-black border-top border-info py-2">
        <div class="container d-flex justify-content-between align-items-center">
            <button class="btn btn-outline-info btn-sm rounded-0" onclick="$('.translation-block').toggle()">[ TOGGLE DATA ]</button>
            <div class="text-neon-blue small">PAGE: <?php echo $current_page; ?> / <?php echo $total_pages; ?></div>
            <div>
                <a href="?page=<?php echo max(1, $current_page - 1); ?>" class="text-white text-decoration-none me-3">< PREV</a>
                <a href="?page=<?php echo min($total_pages, $current_page + 1); ?>" class="text-white text-decoration-none">NEXT ></a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script> function speak(t) { window.speechSynthesis.cancel(); const u = new SpeechSynthesisUtterance(t); u.lang='ja-JP'; window.speechSynthesis.speak(u); } </script>
</body>
</html>