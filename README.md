# Real-Time-Chat-Application



---

# PHP Chat Application

A real-time chat application built with PHP, WebSockets, and MySQL. This project allows users to send and receive messages in real time and supports multiple chat sessions.

## Features

- **Real-time Messaging:** Users can send and receive messages instantly using WebSockets.
- **User Authentication:** Register and log in to use the chat application.
- **Message Persistence:** Messages are saved in the database and can be retrieved upon reopening the chat.
- **Private Chats:** Start a private chat with any user from the contact list.
- **User Profile Pictures:** Display user profile pictures in the chat interface.

## Technologies Used

- **PHP:** Server-side scripting language for handling application logic.
- **WebSockets:** Real-time communication protocol for instant messaging.
- **MySQL:** Database for storing user data and chat messages.
- **HTML/CSS/JavaScript:** Frontend technologies for building the chat interface.

## Getting Started

### Prerequisites

- **PHP** (version 7.4 or higher)
- **MySQL** (version 5.7 or higher)
- **Composer** (for managing PHP dependencies)
- **WebSocket Server** (e.g., Ratchet)

### Installation

1. **Clone the repository:**

   ```bash
   git clone https://github.com/YOUR_USERNAME/YOUR_REPOSITORY.git
   cd YOUR_REPOSITORY
   ```

2. **Install dependencies:**

   ```bash
   composer install
   ```

3. **Set up the database:**

   - Create a MySQL database named `chat_application`.
   - Import the provided SQL schema (`schema.sql`) to set up the required tables.

4. **Configure the application:**

   - Update database connection details in `config/config.php` (if applicable).

5. **Run the WebSocket server:**

   ```bash
   php server.php
   ```

6. **Start the application:**

   - Open `login.php` in your web browser to access the application.

## Usage

1. **Register an account** or **log in** using an existing account.
2. **View the contact list** and start a chat with another user.
3. **Send and receive messages** in real time.

## Contributing

Feel free to open issues or submit pull requests if you have improvements or bug fixes.

1. Fork the repository.
2. Create a new branch for your feature or fix.
3. Make your changes and commit them.
4. Push your branch to your forked repository.
5. Open a pull request to the main repository.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- **Ratchet**: A PHP library for WebSocket.
- **Bootstrap**: For styling and layout (if used).
- **FontAwesome**: For icons (if used).

---

