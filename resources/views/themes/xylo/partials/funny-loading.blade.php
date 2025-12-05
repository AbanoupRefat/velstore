<div id="funnyLoadingOverlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(128, 0, 32, 0.95); z-index: 99999; justify-content: center; align-items: center; flex-direction: column;">
    <div style="text-align: center; color: white; max-width: 90%; padding: 20px;">
        <!-- Spinning Loader -->
        <div class="spinner-container" style="margin-bottom: 40px;">
            <div class="spinner" style="width: 80px; height: 80px; border: 8px solid rgba(255, 255, 255, 0.3); border-top: 8px solid white; border-radius: 50%; margin: 0 auto; animation: spin 1s linear infinite;"></div>
        </div>
        
        <!-- Animated Hoodie Icon -->
        <div style="font-size: clamp(40px, 10vw, 60px); margin-bottom: 30px; animation: bounce 1.5s infinite;">
            👕
        </div>
        
        <!-- Loading Message with Fade Effect -->
        <h2 id="loadingMessage" style="font-size: clamp(18px, 5vw, 28px); font-weight: 600; margin-bottom: 20px; min-height: 60px; font-family: 'Poppins', sans-serif; line-height: 1.4; animation: fadeInOut 2s ease-in-out infinite;">
            Processing your order...
        </h2>
        
        <!-- Progress Dots -->
        <div style="display: flex; gap: 12px; justify-content: center; margin-top: 30px;">
            <div style="width: 12px; height: 12px; background: white; border-radius: 50%; animation: pulse 1.4s infinite ease-in-out both; animation-delay: -0.32s;"></div>
            <div style="width: 12px; height: 12px; background: white; border-radius: 50%; animation: pulse 1.4s infinite ease-in-out both; animation-delay: -0.16s;"></div>
            <div style="width: 12px; height: 12px; background: white; border-radius: 50%; animation: pulse 1.4s infinite ease-in-out both;"></div>
        </div>
    </div>
</div>

<style>
    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    
    @keyframes bounce {
        0%, 100% { 
            transform: translateY(0) scale(1); 
        }
        50% { 
            transform: translateY(-15px) scale(1.1); 
        }
    }
    
    @keyframes pulse {
        0%, 80%, 100% { 
            transform: scale(0);
            opacity: 0.3;
        }
        40% { 
            transform: scale(1);
            opacity: 1;
        }
    }
    
    @keyframes fadeInOut {
        0% { 
            opacity: 0;
            transform: translateY(10px);
        }
        10% {
            opacity: 1;
            transform: translateY(0);
        }
        90% {
            opacity: 1;
            transform: translateY(0);
        }
        100% { 
            opacity: 0;
            transform: translateY(-10px);
        }
    }
    
    #funnyLoadingOverlay {
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px); /* Safari support */
        overscroll-behavior: contain; /* Prevent bounce scroll on mobile */
        -webkit-overflow-scrolling: touch; /* Smooth scrolling on iOS */
        touch-action: none; /* Disable touch interactions */
    }
    
    /* Mobile-specific fixes */
    @media screen and (max-width: 768px) {
        #funnyLoadingOverlay {
            /* Use dvh if supported, fallback to 100vh */
            height: 100dvh;
            height: 100vh;
        }
        
        #loadingMessage {
            font-size: clamp(16px, 4.5vw, 24px);
            min-height: 50px;
        }
    }
    
    /* Very small mobile devices */
    @media screen and (max-width: 375px) {
        .spinner-container .spinner {
            width: 60px !important;
            height: 60px !important;
            border-width: 6px !important;
        }
    }
    
    /* Landscape mobile fix */
    @media screen and (max-height: 500px) and (orientation: landscape) {
        #funnyLoadingOverlay > div {
            padding: 10px !important;
        }
        
        .spinner-container {
            margin-bottom: 20px !important;
        }
        
        #loadingMessage {
            font-size: 16px !important;
            min-height: 40px !important;
            margin-bottom: 10px !important;
        }
    }
</style>

<script>
    const funnyMessages = [
        "Found your hoodie hiding in the warehouse...",
        "Asking our warehouse team for help...",
        "Checking our customer service mood today...",
        "Making sure your hoodie matches the picture...",
        "Teaching the box how to fold properly...",
        "Preparing your hoodie for launch...",
        "Convincing the hoodie to leave its friends...",
        "Predicting your exact delivery time...",
        "Performing quality control circus tricks...",
        "Playing motivational music for the packing team...",
        "Casting a 'fast shipping' spell...",
        "Aiming your package at your doorstep...",
        "Bribing the delivery driver with pizza...",
        "Adding extra love to your package...",
        "Fine-tuning the hoodie's comfort level...",
        "Taking a selfie with your order...",
        "Training carrier pigeons as backup...",
        "Rehearsing the unboxing experience...",
        "Sprinkling magic dust on your order...",
        "Filming your hoodie's journey documentary...",
        "Color-matching with a professional artist...",
        "Calculating the perfect folding angle...",
        "Juggling your order with care...",
        "Consulting the shipping gods...",
        "Serenading your hoodie goodbye..."
    ];

    let messageInterval;
    let currentMessageIndex = 0;
    let shuffledMessages = [];

    function shuffleArray(array) {
        const newArray = [...array];
        for (let i = newArray.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [newArray[i], newArray[j]] = [newArray[j], newArray[i]];
        }
        return newArray;
    }

    function showFunnyLoading() {
        const overlay = document.getElementById('funnyLoadingOverlay');
        if (!overlay) {
            console.error('Loading overlay not found!');
            return;
        }
        
        overlay.style.display = 'flex';
        document.body.style.overflow = 'hidden'; // Prevent scrolling
        document.body.style.position = 'fixed'; // Prevent scroll on iOS
        document.body.style.width = '100%'; // Maintain body width
        
        // Shuffle messages for random order
        shuffledMessages = shuffleArray(funnyMessages);
        currentMessageIndex = 0;
        
        // Show first message immediately
        const messageEl = document.getElementById('loadingMessage');
        if (messageEl) {
            messageEl.textContent = shuffledMessages[0];
        }
        
        // Change message every 2 seconds
        messageInterval = setInterval(() => {
            currentMessageIndex = (currentMessageIndex + 1) % shuffledMessages.length;
            if (messageEl) {
                messageEl.style.animation = 'none';
                setTimeout(() => {
                    messageEl.textContent = shuffledMessages[currentMessageIndex];
                    messageEl.style.animation = 'fadeInOut 2s ease-in-out';
                }, 10);
            }
            
            // Re-shuffle when we've shown all messages
            if (currentMessageIndex === 0) {
                shuffledMessages = shuffleArray(funnyMessages);
            }
        }, 2000);
    }

    function hideFunnyLoading() {
        const overlay = document.getElementById('funnyLoadingOverlay');
        if (overlay) {
            overlay.style.display = 'none';
        }
        document.body.style.overflow = ''; // Restore scrolling
        document.body.style.position = ''; // Restore position
        document.body.style.width = ''; // Restore width
        if (messageInterval) {
            clearInterval(messageInterval);
        }
    }

    // Make functions globally available
    window.showFunnyLoading = showFunnyLoading;
    window.hideFunnyLoading = hideFunnyLoading;
    
    // Debug: Log when script loads
    console.log('Funny loading screen initialized');
</script>