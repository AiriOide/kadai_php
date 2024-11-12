$(document).ready(function() {
    const chatForm = $('#chat-form');
    const userMessageInput = $('#user-message');
    const chatContainer = $('#chat-container');
    const voiceInputBtn = $('#voice-input-btn');
    const textToSpeechBtn = $('#text-to-speech-btn');
    const emotionAnalysis = $('#emotion-analysis');
    let isListening = false;
    let isSpeaking = false;

    chatForm.on('submit', function(e) {
        e.preventDefault();
        sendMessage();
    });

    function sendMessage() {
        const userMessage = userMessageInput.val().trim();
        if (userMessage) {
            appendMessage('user', userMessage);
            $.ajax({
                url: 'chat.php',
                method: 'POST',
                data: { user_message: userMessage },
                success: function(response) {
                    appendMessage('ai', response);
                    analyzeEmotion(userMessage);
                    speakMessage(response);
                }
            });
            userMessageInput.val('');
        }
    }

    function appendMessage(role, content) {
        const messageClass = role === 'user' ? 'user-message' : 'ai-message';
        chatContainer.append(`<div class="message ${messageClass}">${content}</div>`);
        chatContainer.scrollTop(chatContainer[0].scrollHeight);
    }

    function analyzeEmotion(message) {
        $.ajax({
            url: 'analyze_emotion.php',
            method: 'POST',
            data: { message: message },
            dataType: 'json',
            success: function(data) {
                const emotionText = `感情分析: ${data.type} (強度: ${data.intensity.toFixed(2)})`;
                emotionAnalysis.text(emotionText);
                emotionAnalysis.removeClass('positive negative neutral').addClass(data.type);
            }
        });
    }

    // 音声入力機能
    voiceInputBtn.on('click', function() {
        if ('webkitSpeechRecognition' in window) {
            const recognition = new webkitSpeechRecognition();
            recognition.lang = 'ja-JP';
            recognition.interimResults = false;
            recognition.maxAlternatives = 1;

            if (!isListening) {
                recognition.start();
                isListening = true;
                voiceInputBtn.text('音声入力停止');
            } else {
                recognition.stop();
                isListening = false;
                voiceInputBtn.text('音声入力');
            }

            recognition.onresult = function(event) {
                const transcript = event.results[0][0].transcript;
                userMessageInput.val(transcript);
            };

            recognition.onend = function() {
                isListening = false;
                voiceInputBtn.text('音声入力');
            };
        } else {
            alert('お使いのブラウザは音声認識をサポートしていません。');
        }
    });

    // 音声合成機能
    function speakMessage(text) {
        if ('speechSynthesis' in window) {
            const utterance = new SpeechSynthesisUtterance(text);
            utterance.lang = 'ja-JP';
            speechSynthesis.speak(utterance);
        }
    }

    textToSpeechBtn.on('click', function() {
        if (!isSpeaking) {
            const lastAiMessage = $('.ai-message').last().text();
            if (lastAiMessage) {
                speakMessage(lastAiMessage);
                isSpeaking = true;
                textToSpeechBtn.text('音声停止');
            }
        } else {
            speechSynthesis.cancel();
            isSpeaking = false;
            textToSpeechBtn.text('音声再生');
        }
    });
});