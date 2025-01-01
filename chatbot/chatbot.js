document.addEventListener("DOMContentLoaded", () => {
    const chatbotIcon = document.querySelector(".chatbot-icon");
    const chatbotContainer = document.querySelector(".chatbot-container");
    const userInput = document.getElementById("userInput");
    const sendButton = document.getElementById("sendButton");

    // Predefined FAQ answers
    const faqAnswers = {
        "What is the voting process?": "The voting process involves selecting your candidates from the list. You can cast your vote after logging in to the system.",
        "How can I register?": "To register, click on the 'New user? Register here' link on the homepage and fill out your details.",
        "What are the eligibility criteria?": "Only current students of the college are eligible to vote. Please ensure your details are up to date in the system.",
    };

    // Toggle Chatbot
    chatbotIcon.addEventListener("click", () => {
        chatbotContainer.style.display =
            chatbotContainer.style.display === "block" ? "none" : "block";
    });

    // Send User Message
    sendButton.addEventListener("click", () => {
        const message = userInput.value.trim();
        if (message) {
            appendMessage(message, "user");
            fetchBotResponse(message);
            userInput.value = "";
        }
    });

// FAQ Button Clicked
const faqButtons = document.querySelectorAll(".faq-btn");
faqButtons.forEach((button) => {
    button.addEventListener("click", () => {
        const question = button.getAttribute("data-question");
        appendMessage(question, "user");
        fetchBotResponse(question);
    });
});
    // Append messages to the chat
    function appendMessage(message, sender) {
        const messageElement = document.createElement("div");
        messageElement.textContent = message;
        messageElement.className = sender === "user" ? "user-message" : "bot-message";
        chatbotBody.appendChild(messageElement);
        chatbotBody.scrollTop = chatbotBody.scrollHeight;
    }

    // Fetch bot response from project files
    function fetchBotResponse(message) {
        fetch("chatbot/chatbot.php", {  // Updated to include the 'chatbot' folder
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ query: message }),
        })
            .then((response) => response.json())
            .then((data) => {
                appendMessage(data.response, "bot");
            })
            .catch((error) => {
                console.error("Error:", error);
                appendMessage("An error occurred. Please try again later.", "bot");
            });
    }
    
});
