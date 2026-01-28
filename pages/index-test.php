<?php
// index-test.php
// 严肃风格的EJU词汇最终测试页面
header('Content-Type: text/html; charset=utf-8');

// 1. PHP后端逻辑：自动读取并聚合数据
$dataDirectory = '../data/';
$vocabData = [];
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

// 转换为JS对象
$jsVocabJson = json_encode($vocabData, JSON_UNESCAPED_UNICODE);
?>
<!DOCTYPE html>
<html lang="ja-JP">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EJU日本語語彙 | 最終試練</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Shippori+Mincho:wght@400;500;700;800&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        samurai: {
                            dark: '#1a202c',     // 深炭色背景
                            panel: '#2d3748',    // 面板色
                            gold: '#d69e2e',     // 皇家金
                            goldLight: '#ecc94b',
                            crimson: '#c53030',  // 错误/警告红
                            paper: '#f7fafc',    // 纸白字
                            sub: '#a0aec0'       // 副标题灰
                        }
                    },
                    fontFamily: {
                        serif: ['"Shippori Mincho"', 'serif'], // 强制使用衬线体
                    },
                    backgroundImage: {
                        'pattern': "url('https://www.transparenttextures.com/patterns/black-scales.png')"
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            /* 庄重的按钮样式 */
            .btn-samurai {
                @apply w-full py-4 px-6 border border-samurai-gold/30 text-samurai-gold font-serif font-bold text-lg 
                bg-gradient-to-b from-gray-800 to-gray-900 
                hover:from-samurai-gold hover:to-yellow-600 hover:text-black hover:border-transparent
                transition-all duration-300 shadow-lg tracking-widest relative overflow-hidden;
            }
            .btn-samurai::before {
                content: '';
                @apply absolute top-0 left-0 w-1 h-full bg-samurai-gold transition-all duration-300;
            }
            .btn-samurai:hover::before {
                @apply w-full opacity-20;
            }
            
            /* 选项按钮 */
            .option-card {
                @apply w-full text-left p-5 border border-gray-600 bg-gray-800/50 text-gray-300 
                hover:bg-gray-700 hover:border-samurai-gold/50 hover:text-samurai-goldLight
                transition-all duration-200 font-serif text-lg tracking-wide;
            }
            .option-card.selected {
                @apply bg-samurai-gold text-black border-samurai-gold font-bold;
            }

            /* 错题回顾模式下的反馈样式 */
            .option-card.review-correct {
                @apply bg-green-800 border-green-500 text-white !important;
            }
            .option-card.review-wrong {
                @apply bg-red-900 border-red-500 text-white !important;
            }
        }
        body {
            background-color: #111;
        }
    </style>
</head>
<body class="min-h-screen text-samurai-paper font-serif bg-samurai-dark bg-pattern flex flex-col items-center relative overflow-x-hidden">

    <nav class="w-full max-w-5xl mx-auto flex justify-between items-center p-6 border-b border-gray-700 relative z-20">
        <div class="flex items-center space-x-3">
            <i class="fas fa-torii-gate text-3xl text-samurai-gold"></i>
            <div>
                <h1 class="text-xl font-bold tracking-[0.2em] text-samurai-gold">最終試練</h1>
                <p class="text-xs text-samurai-sub uppercase">EJU Vocabulary Final Exam</p>
            </div>
        </div>
        <a href="../index.php" class="text-samurai-sub hover:text-white transition-colors flex items-center gap-2 text-sm border border-gray-700 px-4 py-2 rounded">
            <i class="fas fa-times"></i> 終了テスト
        </a>
    </nav>

    <main class="flex-grow w-full max-w-3xl flex flex-col justify-center items-center p-4 relative z-10 min-h-[600px]">

        <div id="view-intro" class="text-center w-full animate-fade-in">
            <div class="mb-8 relative inline-block">
                <div class="absolute inset-0 bg-samurai-gold blur-2xl opacity-20"></div>
                <i class="fas fa-scroll text-8xl text-samurai-gold mb-6 relative z-10"></i>
            </div>
            
            <h2 class="text-4xl md:text-5xl font-black mb-6 tracking-widest leading-tight">
                覚悟は<br>できていますか
            </h2>
            
            <div class="bg-gray-800/80 p-6 border-l-4 border-samurai-gold text-left max-w-lg mx-auto mb-10 shadow-2xl">
                <ul class="space-y-4 text-gray-300">
                    <li class="flex items-start"><i class="fas fa-check text-samurai-gold mt-1 mr-3"></i> <span>出題数は<strong>50問</strong>です。</span></li>
                    <li class="flex items-start"><i class="fas fa-check text-samurai-gold mt-1 mr-3"></i> <span>途中での正誤判定はありません。</span></li>
                    <li class="flex items-start"><i class="fas fa-check text-samurai-gold mt-1 mr-3"></i> <span>二つの出題形式がランダムに現れます。</span></li>
                    <li class="flex items-start"><i class="fas fa-check text-samurai-gold mt-1 mr-3"></i> <span>不合格の場合、<strong>追試（リベンジ）</strong>が行われます。</span></li>
                </ul>
            </div>

            <button onclick="startExam()" class="btn-samurai max-w-xs mx-auto text-2xl">
                試練開始
            </button>
        </div>

        <div id="view-quiz" class="w-full hidden">
            <div class="w-full h-1 bg-gray-800 mb-8 relative">
                <div id="progress-bar" class="h-full bg-samurai-gold transition-all duration-500" style="width: 0%"></div>
                <div class="absolute right-0 -top-6 text-sm text-samurai-gold font-mono">
                    <span id="q-current">1</span> / <span id="q-total">50</span>
                </div>
            </div>

            <div class="bg-gray-900 border border-gray-700 p-8 md:p-12 shadow-2xl relative mb-6">
                <div class="absolute top-0 left-0 w-4 h-4 border-t-2 border-l-2 border-samurai-gold"></div>
                <div class="absolute top-0 right-0 w-4 h-4 border-t-2 border-r-2 border-samurai-gold"></div>
                <div class="absolute bottom-0 left-0 w-4 h-4 border-b-2 border-l-2 border-samurai-gold"></div>
                <div class="absolute bottom-0 right-0 w-4 h-4 border-b-2 border-r-2 border-samurai-gold"></div>

                <div class="text-center">
                    <span id="q-type" class="inline-block px-3 py-1 border border-gray-600 text-gray-400 text-xs tracking-widest mb-4">
                        </span>
                    <h3 id="q-text" class="text-4xl md:text-5xl font-bold mb-4 text-white">
                        </h3>
                    <p id="q-sub" class="text-xl text-samurai-gold h-8">
                        </p>
                </div>
            </div>

            <div id="option-container" class="grid grid-cols-1 gap-4">
                </div>
        </div>

        <div id="view-result" class="w-full text-center hidden">
            <h2 class="text-2xl text-gray-400 tracking-widest mb-2">試練終了</h2>
            <div class="text-6xl md:text-8xl font-black text-samurai-gold mb-4 text-shadow-gold" id="score-display">
                0
            </div>
            <div class="text-xl mb-8">
                <span class="text-gray-500">RANK:</span> 
                <span id="rank-display" class="text-3xl font-bold ml-2 text-white"></span>
            </div>

            <p id="feedback-msg" class="text-lg text-gray-300 mb-10 italic border-t border-b border-gray-800 py-4 max-w-lg mx-auto">
                </p>

            <div id="mistake-alert" class="hidden mb-8 bg-red-900/20 border border-red-900/50 p-6 rounded">
                <p class="text-samurai-crimson text-lg font-bold mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>未熟な点が見つかりました
                </p>
                <p class="text-gray-400 text-sm mb-4">
                    <span id="mistake-count">0</span> 問の誤答があります。<br>完璧にするまで、帰ることは許されません。
                </p>
                <button onclick="startRetry()" class="px-8 py-3 bg-samurai-crimson hover:bg-red-600 text-white font-bold tracking-widest transition-colors shadow-lg animate-pulse">
                    <i class="fas fa-fire mr-2"></i> 追試を受ける (复习错题)
                </button>
            </div>

            <div id="perfect-alert" class="hidden mb-12">
                <button onclick="location.reload()" class="btn-samurai max-w-xs mx-auto">
                    新たな試練へ
                </button>
            </div>
        </div>

    </main>

    <div class="fixed inset-0 pointer-events-none z-0 opacity-10" style="background-image: radial-gradient(circle, #d69e2e 1px, transparent 1px); background-size: 40px 40px;"></div>

    <script>
        // --- 1. 数据准备 ---
        const rawData = <?php echo $jsVocabJson; ?>;
        const EXAM_SIZE = 50;

        // 状态变量
        let examState = {
            questions: [],
            currentIndex: 0,
            score: 0,
            mistakes: [], // 存储错题对象
            mode: 'exam'  // 'exam' (正常考试) 或 'retry' (错题重练)
        };

        // 洗牌算法
        function shuffle(array) {
            let m = array.length, t, i;
            while (m) {
                i = Math.floor(Math.random() * m--);
                t = array[m];
                array[m] = array[i];
                array[i] = t;
            }
            return array;
        }

        // 获取干扰项
        function getDistractors(correctItem, type, count = 3) {
            let distractors = [];
            while(distractors.length < count) {
                let rand = rawData[Math.floor(Math.random() * rawData.length)];
                let val = (type === 'meaning') ? rand.meaning_cn : rand.word;
                let correctVal = (type === 'meaning') ? correctItem.meaning_cn : correctItem.word;
                
                if(val !== correctVal && !distractors.includes(val) && val) {
                    distractors.push(val);
                }
            }
            return distractors;
        }

        // 生成试卷
        function generateExamSet(sourceData, count) {
            if (sourceData.length < count) count = sourceData.length;
            const shuffled = shuffle([...sourceData]);
            const selected = shuffled.slice(0, count);

            return selected.map(item => {
                // 随机决定题型：
                // Type 1: Word -> Meaning (正向)
                // Type 2: Meaning -> Word (反向)
                const isType1 = Math.random() > 0.5;
                
                let q = {
                    originalItem: item, // 保存原始数据用于复习
                    typeLabel: isType1 ? '意味選択' : '語彙想起',
                    questionText: isType1 ? item.word : item.meaning_cn,
                    subText: isType1 ? (item.reading || '') : '以下の言葉を選べ',
                    correctAnswer: isType1 ? item.meaning_cn : item.word,
                    userSelected: null
                };

                // 生成选项
                const distType = isType1 ? 'meaning' : 'word';
                const options = getDistractors(item, distType, 3);
                options.push(q.correctAnswer);
                q.options = shuffle(options);

                return q;
            });
        }

        // --- 2. 交互逻辑 ---

        function startExam() {
            if (!rawData || rawData.length === 0) {
                alert("データ読み込みエラー");
                return;
            }
            
            examState.mode = 'exam';
            examState.questions = generateExamSet(rawData, EXAM_SIZE);
            examState.currentIndex = 0;
            examState.score = 0;
            examState.mistakes = [];

            switchView('view-quiz');
            renderQuestion();
        }

        function switchView(id) {
            ['view-intro', 'view-quiz', 'view-result'].forEach(v => {
                document.getElementById(v).classList.add('hidden');
            });
            document.getElementById(id).classList.remove('hidden');
        }

        function renderQuestion() {
            const q = examState.questions[examState.currentIndex];
            const isRetry = examState.mode === 'retry';

            // UI更新
            document.getElementById('q-current').innerText = examState.currentIndex + 1;
            document.getElementById('q-total').innerText = examState.questions.length;
            document.getElementById('progress-bar').style.width = ((examState.currentIndex) / examState.questions.length * 100) + '%';

            document.getElementById('q-type').innerText = q.typeLabel + (isRetry ? ' (再挑戦)' : '');
            document.getElementById('q-text').innerText = q.questionText;
            document.getElementById('q-sub').innerText = q.subText;

            // 渲染选项
            const container = document.getElementById('option-container');
            container.innerHTML = '';

            q.options.forEach(opt => {
                const btn = document.createElement('button');
                btn.className = 'option-card';
                btn.innerText = opt;
                btn.onclick = () => handleAnswer(btn, opt, q);
                container.appendChild(btn);
            });
        }

        function handleAnswer(btn, selectedValue, questionObj) {
            // 防止重复点击
            const allBtns = document.querySelectorAll('.option-card');
            allBtns.forEach(b => b.disabled = true);

            const isCorrect = (selectedValue === questionObj.correctAnswer);

            if (examState.mode === 'exam') {
                // --- 考试模式 (不提示对错，直接下一题) ---
                btn.classList.add('selected'); // 仅显示选中状态
                
                if (isCorrect) {
                    examState.score++;
                } else {
                    examState.mistakes.push(questionObj);
                }

                setTimeout(() => {
                    nextQuestion();
                }, 300); // 快速切换

            } else {
                // --- 追试/复习模式 (必须提示对错，以此学习) ---
                if (isCorrect) {
                    btn.classList.add('review-correct');
                    // 播放一个清脆的音效或视觉提示
                } else {
                    btn.classList.add('review-wrong');
                    // 高亮正确答案
                    allBtns.forEach(b => {
                        if (b.innerText === questionObj.correctAnswer) b.classList.add('review-correct');
                    });
                }
                
                // 复习模式下，稍微停顿让用户看清正确答案
                setTimeout(() => {
                    nextQuestion();
                }, 1500);
            }
        }

        function nextQuestion() {
            examState.currentIndex++;
            if (examState.currentIndex < examState.questions.length) {
                renderQuestion();
            } else {
                finishExam();
            }
        }

        function finishExam() {
            if (examState.mode === 'retry') {
                // 复习结束，回到主页或重置
                alert("追試完了。よく頑張りました。");
                location.reload(); // 简单重置
                return;
            }

            // --- 考试结束结算 ---
            switchView('view-result');
            
            const total = examState.questions.length;
            const score = examState.score;
            const percentage = (score / total) * 100;
            
            // 动画显示分数
            document.getElementById('score-display').innerText = score + " / " + total;

            // 评级系统
            const rankEl = document.getElementById('rank-display');
            const msgEl = document.getElementById('feedback-msg');
            let rank = '', msg = '';

            if (percentage >= 95) {
                rank = '特級 (S)';
                rankEl.className = 'text-3xl font-bold ml-2 text-samurai-gold';
                msg = "素晴らしい。神の如き語彙力です。もはや教えることはありません。";
            } else if (percentage >= 80) {
                rank = '柱 (A)';
                rankEl.className = 'text-3xl font-bold ml-2 text-purple-400';
                msg = "お見事。かなりの実力者です。EJUの高得点は間違いないでしょう。";
            } else if (percentage >= 60) {
                rank = '上忍 (B)';
                rankEl.className = 'text-3xl font-bold ml-2 text-blue-400';
                msg = "合格圏内です。しかし、慢心は禁物。さらなる高みを目指しましょう。";
            } else {
                rank = '見習い (C)';
                rankEl.className = 'text-3xl font-bold ml-2 text-gray-500';
                msg = "まだ修行が足りません。基礎を固め直す必要があります。";
            }

            rankEl.innerText = rank;
            msgEl.innerText = msg;

            // 检查错题
            const mistakeCount = examState.mistakes.length;
            if (mistakeCount > 0) {
                document.getElementById('mistake-alert').classList.remove('hidden');
                document.getElementById('perfect-alert').classList.add('hidden');
                document.getElementById('mistake-count').innerText = mistakeCount;
            } else {
                document.getElementById('mistake-alert').classList.add('hidden');
                document.getElementById('perfect-alert').classList.remove('hidden');
            }
        }

        // 启动复习模式
        function startRetry() {
            if (examState.mistakes.length === 0) return;

            examState.mode = 'retry';
            // 将错题列表重新构建为题目队列
            // 注意：这里我们直接使用之前保存的错题对象，但要打乱顺序
            examState.questions = shuffle([...examState.mistakes]);
            examState.currentIndex = 0;
            
            // UI 变更
            document.getElementById('view-result').classList.add('hidden');
            document.getElementById('view-quiz').classList.remove('hidden');
            
            renderQuestion();
        }

    </script>
</body>
</html>