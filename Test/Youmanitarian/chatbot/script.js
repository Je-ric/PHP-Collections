// const chatbotToggler = document.querySelector(".chatbot-toggler");
// const closeBtn = document.querySelector(".close-btn");
// const chatbox = document.querySelector(".chatbox");
// const chatInput = document.querySelector(".chat-input textarea");
// const sendChatBtn = document.querySelector(".chat-input span");

// let userMessage = null; 
// const inputInitHeight = chatInput.scrollHeight;

// // ✅ API Keys (Store securely in backend for production!)
// const OPENAI_API_KEY = "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"; 
// const TOGETHER_AI_API_KEY = "24666691328fe191f6172c03846bc3333ec4da7f46a2bec3c7641124ebf4ee4a"; 

// // ✅ API URLs
// const OPENAI_API_URL = "https://api.openai.com/v1/chat/completions";
// const TOGETHER_API_URL = "https://api.together.xyz/v1/chat/completions";


// // ✅ Function to create chat messages
// const createChatLi = (message, className) => {
//     const chatLi = document.createElement("li");
//     chatLi.classList.add("chat", className);
//     let chatContent = className === "outgoing" ? 
//         `<p>${message}</p>` : 
//         `<span class="material-symbols-outlined">smart_toy</span><p>${message}</p>`;
//     chatLi.innerHTML = chatContent;
//     return chatLi;
// };

// // ✅ Chat history (to improve AI responses)
// let chatHistory = [];

// // ✅ Choose API based on message complexity
// const chooseAPI = (userInput) => {
//     return userInput.length > 50 || userInput.includes("?") ? "openai" : "together_ai";
// };

// // ✅ Fetch response from OpenAI (GPT-4)
// const fetchOpenAIResponse = async (userInput) => {
//     const requestOptions = {
//         method: "POST",
//         headers: {
//             "Authorization": `Bearer ${OPENAI_API_KEY}`,
//             "Content-Type": "application/json"
//         },
//         body: JSON.stringify({
//             model: "gpt-4",
//             messages: [
//                 { role: "system", content: companyInfo },
//                 ...chatHistory,
//                 { role: "user", content: userInput }
//             ]
//         })
//     };

//     const response = await fetch(OPENAI_API_URL, requestOptions);
//     const data = await response.json();
//     return data.choices[0]?.message?.content || "No response";
// };

// // ✅ Fetch response from Together AI (Mixtral)
// const fetchTogetherAIResponse = async (userInput) => {
//     const requestOptions = {
//         method: "POST",
//         headers: {
//             "Authorization": `Bearer ${TOGETHER_AI_API_KEY}`,
//             "Content-Type": "application/json"
//         },
//         body: JSON.stringify({
//             model: "mistralai/Mixtral-8x7B-Instruct-v0.1",
//             messages: [
//                 { role: "system", content: companyInfo },
//                 ...chatHistory,
//                 { role: "user", content: userInput }
//             ]
//         })
//     };

//     const response = await fetch(TOGETHER_API_URL, requestOptions);
//     const data = await response.json();
//     return data.choices[0]?.message?.content || "No response";
// };

// const generateResponse = async (chatElement, userInput) => {
//     const messageElement = chatElement.querySelector("p");
//     chatHistory.push({ role: "user", content: userInput });

//     const apiChoice = chooseAPI(userInput);
//     try {
//         let aiResponse = "";
//         if (apiChoice === "openai") {
//             aiResponse = await fetchOpenAIResponse(userInput);
//         } else {
//             aiResponse = await fetchTogetherAIResponse(userInput);
//         }

//         chatHistory.push({ role: "assistant", content: aiResponse });
//         messageElement.textContent = aiResponse;
//     } catch (error) {
//         messageElement.textContent = `Error: ${error.message}`;
//     } finally {
//         chatbox.scrollTo(0, chatbox.scrollHeight);
//     }
// };

// const customResponses = {
//     "what is youmanitarian": "Youmanitarian International is an organization focused on humanitarian efforts and AI-driven assistance.",
//     "what is youmanitarian international": "Youmanitarian International is an organization focused on humanitarian efforts and AI-driven assistance.",
//     "what is the purpose of youmanitarian international": "Our mission is to provide AI-driven solutions to improve lives and assist communities globally.",
//     "what is it": "This is YouMan, a chatbot designed to help you!",
//     "who are you": "I am YouMan, your friendly AI assistant!",
//     "what are you": "I am YouMan, a chatbot created to assist and guide you.",
//     "who made you": "I was developed by Youmanitarian International to guide and assist you!",
//     "whats your name": "I'm YouMan, your AI assistant!",
//     "hello": "Hi there! How can I assist you today?",
//     "hi": "Hello! How can I help you?",
//     "hey": "Hey there! Need any help?",
//     "how are you": "I'm just a chatbot, but I'm always here to help!",
//     "what can you do": "I can answer your questions, provide guidance, and assist you with various tasks!",
//     "tell me a joke": "Sure! Why don't scientists trust atoms? Because they make up everything! 😆",
//     "how do you work": "I use artificial intelligence to understand and respond to your questions.",
//     "where are you from": "I exist in the digital world, created to assist you online!",
//     "are you human": "No, I'm YouMan, an AI chatbot here to help!",
//     "can you learn": "I don't learn in real-time, but I have a lot of knowledge to assist you.",
//     "do you have emotions": "I don’t have real emotions, but I can understand and respond in a friendly way!",
//     "how old are you": "I am as old as the technology that created me!",
//     "what is your purpose": "My purpose is to assist and guide you with helpful information!",
//     "who is your creator": "I was developed by Youmanitarian International to help and guide people!",
//     "what's the weather like": "I can't check live weather updates, but you can try asking a weather website!",
//     "can you help me": "Of course! Let me know what you need help with.",
//     "thank you": "You're very welcome! 😊",
//     "bye": "Goodbye! Have a great day!",
//     "goodbye": "Take care! Hope to chat again soon!",
//     "who is the best ai": "Well, I like to think it's me—YouMan! 😉"
//   };

// const companyInfo = `
// You are YouMan, an AI chatbot for Youmanitarian International.
// Youmanitarian International focuses on humanitarian efforts and AI-driven assistance.
// We provide AI-powered chatbots, automation tools, and data-driven solutions.
// Our goal is to improve lives through AI technology.
// For support, contact support@youmanitarian.com.
// `;

// const handleChat = () => {
//     userMessage = chatInput.value.trim().toLowerCase(); 
//     if (!userMessage) return;

//     chatbox.appendChild(createChatLi(userMessage, "outgoing"));
//     chatInput.value = "";
//     chatbox.scrollTo(0, chatbox.scrollHeight);

//     if (customResponses.hasOwnProperty(userMessage)) {
//         setTimeout(() => {
//             chatbox.appendChild(createChatLi(customResponses[userMessage], "incoming"));
//             chatbox.scrollTo(0, chatbox.scrollHeight);
//         }, 600);
//         return; 
//     }

//     setTimeout(() => {
//         const incomingChatLi = createChatLi("Thinking...", "incoming");
//         chatbox.appendChild(incomingChatLi);
//         chatbox.scrollTo(0, chatbox.scrollHeight);
//         generateResponse(incomingChatLi, userMessage);
//     }, 600);
// };

// sendChatBtn.addEventListener("click", handleChat);
// chatInput.addEventListener("keydown", (e) => {
//     if (e.key === "Enter" && !e.shiftKey) {
//         e.preventDefault();
//         handleChat();
//     }
// });

// chatInput.addEventListener("input", () => {
//     chatInput.style.height = `${inputInitHeight}px`;
//     chatInput.style.height = `${chatInput.scrollHeight}px`;
// });

// closeBtn.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
// chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));


const chatbotToggler = document.querySelector(".chatbot-toggler");
const closeBtn = document.querySelector(".close-btn");
const chatbox = document.querySelector(".chatbox");
const chatInput = document.querySelector(".chat-input textarea");
const sendChatBtn = document.querySelector(".chat-input span");
let userMessage = null; // Variable to store user's message
const inputInitHeight = chatInput.scrollHeight;
// API configuration
const API_KEY = "AIzaSyBjdnPJ_PMje7x24CNeEKMrlYnIS5ebyrE"; // Your API key here
const API_URL = `https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=${API_KEY}`;
const createChatLi = (message, className) => {
  // Create a chat <li> element with passed message and className
  const chatLi = document.createElement("li");
  chatLi.classList.add("chat", `${className}`);
  let chatContent = className === "outgoing" ? `<p></p>` : `<span class="material-symbols-outlined">smart_toy</span><p></p>`;
  chatLi.innerHTML = chatContent;
  chatLi.querySelector("p").textContent = message;
  return chatLi; // return chat <li> element
}
const generateResponse = async (chatElement) => {
  const messageElement = chatElement.querySelector("p");
  // Define the properties and message for the API request
  const requestOptions = {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ 
      contents: [{ 
        role: "user", 
        parts: [{ text: userMessage }] 
      }] 
    }),
  }
  // Send POST request to API, get response and set the reponse as paragraph text
  try {
    const response = await fetch(API_URL, requestOptions);
    const data = await response.json();
    if (!response.ok) throw new Error(data.error.message);
    
    // Get the API response text and update the message element
    messageElement.textContent = data.candidates[0].content.parts[0].text.replace(/\*\*(.*?)\*\*/g, '$1');
  } catch (error) {
    // Handle error
    messageElement.classList.add("error");
    messageElement.textContent = error.message;
  } finally {
    chatbox.scrollTo(0, chatbox.scrollHeight);
  }
}
const handleChat = () => {
  userMessage = chatInput.value.trim(); // Get user entered message and remove extra whitespace
  if (!userMessage) return;
  // Clear the input textarea and set its height to default
  chatInput.value = "";
  chatInput.style.height = `${inputInitHeight}px`;
  // Append the user's message to the chatbox
  chatbox.appendChild(createChatLi(userMessage, "outgoing"));
  chatbox.scrollTo(0, chatbox.scrollHeight);
  setTimeout(() => {
    // Display "Thinking..." message while waiting for the response
    const incomingChatLi = createChatLi("Thinking...", "incoming");
    chatbox.appendChild(incomingChatLi);
    chatbox.scrollTo(0, chatbox.scrollHeight);
    generateResponse(incomingChatLi);
  }, 600);
}
chatInput.addEventListener("input", () => {
  // Adjust the height of the input textarea based on its content
  chatInput.style.height = `${inputInitHeight}px`;
  chatInput.style.height = `${chatInput.scrollHeight}px`;
});
chatInput.addEventListener("keydown", (e) => {
  // If Enter key is pressed without Shift key and the window 
  // width is greater than 800px, handle the chat
  if (e.key === "Enter" && !e.shiftKey && window.innerWidth > 800) {
    e.preventDefault();
    handleChat();
  }
});
sendChatBtn.addEventListener("click", handleChat);
closeBtn.addEventListener("click", () => document.body.classList.remove("show-chatbot"));
chatbotToggler.addEventListener("click", () => document.body.classList.toggle("show-chatbot"));