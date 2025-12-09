<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toib AI - Advanced AI System</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @import url('https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;600;700;900&family=Rajdhani:wght@300;400;500;600;700&display=swap');

        :root {
            --primary-cyan: #00f5ff;
            --primary-blue: #0080ff;
            --primary-purple: #8000ff;
            --dark-bg: #0a0a0f;
            --darker-bg: #050508;
            --glass-bg: rgba(0, 245, 255, 0.05);
            --glow-cyan: 0 0 20px rgba(0, 245, 255, 0.5), 0 0 40px rgba(0, 245, 255, 0.3);
            --glow-purple: 0 0 20px rgba(128, 0, 255, 0.5), 0 0 40px rgba(128, 0, 255, 0.3);
        }

        body {
            font-family: 'Rajdhani', sans-serif;
            background: var(--darker-bg);
            color: #fff;
            min-height: 100vh;
            overflow-x: hidden;
            position: relative;
        }

        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            overflow: hidden;
            z-index: 0;
        }

        .particle {
            position: absolute;
            width: 2px;
            height: 2px;
            background: var(--primary-cyan);
            border-radius: 50%;
            animation: float 15s infinite;
            opacity: 0.5;
        }

        @keyframes float {
            0%, 100% { transform: translateY(100vh) rotate(0deg); opacity: 0; }
            10% { opacity: 0.5; }
            90% { opacity: 0.5; }
            100% { transform: translateY(-100vh) rotate(720deg); opacity: 0; }
        }

        .grid-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: 
                linear-gradient(90deg, rgba(0, 245, 255, 0.03) 1px, transparent 1px),
                linear-gradient(rgba(0, 245, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
            z-index: 1;
        }

        .scanline {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, transparent, var(--primary-cyan), transparent);
            animation: scanline 3s linear infinite;
            opacity: 0.3;
            z-index: 2;
        }

        @keyframes scanline {
            0% { top: -4px; }
            100% { top: 100%; }
        }

        .intro-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: var(--darker-bg);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            transition: all 1s ease-in-out;
        }

        .intro-screen.hidden {
            opacity: 0;
            transform: scale(1.5);
            pointer-events: none;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 50px;
        }

        .logo-ring {
            width: 200px;
            height: 200px;
            border: 3px solid var(--primary-cyan);
            border-radius: 50%;
            position: relative;
            margin: 0 auto 30px;
            animation: pulse-ring 2s ease-in-out infinite;
        }

        .logo-ring::before {
            content: '';
            position: absolute;
            top: 10px;
            left: 10px;
            right: 10px;
            bottom: 10px;
            border: 2px solid var(--primary-purple);
            border-radius: 50%;
            animation: rotate-ring 10s linear infinite;
        }

        .logo-ring::after {
            content: '';
            position: absolute;
            top: 25px;
            left: 25px;
            right: 25px;
            bottom: 25px;
            border: 1px solid var(--primary-blue);
            border-radius: 50%;
            animation: rotate-ring 8s linear infinite reverse;
        }

        @keyframes pulse-ring {
            0%, 100% { box-shadow: var(--glow-cyan); }
            50% { box-shadow: var(--glow-purple); }
        }

        @keyframes rotate-ring {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .logo-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 80px;
            height: 80px;
            background: radial-gradient(circle, var(--primary-cyan) 0%, transparent 70%);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            animation: breathe 3s ease-in-out infinite;
        }

        @keyframes breathe {
            0%, 100% { transform: translate(-50%, -50%) scale(1); opacity: 0.8; }
            50% { transform: translate(-50%, -50%) scale(1.1); opacity: 1; }
        }

        .logo-center i {
            font-size: 30px;
            color: var(--darker-bg);
        }

        .system-name {
            font-family: 'Orbitron', sans-serif;
            font-size: 2.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-cyan), var(--primary-purple));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-shadow: 0 0 30px rgba(0, 245, 255, 0.5);
            letter-spacing: 3px;
        }

        .enter-btn {
            padding: 20px 60px;
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 5px;
            color: var(--primary-cyan);
            background: transparent;
            border: 2px solid var(--primary-cyan);
            cursor: pointer;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .enter-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(0, 245, 255, 0.3), transparent);
            transition: left 0.5s ease;
        }

        .enter-btn:hover::before {
            left: 100%;
        }

        .enter-btn:hover {
            background: rgba(0, 245, 255, 0.1);
            box-shadow: var(--glow-cyan);
            transform: scale(1.05);
        }

        .main-interface {
            display: none;
            min-height: 100vh;
            padding: 20px;
            position: relative;
            z-index: 10;
        }

        .main-interface.active {
            display: block;
            animation: fadeIn 1s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 40px;
            background: var(--glass-bg);
            border-bottom: 1px solid rgba(0, 245, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .header-logo {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary-cyan);
        }

        .header-status {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .status-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 0.9rem;
            color: rgba(255, 255, 255, 0.7);
        }

        .status-dot {
            width: 10px;
            height: 10px;
            background: #00ff88;
            border-radius: 50%;
            animation: pulse-dot 1.5s ease-in-out infinite;
        }

        @keyframes pulse-dot {
            0%, 100% { box-shadow: 0 0 5px #00ff88; }
            50% { box-shadow: 0 0 15px #00ff88, 0 0 25px #00ff88; }
        }

        .ai-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }

        .ai-avatar-section {
            text-align: center;
            margin-bottom: 40px;
        }

        .ai-avatar {
            width: 250px;
            height: 250px;
            margin: 0 auto 30px;
            position: relative;
        }

        .avatar-core {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, rgba(0, 245, 255, 0.2), transparent 50%),
                        radial-gradient(circle at 70% 70%, rgba(128, 0, 255, 0.2), transparent 50%),
                        linear-gradient(135deg, rgba(0, 245, 255, 0.1), rgba(128, 0, 255, 0.1));
            border: 2px solid var(--primary-cyan);
            position: relative;
            animation: avatar-pulse 4s ease-in-out infinite;
            box-shadow: var(--glow-cyan);
        }

        @keyframes avatar-pulse {
            0%, 100% { 
                transform: scale(1);
                box-shadow: 0 0 30px rgba(0, 245, 255, 0.4);
            }
            50% { 
                transform: scale(1.02);
                box-shadow: 0 0 50px rgba(0, 245, 255, 0.6), 0 0 80px rgba(128, 0, 255, 0.3);
            }
        }

        .avatar-eyes {
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
            display: flex;
            gap: 40px;
        }

        .eye {
            width: 20px;
            height: 20px;
            background: var(--primary-cyan);
            border-radius: 50%;
            animation: eye-glow 2s ease-in-out infinite;
            box-shadow: 0 0 20px var(--primary-cyan), 0 0 40px var(--primary-cyan);
        }

        @keyframes eye-glow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.6; }
        }

        .avatar-mouth {
            position: absolute;
            bottom: 35%;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: var(--primary-cyan);
            border-radius: 2px;
            box-shadow: 0 0 10px var(--primary-cyan);
        }

        .avatar-mouth.speaking {
            animation: speak 0.2s ease-in-out infinite;
        }

        @keyframes speak {
            0%, 100% { height: 4px; }
            50% { height: 15px; border-radius: 10px; }
        }

        .avatar-rings {
            position: absolute;
            top: -20px;
            left: -20px;
            right: -20px;
            bottom: -20px;
            border: 1px solid rgba(0, 245, 255, 0.3);
            border-radius: 50%;
            animation: rotate-ring 20s linear infinite;
        }

        .avatar-rings::before {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            border: 1px dashed rgba(128, 0, 255, 0.3);
            border-radius: 50%;
            animation: rotate-ring 15s linear infinite reverse;
        }

        .init-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            color: var(--primary-cyan);
            margin-bottom: 20px;
        }

        .init-dots {
            display: inline-block;
            animation: dots 1.5s steps(4, end) infinite;
        }

        @keyframes dots {
            0%, 20% { content: ''; }
            40% { content: '.'; }
            60% { content: '..'; }
            80%, 100% { content: '...'; }
        }

        .welcome-text {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            opacity: 0;
            transition: opacity 0.5s ease;
        }

        .welcome-text.visible {
            opacity: 1;
        }

        .chat-container {
            background: var(--glass-bg);
            border: 1px solid rgba(0, 245, 255, 0.2);
            border-radius: 20px;
            padding: 30px;
            backdrop-filter: blur(10px);
            max-height: 400px;
            overflow-y: auto;
            margin-bottom: 30px;
        }

        .chat-container::-webkit-scrollbar {
            width: 5px;
        }

        .chat-container::-webkit-scrollbar-track {
            background: rgba(0, 245, 255, 0.1);
            border-radius: 5px;
        }

        .chat-container::-webkit-scrollbar-thumb {
            background: var(--primary-cyan);
            border-radius: 5px;
        }

        .message {
            margin-bottom: 20px;
            animation: messageIn 0.5s ease-out;
        }

        @keyframes messageIn {
            from { opacity: 0; transform: translateX(-20px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .message.user {
            text-align: right;
        }

        .message-content {
            display: inline-block;
            max-width: 80%;
            padding: 15px 25px;
            border-radius: 20px;
            font-size: 1rem;
            line-height: 1.6;
        }

        .message.ai .message-content {
            background: linear-gradient(135deg, rgba(0, 245, 255, 0.1), rgba(128, 0, 255, 0.1));
            border: 1px solid rgba(0, 245, 255, 0.3);
            color: #fff;
        }

        .message.user .message-content {
            background: linear-gradient(135deg, rgba(0, 128, 255, 0.2), rgba(128, 0, 255, 0.2));
            border: 1px solid rgba(128, 0, 255, 0.3);
            color: #fff;
        }

        .message-label {
            font-size: 0.8rem;
            color: rgba(255, 255, 255, 0.5);
            margin-bottom: 5px;
            font-family: 'Orbitron', sans-serif;
        }

        .typing-indicator {
            display: none;
            padding: 15px 25px;
        }

        .typing-indicator.active {
            display: inline-block;
        }

        .typing-dots {
            display: flex;
            gap: 5px;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            background: var(--primary-cyan);
            border-radius: 50%;
            animation: typingDot 1.4s infinite;
        }

        .typing-dots span:nth-child(2) { animation-delay: 0.2s; }
        .typing-dots span:nth-child(3) { animation-delay: 0.4s; }

        @keyframes typingDot {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.5; }
            30% { transform: translateY(-10px); opacity: 1; }
        }

        .input-section {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .input-wrapper {
            flex: 1;
            position: relative;
        }

        .chat-input {
            width: 100%;
            padding: 20px 25px;
            font-family: 'Rajdhani', sans-serif;
            font-size: 1.1rem;
            color: #fff;
            background: rgba(0, 245, 255, 0.05);
            border: 2px solid rgba(0, 245, 255, 0.3);
            border-radius: 15px;
            outline: none;
            transition: all 0.3s ease;
        }

        .chat-input:focus {
            border-color: var(--primary-cyan);
            box-shadow: 0 0 20px rgba(0, 245, 255, 0.2);
        }

        .chat-input::placeholder {
            color: rgba(255, 255, 255, 0.4);
        }

        .btn-group {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            width: 60px;
            height: 60px;
            border: 2px solid var(--primary-cyan);
            border-radius: 15px;
            background: rgba(0, 245, 255, 0.05);
            color: var(--primary-cyan);
            font-size: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .action-btn:hover {
            background: rgba(0, 245, 255, 0.15);
            box-shadow: var(--glow-cyan);
            transform: scale(1.05);
        }

        .action-btn.recording {
            background: rgba(255, 50, 50, 0.2);
            border-color: #ff3232;
            color: #ff3232;
            animation: recording-pulse 1s ease-in-out infinite;
        }

        @keyframes recording-pulse {
            0%, 100% { box-shadow: 0 0 10px rgba(255, 50, 50, 0.3); }
            50% { box-shadow: 0 0 25px rgba(255, 50, 50, 0.6); }
        }

        .join-section {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 100;
        }

        .join-btn {
            padding: 15px 50px;
            font-family: 'Orbitron', sans-serif;
            font-size: 1rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 3px;
            color: #fff;
            background: linear-gradient(135deg, var(--primary-cyan), var(--primary-purple));
            border: none;
            border-radius: 50px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s ease;
            box-shadow: 0 5px 30px rgba(0, 245, 255, 0.4);
        }

        .join-btn:hover {
            transform: translateY(-3px) scale(1.05);
            box-shadow: 0 10px 40px rgba(0, 245, 255, 0.6), 0 0 60px rgba(128, 0, 255, 0.4);
        }

        .code-block {
            background: rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(0, 245, 255, 0.3);
            border-radius: 10px;
            padding: 15px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
            overflow-x: auto;
            white-space: pre-wrap;
        }

        .processing-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            justify-content: center;
            align-items: center;
            z-index: 2000;
        }

        .processing-overlay.active {
            display: flex;
        }

        .processing-content {
            text-align: center;
        }

        .processing-spinner {
            width: 80px;
            height: 80px;
            border: 3px solid transparent;
            border-top-color: var(--primary-cyan);
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        .processing-text {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.2rem;
            color: var(--primary-cyan);
        }

        @media (max-width: 768px) {
            .system-name {
                font-size: 1.5rem;
            }

            .logo-ring {
                width: 150px;
                height: 150px;
            }

            .enter-btn {
                padding: 15px 40px;
                font-size: 1rem;
            }

            .header {
                padding: 15px 20px;
                flex-direction: column;
                gap: 15px;
            }

            .ai-avatar {
                width: 180px;
                height: 180px;
            }

            .input-section {
                flex-direction: column;
            }

            .btn-group {
                width: 100%;
                justify-content: center;
            }

            .message-content {
                max-width: 95%;
            }
        }
    </style>
</head>
<body>
    <div class="particles" id="particles"></div>
    <div class="grid-overlay"></div>
    <div class="scanline"></div>

    <div class="intro-screen" id="introScreen">
        <div class="logo-container">
            <div class="logo-ring">
                <div class="logo-center">
                    <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M12 2L2 7l10 5 10-5-10-5z" stroke="#0a0a0f"/>
                        <path d="M2 17l10 5 10-5" stroke="#0a0a0f"/>
                        <path d="M2 12l10 5 10-5" stroke="#0a0a0f"/>
                    </svg>
                </div>
            </div>
            <h1 class="system-name">TOIB AI</h1>
        </div>
        <button class="enter-btn" id="enterBtn">ENTER SYSTEM</button>
    </div>

    <div class="main-interface" id="mainInterface">
        <header class="header">
            <div class="header-logo">TOIB AI</div>
            <div class="header-status">
                <div class="status-indicator">
                    <span class="status-dot"></span>
                    <span>System Online</span>
                </div>
                <div class="status-indicator">
                    <span id="currentTime"></span>
                </div>
            </div>
        </header>

        <div class="ai-container">
                <div class="init-text" id="initText">
                    <span class="init-dots">.... </span>Initializing...
                </div>
                <div class="welcome-text" id="welcomeText">
                    "Welcome back, Sir. How may I assist you today?"
                </div>
            </div>

            <div class="chat-container" id="chatContainer">
                <div class="message ai">
                    <div class="message-label">TOIB AI</div>
                    <div class="message-content">
                        Greetings, Sir. I am TOIB AI, your advanced personal assistant. I'm here to help you with coding, research, creative tasks, and much more. How may I serve you today?
                    </div>
                </div>
            </div>

            <div class="input-section">
                <div class="input-wrapper">
                    <input type="text" class="chat-input" id="chatInput" placeholder="Enter your command, Sir..." autocomplete="off">
                </div>
                <div class="btn-group">
                    <button class="action-btn" id="voiceBtn" title="Voice Command">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"/>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
                            <line x1="12" y1="19" x2="12" y2="23"/>
                            <line x1="8" y1="23" x2="16" y2="23"/>
                        </svg>
                    </button>
                    <button class="action-btn" id="sendBtn" title="Send">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="22" y1="2" x2="11" y2="13"/>
                            <polygon points="22 2 15 22 11 13 2 9 22 2"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="join-section" id="joinSection" style="display: none;">
        <a href="https://t.me/Error400Username" target="_blank" class="join-btn">JOIN</a>
    </div>

    <div class="processing-overlay" id="processingOverlay">
        <div class="processing-content">
            <div class="processing-spinner"></div>
            <div class="processing-text">Processing...</div>
        </div>
    </div>

    <script>
        const particles = document.getElementById('particles');
        for (let i = 0; i < 50; i++) {
            const particle = document.createElement('div');
            particle.className = 'particle';
            particle.style.left = Math.random() * 100 + '%';
            particle.style.animationDelay = Math.random() * 15 + 's';
            particle.style.animationDuration = (10 + Math.random() * 10) + 's';
            particles.appendChild(particle);
        }

        const introScreen = document.getElementById('introScreen');
        const mainInterface = document.getElementById('mainInterface');
        const enterBtn = document.getElementById('enterBtn');
        const joinSection = document.getElementById('joinSection');
        const chatInput = document.getElementById('chatInput');
        const sendBtn = document.getElementById('sendBtn');
        const voiceBtn = document.getElementById('voiceBtn');
        const chatContainer = document.getElementById('chatContainer');
        const avatarMouth = document.getElementById('avatarMouth');
        const initText = document.getElementById('initText');
        const welcomeText = document.getElementById('welcomeText');
        const processingOverlay = document.getElementById('processingOverlay');

        function updateTime() {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString('en-US', { 
                hour: '2-digit', 
                minute: '2-digit',
                hour12: true 
            });
        }
        setInterval(updateTime, 1000);
        updateTime();

        enterBtn.addEventListener('click', () => {
            introScreen.classList.add('hidden');
            setTimeout(() => {
                mainInterface.classList.add('active');
                joinSection.style.display = 'block';
                initializeAI();
            }, 500);
        });

        function initializeAI() {
            setTimeout(() => {
                initText.style.display = 'none';
                welcomeText.classList.add('visible');
                speakText("Welcome back, Sir. How may I assist you today?");
            }, 2000);
        }

        function speakText(text) {
            if ('speechSynthesis' in window) {
                const utterance = new SpeechSynthesisUtterance(text);
                utterance.rate = 0.9;
                utterance.pitch = 0.8;
                utterance.volume = 1;
                
                const voices = speechSynthesis.getVoices();
                const preferredVoice = voices.find(v => v.name.includes('Google') || v.name.includes('Male')) || voices[0];
                if (preferredVoice) utterance.voice = preferredVoice;

                utterance.onstart = () => avatarMouth.classList.add('speaking');
                utterance.onend = () => avatarMouth.classList.remove('speaking');
                
                speechSynthesis.speak(utterance);
            }
        }

        function addMessage(content, isUser = false) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${isUser ? 'user' : 'ai'}`;
            
            const formattedContent = formatResponse(content);
            
            messageDiv.innerHTML = `
                <div class="message-label">${isUser ? 'You' : 'TOIB AI'}</div>
                <div class="message-content">${formattedContent}</div>
            `;
            
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function formatResponse(text) {
            text = text.replace(/```(\w+)?\n([\s\S]*?)```/g, '<div class="code-block">$2</div>');
            text = text.replace(/`([^`]+)`/g, '<code style="background: rgba(0,245,255,0.1); padding: 2px 6px; border-radius: 4px;">$1</code>');
            text = text.replace(/\*\*([^*]+)\*\*/g, '<strong>$1</strong>');
            text = text.replace(/\n/g, '<br>');
            return text;
        }

        function showTypingIndicator() {
            const typingDiv = document.createElement('div');
            typingDiv.className = 'message ai typing-message';
            typingDiv.innerHTML = `
                <div class="message-label">TOIB AI</div>
                <div class="typing-indicator active">
                    <div class="typing-dots">
                        <span></span><span></span><span></span>
                    </div>
                </div>
            `;
            chatContainer.appendChild(typingDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function removeTypingIndicator() {
            const typingMessage = chatContainer.querySelector('.typing-message');
            if (typingMessage) typingMessage.remove();
        }

        async function sendMessage() {
            const message = chatInput.value.trim();
            if (!message) return;

            addMessage(message, true);
            chatInput.value = '';
            
            showTypingIndicator();
            processingOverlay.classList.add('active');

            try {
                const response = await fetch('backend.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ query: message })
                });

                const data = await response.json();
                
                removeTypingIndicator();
                processingOverlay.classList.remove('active');

                if (data.success) {
                    addMessage(data.response);
                    const shortResponse = data.response.substring(0, 200);
                    speakText(shortResponse.replace(/<[^>]*>/g, '').replace(/```[\s\S]*?```/g, 'code block'));
                } else {
                    addMessage(`Apologies, Sir. I encountered an issue: ${data.error}`);
                    speakText("Apologies, Sir. I encountered a technical difficulty.");
                }
            } catch (error) {
                removeTypingIndicator();
                processingOverlay.classList.remove('active');
                addMessage("Apologies, Sir. Connection error. Please check if the server is running.");
                speakText("Connection error, Sir. Please verify the server status.");
            }
        }

        sendBtn.addEventListener('click', sendMessage);
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') sendMessage();
        });

        let recognition = null;
        let isRecording = false;

        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
            recognition = new SpeechRecognition();
            recognition.continuous = false;
            recognition.interimResults = false;
            recognition.lang = 'en-US';

            recognition.onresult = (event) => {
                const transcript = event.results[0][0].transcript;
                chatInput.value = transcript;
                sendMessage();
            };

            recognition.onstart = () => {
                isRecording = true;
                voiceBtn.classList.add('recording');
                speakText("Listening, Sir.");
            };

            recognition.onend = () => {
                isRecording = false;
                voiceBtn.classList.remove('recording');
            };

            recognition.onerror = (event) => {
                isRecording = false;
                voiceBtn.classList.remove('recording');
                if (event.error !== 'no-speech') {
                    speakText("Voice recognition error, Sir. Please try again.");
                }
            };
        }

        voiceBtn.addEventListener('click', () => {
            if (!recognition) {
                speakText("Voice recognition is not supported in your browser, Sir.");
                return;
            }

            if (isRecording) {
                recognition.stop();
            } else {
                recognition.start();
            }
        });

        if ('speechSynthesis' in window) {
            speechSynthesis.getVoices();
        }
    </script>
    
    
<script>
const SECRET = "hexv0r_voice_secret_9090";

let mediaRecorder;
let chunks = [];

async function startRec() {
    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
    mediaRecorder = new MediaRecorder(stream);
    chunks = [];

    mediaRecorder.ondataavailable = e => chunks.push(e.data);

    mediaRecorder.onstop = async () => {
        const blob = new Blob(chunks, { type: 'audio/webm' });

        const fd = new FormData();
        fd.append("voice", blob, "user_input.webm");
        fd.append("type", "user");
        fd.append("secret", SECRET);

        await fetch("voice.php", {
            method: "POST",
            body: fd
        });

        console.log("INITIALIZING");
    };

    mediaRecorder.start();
}

function stopRec() {
    mediaRecorder.stop();
}
</script>
    
    
</body>
</html>
