<?php
// index-exercise.php
// 位于 pages/ 目录下，负责读取 ../data/ 目录下的所有JSON数据并生成练习
header('Content-Type: text/html; charset=utf-8');

// 1. PHP后端逻辑：自动聚合所有词汇数据
$dataDirectory = '../data/';
$vocabData = [];

// 扫描目录下所有 data*.json 文件
$files = glob($dataDirectory . 'data*.json');

if ($files) {
    foreach ($files as $file) {
        $jsonContent = file_get_contents($file);
        $data = json_decode($jsonContent, true);
        if ($data) {
            $vocabData = array_merge($vocabData, $data);
        }
    }
}

// 将PHP数组转换为JS对象，以便前端进行游戏逻辑处理
$jsVocabJson = json_encode($vocabData, JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="ja-JP">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJU日本語語彙練習 | 挑戦モード</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=M+PLUS+Rounded+1c:wght@400;700;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        anime: {
                            bg: '#E0F7FA',       // 浅蓝背景
                            card: '#FFFFFF',     // 卡片白
                            primary: '#4DD0E1',  // 主色调
                            accent: '#FF7043',   // 强调色 (橙)
                            correct: '#66BB6A',  // 正确绿
                            wrong: '#EF5350',    // 错误红
                            text: '#37474F'      // 深灰字
                        }
                    },
                    fontFamily: {
                        jp: ['"M PLUS Rounded 1c"', 'sans-serif'],
                    },
                    animation: {
                        'bounce-sm': 'bounce 2s infinite',
                        'pop-in': 'popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards',
                        'shake': 'shake 0.5s cubic-bezier(.36,.07,.19,.97) both',
                    },
                    keyframes: {
                        popIn: {
                            '0%': { opacity: '0', transform: 'scale(0.5)' },
                            '100%': { opacity: '1', transform: 'scale(1)' },
                        },
                        shake: {
                            '10%, 90%': { transform: 'translate3d(-1px, 0, 0)' },
                            '20%, 80%': { transform: 'translate3d(2px, 0, 0)' },
                            '30%, 50%, 70%': { transform: 'translate3d(-4px, 0, 0)' },
                            '40%, 60%': { transform: 'translate3d(4px, 0, 0)' }
                        }
                    }
                }
            }
        }
    </script>

    <style type="text/tailwindcss">
        @layer utilities {
            .btn-option {
                @apply w-full text-left p-4 rounded-xl border-2 border-gray-100 bg-white text-lg font-bold text-gray-600 shadow-sm transition-all duration-200 hover:border-anime-primary hover:text-anime-primary hover:shadow-md active:scale-95;
            }
            .btn-option.correct {
                @apply border-anime-correct bg-green-50 text-anime-correct pointer-events-none;
            }
            .btn-option.wrong {
                @apply border-anime-wrong bg-red-50 text-anime-wrong pointer-events-none;
            }
            .btn-option.disabled {
                @apply opacity-50 pointer-events-none;
            }
            .glass-panel {
                @apply bg-white/90 backdrop-blur-lg border border-white/50 shadow-xl rounded-3xl;
            }
        }
        
        body {
            background-image: radial-gradient(#4DD0E1 1px, transparent 1px), radial-gradient(#4DD0E1 1px, transparent 1px);
            background-position: 0 0, 25px 25px;
            background-size: 50px 50px;
            background-color: #E0F7FA;
        }
    </style>
</head>
<body class="min-h-screen font-jp flex items-center justify-center p-4 relative overflow-hidden">

    <div class="absolute top-4 right-4 md:top-8 md:right-8 z-50">
        <a href="../index.php" class="inline-flex items-center px-4 py-2 bg-white text-anime-wrong border-2 border-anime-wrong rounded-full font-bold shadow-lg hover:bg-anime-wrong hover:text-white transition-colors duration-300">
            <i class="fas fa-sign-out-alt mr-2"></i> 練習を終了する
        </a>
    </div>

    <div class="absolute bottom-0 left-0 text-anime-primary/20 text-9xl transform -translate-x-10 translate-y-10 animate-bounce-sm">
        <i class="fas fa-gamepad"></i>
    </div>
    <div class="absolute top-10 left-10 text-anime-accent/20 text-8xl transform rotate-12">
        <i class="fas fa-star"></i>
    </div>

    <div class="w-full max-w-2xl relative z-10">
        
        <div id="scene-setup" class="glass-panel p-8 md:p-12 text-center animate-pop-in">
            <div class="mb-6 inline-block bg-anime-primary text-white px-4 py-1 rounded-full text-sm font-bold tracking-wider">
                EJU VOCABULARY CHALLENGE
            </div>
            <h1 class="text-4xl md:text-5xl font-black text-anime-text mb-2">語彙練習</h1>
            <p class="text-gray-500 mb-8 font-bold">今日の特訓を始めましょう！</p>

            <div class="space-y-6">
                <div class="relative">
                    <label class="block text-left text-gray-600 font-bold mb-2 ml-1">問題数を選択 (题目数量)</label>
                    <div class="relative">
                        <select id="question-count" class="w-full appearance-none bg-gray-50 border-2 border-gray-200 text-gray-700 py-4 px-6 pr-8 rounded-2xl leading-tight focus:outline-none focus:bg-white focus:border-anime-primary font-bold text-xl cursor-pointer transition-colors">
                            <option value="10">⚡ 10問 (快速练习)</option>
                            <option value="30">🔥 30問 (标准特训)</option>
                            <option value="50">👑 50問 (地狱模式)</option>
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-6 text-gray-700">
                            <i class="fas fa-chevron-down"></i>
                        </div>
                    </div>
                </div>

                <button onclick="startQuiz()" class="w-full py-5 bg-gradient-to-r from-anime-primary to-blue-400 text-white font-black text-2xl rounded-2xl shadow-lg shadow-anime-primary/40 hover:scale-[1.02] active:scale-95 transition-all duration-300">
                    <i class="fas fa-play mr-2"></i> スタート！
                </button>
            </div>
            
            <p class="mt-6 text-xs text-gray-400 font-bold">
                * データベースからランダムに出題されます
            </p>
        </div>

        <div id="scene-quiz" class="glass-panel p-6 md:p-8 hidden">
            <div class="flex justify-between items-center mb-4 text-sm font-bold text-gray-500">
                <span>QUESTION <span id="current-q-num" class="text-anime-primary text-xl">1</span></span>
                <span>SCORE: <span id="current-score" class="text-anime-accent text-xl">0</span></span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3 mb-8 overflow-hidden">
                <div id="progress-bar" class="bg-anime-primary h-3 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
            </div>

            <div class="text-center mb-8 relative min-h-[120px] flex flex-col justify-center items-center">
                <span id="q-type-badge" class="absolute -top-2 bg-gray-100 text-gray-500 text-xs px-3 py-1 rounded-full font-bold">
                    意味を選んでください
                </span>
                <h2 id="q-text" class="text-4xl md:text-5xl font-black text-anime-text break-words w-full">
                    </h2>
                <p id="q-sub" class="text-xl text-gray-400 mt-2 font-bold min-h-[1.75rem]">
                    </p>
            </div>

            <div id="options-container" class="grid grid-cols-1 gap-4 mb-4">
                </div>

            <div id="feedback-overlay" class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-0 transition-opacity duration-300 z-20">
                <i id="feedback-icon" class="fas fa-circle-notch text-9xl"></i>
            </div>
        </div>

        <div id="scene-result" class="glass-panel p-8 text-center hidden animate-pop-in">
            <div class="text-6xl mb-4" id="result-emoji">🎉</div>
            <h2 class="text-4xl font-black text-anime-text mb-2">お疲れ様でした！</h2>
            <p class="text-gray-500 font-bold mb-8">本次练习结果</p>

            <div class="bg-anime-bg rounded-2xl p-6 mb-8 border-2 border-anime-primary/20">
                <div class="text-sm text-gray-500 font-bold uppercase tracking-widest mb-1">Total Score</div>
                <div class="text-6xl font-black text-anime-primary">
                    <span id="final-score">80</span><span class="text-2xl text-gray-400">/100</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-8">
                <button onclick="location.reload()" class="py-4 bg-gray-100 text-gray-600 font-bold rounded-xl hover:bg-gray-200 transition-colors">
                    <i class="fas fa-redo mr-2"></i> もう一度
                </button>
                <button onclick="window.location.href='../index.php'" class="py-4 bg-anime-primary text-white font-bold rounded-xl shadow-lg shadow-anime-primary/30 hover:bg-anime-primary/90 transition-colors">
                    <i class="fas fa-home mr-2"></i> ホームへ
                </button>
            </div>
        </div>

    </div>

    <script>
        // 1. 从PHP接收数据
        const rawData = <?php echo $jsVocabJson; ?>;
        
        // 游戏状态变量
        let gameState = {
            totalQuestions: 10,
            currentIndex: 0,
            score: 0,
            quizQueue: [], // 存储生成的题目对象
            isAnswering: false
        };

        // 辅助函数：随机洗牌
        function shuffleArray(array) {
            for (let i = array.length - 1; i > 0; i--) {
                const j = Math.floor(Math.random() * (i + 1));
                [array[i], array[j]] = [array[j], array[i]];
            }
            return array;
        }

        // 辅助函数：获取随机干扰项 (excludeText用于排除正确答案)
        function getRandomDistractors(count, type, excludeText) {
            let distractors = [];
            let safetyCounter = 0;
            while(distractors.length < count && safetyCounter < 1000) {
                let randomItem = rawData[Math.floor(Math.random() * rawData.length)];
                let text = (type === 'meaning') ? randomItem.meaning_cn : 
                           (type === 'reading') ? randomItem.reading : randomItem.word;
                
                // 确保不重复且不是正确答案
                if(text !== excludeText && !distractors.includes(text) && text) {
                    distractors.push(text);
                }
                safetyCounter++;
            }
            return distractors;
        }

        // 2. 初始化题库引擎
        function generateQuiz(count) {
            // 先打乱所有词汇
            const shuffledVocab = shuffleArray([...rawData]);
            // 截取前N个作为题目
            const selectedVocab = shuffledVocab.slice(0, count);
            
            return selectedVocab.map(item => {
                // 随机决定题型：
                // 1. 视词选义 (Word -> Meaning)
                // 2. 视义选词 (Meaning -> Word)
                // 3. 读音测试 (Word -> Reading) (如果有假名)
                
                let qType = Math.floor(Math.random() * 3);
                // 简单处理：如果没有读音数据，强制转为视词选义
                if(qType === 2 && !item.reading) qType = 0;

                let question = {};
                
                if (qType === 0) { // Word -> Meaning
                    question = {
                        type: 'meaning',
                        text: item.word,
                        sub: item.reading || '', // 提示读音
                        correctAnswer: item.meaning_cn,
                        badge: '意味を選んでください (请选择含义)'
                    };
                    question.options = shuffleArray([item.meaning_cn, ...getRandomDistractors(3, 'meaning', item.meaning_cn)]);
                } else if (qType === 1) { // Meaning -> Word
                    question = {
                        type: 'word',
                        text: item.meaning_cn,
                        sub: '', 
                        correctAnswer: item.word,
                        badge: '言葉を選んでください (请选择词汇)'
                    };
                    question.options = shuffleArray([item.word, ...getRandomDistractors(3, 'word', item.word)]);
                } else { // Word -> Reading
                    question = {
                        type: 'reading',
                        text: item.word,
                        sub: '?',
                        correctAnswer: item.reading,
                        badge: '読み方を選んでください (请选择读音)'
                    };
                    question.options = shuffleArray([item.reading, ...getRandomDistractors(3, 'reading', item.reading)]);
                }
                return question;
            });
        }

        // 3. 界面交互逻辑
        function startQuiz() {
            const count = parseInt(document.getElementById('question-count').value);
            
            // 数据检查
            if (!rawData || rawData.length < 4) {
                alert("データが不足しています。(数据不足，无法生成练习)");
                return;
            }

            // 初始化状态
            gameState.totalQuestions = Math.min(count, rawData.length);
            gameState.currentIndex = 0;
            gameState.score = 0;
            gameState.quizQueue = generateQuiz(gameState.totalQuestions);
            
            // 切换场景
            document.getElementById('scene-setup').classList.add('hidden');
            document.getElementById('scene-quiz').classList.remove('hidden');
            document.getElementById('scene-quiz').classList.add('animate-pop-in');

            renderQuestion();
        }

        function renderQuestion() {
            gameState.isAnswering = false;
            const q = gameState.quizQueue[gameState.currentIndex];
            
            // 更新UI文本
            document.getElementById('current-q-num').innerText = gameState.currentIndex + 1;
            document.getElementById('current-score').innerText = gameState.score;
            document.getElementById('q-text').innerText = q.text;
            document.getElementById('q-sub').innerText = q.sub;
            document.getElementById('q-type-badge').innerText = q.badge;
            
            // 更新进度条
            const progress = (gameState.currentIndex / gameState.totalQuestions) * 100;
            document.getElementById('progress-bar').style.width = `${progress}%`;

            // 生成选项
            const container = document.getElementById('options-container');
            container.innerHTML = ''; // 清空

            q.options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'btn-option';
                btn.innerHTML = `<i class="far fa-circle text-gray-300 mr-3"></i>${opt}`;
                btn.onclick = () => checkAnswer(btn, opt, q.correctAnswer);
                container.appendChild(btn);
            });

            // 隐藏反馈层
            document.getElementById('feedback-overlay').classList.add('opacity-0');
        }

        function checkAnswer(btnElement, selected, correct) {
            if (gameState.isAnswering) return;
            gameState.isAnswering = true;

            const feedbackOverlay = document.getElementById('feedback-overlay');
            const feedbackIcon = document.getElementById('feedback-icon');

            // 禁用所有按钮
            const allBtns = document.querySelectorAll('.btn-option');
            allBtns.forEach(b => b.classList.add('disabled'));

            if (selected === correct) {
                // 正确
                btnElement.classList.add('correct');
                btnElement.querySelector('i').className = 'fas fa-check-circle mr-3';
                gameState.score += 10; // 每题10分 (此处仅用于展示，最后会重算)
                
                // 视觉反馈
                feedbackIcon.className = 'far fa-circle text-9xl text-anime-correct animate-ping';
            } else {
                // 错误
                btnElement.classList.add('wrong', 'animate-shake');
                btnElement.querySelector('i').className = 'fas fa-times-circle mr-3';
                
                // 高亮正确答案
                allBtns.forEach(b => {
                    if (b.innerText.trim() === correct) {
                        b.classList.remove('disabled'); // 保持清晰度
                        b.classList.add('correct');
                        b.querySelector('i').className = 'fas fa-check-circle mr-3';
                    }
                });

                // 视觉反馈
                feedbackIcon.className = 'fas fa-times text-9xl text-anime-wrong animate-bounce';
            }

            // 显示反馈图标
            feedbackOverlay.classList.remove('opacity-0');

            // 延迟进入下一题
            setTimeout(() => {
                gameState.currentIndex++;
                if (gameState.currentIndex < gameState.totalQuestions) {
                    renderQuestion();
                } else {
                    showResult();
                }
            }, 1500);
        }

        function showResult() {
            document.getElementById('scene-quiz').classList.add('hidden');
            document.getElementById('scene-result').classList.remove('hidden');
            
            // 计算百分制分数
            const finalScore = Math.round((gameState.score / (gameState.totalQuestions * 10)) * 100);
            
            // 动画数字增长
            let currentDisplay = 0;
            const scoreElement = document.getElementById('final-score');
            const timer = setInterval(() => {
                currentDisplay += 1;
                scoreElement.innerText = currentDisplay;
                if (currentDisplay >= finalScore) clearInterval(timer);
            }, 20);

            // 评价 Emoji
            const emojiEl = document.getElementById('result-emoji');
            if (finalScore === 100) emojiEl.innerText = '🏆';
            else if (finalScore >= 80) emojiEl.innerText = '😎';
            else if (finalScore >= 60) emojiEl.innerText = '🙂';
            else emojiEl.innerText = '🥹';
        }
    </script>
</body>
</html>