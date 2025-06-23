<p align="center">
  <img src="/public/assets/icon/dark-icon.webp" width="300" alt="Laravel Logo">
</p>

<h1 align="center">🛠️ Nuanu City Tour AI Agent</h1>

<p align="center">
  A conversational AI system to qualify guests, guide city tours, and log FAQs — powered by Laravel, Firestore, and Airtable.
</p>

---

## ✨ Overview

This project is a **smart AI agent** designed for the **Nuanu City Tour** experience. It:

* Greets guests and collects their names.
* Guides them through city tour options.
* Logs FAQs for future knowledge improvements.

Everything is logged and stored in **Firestore** and **Airtable** for backend analytics and guest tracking.

🎥 **Watch the demo:**
[Loom Video Walkthrough](https://www.loom.com/share/b0b2916e15a74b7598beaf69e22e8603?sid=13aded91-a129-4281-8ef7-8f2b67d7a4b1)
---

## 🔁 Conversational Flow
### 1. 👋 Greeting

The agent welcomes users and asks for their name.
* The name is saved into:

  * 🔥 Firestore: `guest_full_name`
  * 🧊 Airtable: `Guest Info Table`
  
<p align="center">
  <a href="/"><img src="/public/assets/img/WelcomeSection.webp" alt="GitHub Stars"></a>
</p>
<p align="center">
  <a href="/"><img src="/public/assets/img/FirebaseGuest.webp" alt="GitHub Stars"></a>
</p>
<p align="center">
  <a href="/"><img src="/public/assets/img/AirtableGuest.webp" alt="GitHub Stars"></a>
</p>

---

### 2. 🧭 Tour Guide

Once the guest is greeted, the AI provides guided tour options.
It fetches dynamic tour data from Firestore and allows free-form questions.

<p align="center">
  <a href="/"><img src="/public/assets/img/TourSection.webp" alt="GitHub Stars"></a>
</p>
---

### 3. ❓ FAQ Logging

If a guest asks a question (e.g., *"Is this wheelchair accessible?"*), the question is logged for future FAQ creation and improvements.

* 🔄 Synced to both Firestore and Airtable.
<p align="center">
  <a href="/"><img src="/public/assets/img/FirebaseChats.webp" alt="GitHub Stars"></a>
</p>
<p align="center">
  <a href="/"><img src="/public/assets/img/AirtableChats.webp" alt="GitHub Stars"></a>
</p>

---
## 🧩 Tech Stack
* **Laravel** (Backend Framework)
* **OpenAI / AI Integration** (for conversation)
* **Google Firestore** (Realtime Database)
* **Airtable** (Guest & FAQ Logging)
* **JavaScript + Blade** (Frontend UI)
---
## 🚀 Getting Started
1. Clone this repository
2. Run `composer install`
3. Setup `.env` file and connect Firestore & Airtable API
4. Run `php artisan serve`
5. Start chatting!
---
## 🤝 Contributing
Contributions are welcome! Feel free to open issues or pull requests for improvements.
---
## 🛡️ License
This project is open-sourced under the [MIT license](https://opensource.org/licenses/MIT).

---
Let me know if you’d like help embedding your screenshots or adjusting for deployment or documentation for team use!
