<?php
// PHP基础配置
header('Content-Type: text/html; charset=utf-8');
$siteTitle = 'EJU日语词汇大冒险';
$siteSubtitle = 'アニメの世界で学ぶ日本語 | 动漫风沉浸式学习';

// 定义按钮数据配置（方便管理和扩展）
// 不同的部分分配不同的动漫主题色和图标
$menuItems = [
    [
        'url' => 'pages/index01.php',
        'text' => '語彙 001-100',
        'theme' => 'flower', // 花仙子主题
        'icon' => 'fa-seedling',
        'desc' => '旅立ちの時'
    ],
    [
        'url' => 'pages/index02.php',
        'text' => '語彙 101-200',
        'theme' => 'flower',
        'icon' => 'fa-fan',
        'desc' => '花の鍵'
    ],
    [
        'url' => 'pages/index03.php',
        'text' => '語彙 201-300',
        'theme' => 'chihiro', // 千寻主题
        'icon' => 'fa-train-subway',
        'desc' => '不思議の町'
    ],
    [
        'url' => 'pages/index04.php',
        'text' => '語彙 301-400',
        'theme' => 'chihiro',
        'icon' => 'fa-dragon',
        'desc' => '竜の背中'
    ],
    [
        'url' => 'pages/index05.php',
        'text' => '語彙 401-500',
        'theme' => 'chihiro',
        'icon' => 'fa-bath',
        'desc' => '湯屋の仕事'
    ],
    [
        'url' => 'pages/index06.php',
        'text' => '語彙 501-600',
        'theme' => 'ikkyu', // 一休主题
        'icon' => 'fa-brain',
        'desc' => '知恵比べ'
    ],
    [
        'url' => 'pages/index07.php',
        'text' => '語彙 601-700',
        'theme' => 'ikkyu',
        'icon' => 'fa-bell',
        'desc' => '諸行無常'
    ],
    [
        'url' => 'pages/index08.php',
        'text' => '語彙 701-800',
        'theme' => 'ikkyu',
        'icon' => 'fa-torii-gate',
        'desc' => '将軍様'
    ],
];
?>
<!DOCTYPE html>
<html lang="ja-JP">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $siteTitle; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Kiwi+Maru:wght@400;500&family=M+PLUS+Rounded+1c:wght@400;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        anime: {
                            sky: '#A0E9FF',      // 动漫晴空蓝
                            cloud: '#F0F9FF',    // 云朵白
                            flower: '#FFB7B2',   // 花仙子粉
                            flowerDark: '#FF9E99',
                            chihiro: '#FF6B6B',  // 油屋红
                            chihiroDark: '#E05555',
                            ikkyu: '#4D96FF',    // 智慧蓝
                            ikkyuDark: '#3A7BD5',
                            accent: '#FFD93D',   // 活力黄
                        }
                    },
                    fontFamily: {
                        // 圆润可爱的日系字体
                        jp: ['"M PLUS Rounded 1c"', '"Kiwi Maru"', 'sans-serif'], 
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'float-fast': 'float 4s ease-in-out infinite',
                        'spin-slow': 'spin 12s linear infinite',
                        'wiggle': 'wiggle 1s ease-in-out infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-20px)' },
                        },
                        wiggle: {
                            '0%, 100%': { transform: 'rotate(-3deg)' },
                            '50%': { transform: 'rotate(3deg)' },
                        }
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer utilities {
            .text-stroke {
                -webkit-text-stroke: 1px #fff;
                text-shadow: 2px 2px 0px rgba(0,0,0,0.1);
            }
            .card-anime {
                @apply relative overflow-hidden rounded-3xl border-4 border-white shadow-xl transition-all duration-300 cursor-pointer;
            }
            .card-anime:hover {
                @apply transform -translate-y-2 shadow-2xl scale-105;
            }
            /* 花仙子主题卡片 */
            .theme-flower {
                @apply bg-gradient-to-br from-pink-100 to-pink-200 text-pink-600 border-pink-200;
            }
            .theme-flower:hover {
                @apply ring-4 ring-pink-300;
            }
            /* 千寻主题卡片 */
            .theme-chihiro {
                @apply bg-gradient-to-br from-orange-100 to-red-100 text-red-600 border-orange-200;
            }
            .theme-chihiro:hover {
                @apply ring-4 ring-red-300;
            }
            /* 一休主题卡片 */
            .theme-ikkyu {
                @apply bg-gradient-to-br from-blue-100 to-indigo-100 text-blue-600 border-blue-200;
            }
            .theme-ikkyu:hover {
                @apply ring-4 ring-blue-300;
            }
        }

        /* 动态背景装饰 */
        body {
            background: linear-gradient(135deg, #fdfbfb 0%, #ebedee 100%);
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }

        /* 樱花飘落动画 */
        .sakura {
            position: absolute;
            background-color: #ffd1dc;
            border-radius: 100% 0 100% 0;
            opacity: 0.5;
            animation: fall linear infinite;
        }
        @keyframes fall {
            0% { top: -10%; transform: translateX(0) rotate(0deg); opacity: 0; }
            10% { opacity: 0.8; }
            100% { top: 110%; transform: translateX(100px) rotate(360deg); opacity: 0; }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col font-jp relative overflow-x-hidden selection:bg-anime-accent selection:text-white">

    <div id="sakura-container" class="fixed inset-0 pointer-events-none z-0"></div>
    
    <div class="fixed top-10 left-10 text-anime-sky/30 text-8xl animate-float pointer-events-none z-0">
        <i class="fas fa-cloud"></i>
    </div>
    <div class="fixed top-20 right-20 text-anime-sky/20 text-6xl animate-float-fast pointer-events-none z-0">
        <i class="fas fa-cloud"></i>
    </div>
    <div class="fixed bottom-10 left-20 text-anime-flower/20 text-7xl animate-float pointer-events-none z-0">
        <i class="fas fa-fan"></i>
    </div>

    <div class="relative z-10 w-full max-w-7xl mx-auto px-4 py-8 flex flex-col items-center">
        
        <header class="text-center mb-12 relative group cursor-default">
            <div class="inline-block relative">
                <i class="fas fa-sun text-anime-accent text-5xl absolute -top-8 -right-8 animate-spin-slow"></i>
                <h1 class="text-[clamp(2.5rem,6vw,4rem)] font-black text-transparent bg-clip-text bg-gradient-to-r from-anime-flower via-anime-chihiro to-anime-ikkyu pb-2 drop-shadow-sm">
                    <?php echo $siteTitle; ?>
                </h1>
            </div>
            <p class="mt-4 text-gray-500 text-lg md:text-xl font-bold tracking-widest bg-white/60 inline-block px-6 py-2 rounded-full shadow-sm backdrop-blur-sm border border-white">
                <i class="fas fa-star text-anime-accent mr-2"></i>
                <?php echo $siteSubtitle; ?>
                <i class="fas fa-star text-anime-accent ml-2"></i>
            </p>
        </header>

        <main class="w-full grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-16">
            
            <?php foreach ($menuItems as $index => $item): ?>
                <?php 
                    $themeClass = 'theme-' . $item['theme'];
                    $delay = $index * 100; // 动画延迟
                ?>
                <a href="<?php echo $item['url']; ?>" 
                   class="card-anime <?php echo $themeClass; ?> group p-6 flex flex-col items-center justify-between min-h-[180px]"
                   style="animation-delay: <?php echo $delay; ?>ms"
                   data-jp-text="<?php echo $item['text']; ?>">
                    
                    <i class="fas <?php echo $item['icon']; ?> absolute -bottom-4 -right-4 text-8xl opacity-10 transform -rotate-12 transition-transform group-hover:rotate-0 group-hover:scale-110"></i>
                    
                    <div class="w-full flex justify-between items-start z-10">
                        <span class="bg-white/80 backdrop-blur-md px-3 py-1 rounded-full text-xs font-bold shadow-sm text-gray-500">
                            PART <?php echo $index + 1; ?>
                        </span>
                        <i class="fas <?php echo $item['icon']; ?> text-2xl opacity-70"></i>
                    </div>

                    <h2 class="text-3xl font-black mt-4 mb-2 z-10 text-stroke tracking-wider text-center group-hover:scale-110 transition-transform">
                        <?php echo $item['text']; ?>
                    </h2>

                    <p class="text-sm font-bold opacity-80 z-10 bg-white/40 px-4 py-1 rounded-lg">
                        <?php echo $item['desc']; ?>
                    </p>
                </a>
            <?php endforeach; ?>

            <a href="pages/index-exercise.php" 
               class="card-anime bg-gradient-to-r from-anime-flower to-purple-200 text-purple-700 border-purple-200 md:col-span-2 lg:col-span-2 flex items-center justify-center relative min-h-[180px] group"
               data-jp-text="語彙練習">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="text-center z-10">
                    <i class="fas fa-pencil-alt text-4xl mb-2 animate-bounce"></i>
                    <h2 class="text-4xl font-black text-stroke">語彙練習</h2>
                    <p class="mt-2 font-bold opacity-80">知識の定着 · 记忆巩固</p>
                </div>
            </a>

            <a href="pages/index-test.php" 
               class="card-anime bg-gradient-to-r from-anime-accent to-orange-300 text-orange-800 border-orange-200 md:col-span-2 lg:col-span-2 flex items-center justify-center relative min-h-[180px] group"
               data-jp-text="試験の挑戦">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10"></div>
                <div class="text-center z-10">
                    <i class="fas fa-flag-checkered text-4xl mb-2 group-hover:animate-wiggle"></i>
                    <h2 class="text-4xl font-black text-stroke">試験の挑戦</h2>
                    <p class="mt-2 font-bold opacity-80">実力診断 · 最终试炼</p>
                </div>
            </a>

        </main>

        <footer class="text-center text-gray-500 text-sm font-bold bg-white/80 backdrop-blur-md px-8 py-4 rounded-full shadow-sm">
            <p>© 2026 EJU日本語語彙学習サイト | Designed with CF Studio <i class="fas fa-heart text-anime-chihiro animate-pulse"></i> for Anime Lovers 版权所有：春风工作室 著作権所有：春風スタジオ </p>
        </footer>
    </div>

    <script>
        // 1. 樱花生成脚本
        function createSakura() {
            const container = document.getElementById('sakura-container');
            const sakura = document.createElement('div');
            sakura.classList.add('sakura');
            
            // 随机大小、位置、动画时长
            const size = Math.random() * 15 + 10 + 'px';
            sakura.style.width = size;
            sakura.style.height = size;
            sakura.style.left = Math.random() * 100 + 'vw';
            sakura.style.animationDuration = Math.random() * 5 + 5 + 's';
            sakura.style.animationDelay = Math.random() * 2 + 's';

            container.appendChild(sakura);

            // 动画结束后移除元素
            setTimeout(() => {
                sakura.remove();
            }, 10000);
        }

        // 每300ms生成一片樱花
        setInterval(createSakura, 300);

        // 2. 语音播报逻辑 (Web Speech API)
        const speechSynth = window.speechSynthesis;
        let currentUtterance = null;

        function speakJapanese(text) {
            if (speechSynth.speaking) {
                speechSynth.cancel();
            }
            currentUtterance = new SpeechSynthesisUtterance(text);
            currentUtterance.lang = 'ja-JP';
            currentUtterance.volume = 1;
            currentUtterance.rate = 1; 
            currentUtterance.pitch = 1.2; // 稍微提高音调，更像动漫角色
            speechSynth.speak(currentUtterance);
        }

        // 为所有卡片绑定交互
        document.querySelectorAll('a[data-jp-text]').forEach(card => {
            card.addEventListener('mouseenter', () => {
                const text = card.getAttribute('data-jp-text');
                speakJapanese(text);
            });
        });
    </script>
</body>
</html>