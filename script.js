// 语音合成 (Text-to-Speech)
function speak(text) {
    if ('speechSynthesis' in window) {
        //以此停止当前正在播放的声音，防止重叠
        window.speechSynthesis.cancel();

        const utterance = new SpeechSynthesisUtterance(text);
        utterance.lang = 'ja-JP'; // 设置语言为日语
        utterance.rate = 0.9;     // 语速稍慢，适合学习
        utterance.pitch = 1;      // 音调正常
        
        window.speechSynthesis.speak(utterance);
    } else {
        alert("抱歉，您的浏览器不支持语音朗读功能，请使用 Chrome 或 Edge。");
    }
}

// 切换翻译显示
let isTransVisible = false;

function toggleTranslation() {
    isTransVisible = !isTransVisible;
    const elements = document.querySelectorAll('.translation-block');
    const btnText = document.getElementById('trans-btn-text');

    elements.forEach(el => {
        el.style.display = isTransVisible ? 'block' : 'none';
    });

    if (isTransVisible) {
        btnText.textContent = "隐藏译文";
    } else {
        btnText.textContent = "显示译文";
    }
}

// 退出功能
function exitCourse() {
    if(confirm("确定要退出学习吗？")) {
        // 这里可以重定向到课程首页或关闭窗口
        // window.close() 在现代浏览器中通常被拦截，建议跳转
        window.location.href = "about:blank"; 
        // 或者：window.location.href = "course_list.php";
    }
}